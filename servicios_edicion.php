<?php
session_start();

require_once 'conexion_base.php';
require_once 'verificar_sesion_empleado.php';


/* ================= Helpers ================= */
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function nfmt($n){ return number_format((float)$n, 2, ',', '.'); }
function quitar_acentos($str){
    $map = ['Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N','á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n'];
    return strtr($str, $map);
}

/* =============== FILTROS (GET) =============== */
$svc_codigo  = isset($_GET['svc_codigo'])  ? trim($_GET['svc_codigo'])  : '';
$svc_nombre  = isset($_GET['svc_nombre'])  ? trim($_GET['svc_nombre'])  : '';
$svc_desc    = isset($_GET['svc_desc'])    ? trim($_GET['svc_desc'])    : '';
$incl_nd     = isset($_GET['incluir_no_disponibles']) ? 1 : 0;
$nuevo       = isset($_GET['nuevo']) ? 1 : 0; // bandera para abrir modal Nuevo
$msg         = $_GET['msg'] ?? '';

/* Para mantener filtros en redirects */
function filtros_qs($svc_codigo,$svc_nombre,$svc_desc,$incl_nd){
    $arr = ['svc_codigo'=>$svc_codigo,'svc_nombre'=>$svc_nombre,'svc_desc'=>$svc_desc];
    if ($incl_nd) $arr['incluir_no_disponibles']=1;
    return http_build_query($arr);
}
$current_qs = filtros_qs($svc_codigo,$svc_nombre,$svc_desc,$incl_nd);

/* =============== ACCIONES (POST) =============== */
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $accion = $_POST['accion'] ?? '';

    // Persistir filtros al volver
    $f_codigo = $_POST['f_codigo'] ?? $svc_codigo;
    $f_nombre = $_POST['f_nombre'] ?? $svc_nombre;
    $f_desc   = $_POST['f_desc']   ?? $svc_desc;
    $f_nd     = isset($_POST['f_incluir_nd']) ? 1 : $incl_nd;
    $qs = filtros_qs($f_codigo,$f_nombre,$f_desc,$f_nd);

    // IDs seleccionados (usaremos servicio_codigo como identificador único)
    $ids = isset($_POST['ids']) ? array_filter(array_map('trim',(array)$_POST['ids'])) : [];

    // Guardar cambios desde modal Ver (editar costo/estado)
    if ($accion === 'ver_guardar') {
        $codigo = trim((string)($_POST['servicio_codigo'] ?? ''));
        $costo  = max(0, (float)($_POST['servicio_costo'] ?? 0));
        $disp   = (int)($_POST['servicio_disponible'] ?? 1);

        if ($codigo === '') { header("Location: servicios_edicion.php?{$qs}&msg=edit_fail"); exit; }

        $st = $conexion->prepare("
            UPDATE servicios
               SET servicio_costo = :costo,
                   servicio_disponible = :disp
             WHERE servicio_codigo = :codigo
        ");
        $st->execute([':costo'=>$costo, ':disp'=>$disp, ':codigo'=>$codigo]);

        header("Location: servicios_edicion.php?{$qs}&msg=edit_ok");
        exit;
    }

    // Incremento masivo de costo
    if ($accion === 'incrementar_aplicar') {
        if (empty($ids)) { header("Location: servicios_edicion.php?{$qs}&msg=sin_ids"); exit; }
        $porc = (float)($_POST['porcentaje'] ?? 0);
        if ($porc < 0)    { header("Location: servicios_edicion.php?{$qs}&msg=porcentaje_invalido"); exit; }

        $placeholders = implode(',', array_fill(0,count($ids),'?'));
        $sql = "UPDATE servicios
                   SET servicio_costo = ROUND(servicio_costo * (1 + (?/100)), 2)
                 WHERE servicio_codigo IN ($placeholders)";
        $st  = $conexion->prepare($sql);
        $params = array_merge([$porc], $ids);
        $st->execute($params);

        header("Location: servicios_edicion.php?{$qs}&msg=incremento_ok");
        exit;
    }

    // Eliminación lógica por lote (marcar como no disponibles)
    if ($accion === 'eliminar_aplicar') {
        if (empty($ids)) { header("Location: servicios_edicion.php?{$qs}&msg=sin_ids"); exit; }
        $placeholders = implode(',', array_fill(0,count($ids),'?'));
        $sql = "UPDATE servicios SET servicio_disponible = 0 WHERE servicio_codigo IN ($placeholders)";
        $st  = $conexion->prepare($sql);
        $st->execute($ids);

        header("Location: servicios_edicion.php?{$qs}&msg=eliminar_ok");
        exit;
    }

        // ===== Nuevo servicio: guardar =====
    if ($accion === 'nuevo_guardar') {
        // Normalizamos a MAYÚSCULAS para guardar
        $nombre_raw = (string)($_POST['servicio_nombre'] ?? '');
        $desc_raw   = (string)($_POST['servicio_descripcion'] ?? '');
        if (function_exists('mb_strtoupper')) {
            $nombre = mb_strtoupper(trim($nombre_raw), 'UTF-8');
            $desc   = mb_strtoupper(trim($desc_raw), 'UTF-8');
        } else {
            $nombre = strtoupper(trim($nombre_raw));
            $desc   = strtoupper(trim($desc_raw));
        }

        $costo = max(0, (float)($_POST['servicio_costo'] ?? 0));
        $disp  = (int)($_POST['servicio_disponible'] ?? 1);

        if ($nombre === '' || $desc === '') {
            header("Location: servicios_edicion.php?{$qs}&msg=nuevo_invalido&nuevo=1");
            exit;
        }

        // Prefijo de 3 letras para el código (sin acentos ni caracteres no alfabéticos)
        $base    = quitar_acentos(preg_replace('/[^a-zA-Z]/', '', $nombre));
        $prefijo = strtoupper(substr($base, 0, 3));
        if ($prefijo === '') { $prefijo = 'SRV'; } // fallback

        // Buscamos códigos existentes con ese prefijo (3 letras + 2 dígitos exactos)
        $st = $conexion->prepare("
            SELECT servicio_codigo
            FROM servicios
            WHERE servicio_codigo LIKE :pf
            ORDER BY servicio_codigo ASC
        ");
        // '%': puede haber basura histórica, pero por longitud de columna (5) nos limitamos a NN
        $st->execute([':pf' => $prefijo.'%']);
        $existentes = $st->fetchAll(PDO::FETCH_COLUMN);

        // Calculamos el próximo sufijo de 2 dígitos (00..99)
        $usados = [];
        foreach ($existentes as $cod) {
            // Solo tomamos exactamente 3 letras + 2 dígitos
            if (preg_match('/^[A-Z]{3}\d{2}$/', $cod)) {
                $usados[(int)substr($cod, -2)] = true;
            }
        }

        $siguiente = null;
        for ($n = 0; $n <= 99; $n++) {
            if (!isset($usados[$n])) { $siguiente = $n; break; }
        }
        if ($siguiente === null) {
            // No hay más espacio con 2 dígitos para este prefijo
            header("Location: servicios_edicion.php?{$qs}&msg=sin_codigos_disponibles&nuevo=1");
            exit;
        }

        $codigo = $prefijo . str_pad((string)$siguiente, 2, '0', STR_PAD_LEFT);

        // Blindaje por si justo hay colisión de concurrencia
        for ($i=0; $i<5; $i++) {
            $chk = $conexion->prepare("SELECT COUNT(*) FROM servicios WHERE servicio_codigo = ?");
            $chk->execute([$codigo]);
            if ((int)$chk->fetchColumn() === 0) break;
            // Buscar el próximo libre
            $encontro = false;
            for ($n = 0; $n <= 99; $n++) {
                $cand = $prefijo . str_pad((string)$n, 2, '0', STR_PAD_LEFT);
                $chk->execute([$cand]);
                if ((int)$chk->fetchColumn() === 0) { $codigo = $cand; $encontro = true; break; }
            }
            if (!$encontro) {
                header("Location: servicios_edicion.php?{$qs}&msg=sin_codigos_disponibles&nuevo=1");
                exit;
            }
        }

        // Insertamos guardando NOMBRE y DESCRIPCIÓN en MAYÚSCULAS
        $ins = $conexion->prepare("
            INSERT INTO servicios
                (servicio_codigo, servicio_nombre, servicio_descripcion, servicio_costo, servicio_disponible)
            VALUES (:cod, :nom, :des, :costo, :disp)
        ");
        $ins->execute([
            ':cod'   => $codigo,
            ':nom'   => $nombre,
            ':des'   => $desc,
            ':costo' => $costo,
            ':disp'  => $disp
        ]);

        header("Location: servicios_edicion.php?{$qs}&msg=nuevo_ok&nuevo_codigo=".urlencode($codigo));
        exit;
    }
    
}

/* =============== LISTADO (GET) =============== */
$where=[]; $params=[];
if (!$incl_nd) { $where[] = "servicio_disponible = 1"; }
if ($svc_codigo !== '') { $where[] = "servicio_codigo LIKE :codigo";       $params[':codigo'] = "%{$svc_codigo}%"; }
if ($svc_nombre !== '') { $where[] = "servicio_nombre LIKE :nombre";       $params[':nombre'] = "%{$svc_nombre}%"; }
if ($svc_desc   !== '') { $where[] = "servicio_descripcion LIKE :descr";   $params[':descr']  = "%{$svc_desc}%"; }

$sql = "SELECT servicio_codigo, servicio_nombre, servicio_descripcion, servicio_costo, servicio_disponible
          FROM servicios";
if ($where) $sql .= " WHERE ".implode(' AND ',$where);
$sql .= " ORDER BY servicio_nombre ASC, servicio_codigo ASC";
$st = $conexion->prepare($sql);
$st->execute($params);
$servicios = $st->fetchAll(PDO::FETCH_ASSOC);

/* =============== MODAL VER (GET ?ver=CODIGO) =============== */
$verCodigo = isset($_GET['ver']) ? trim($_GET['ver']) : '';
$servVer = null;
if ($verCodigo !== '') {
    $st = $conexion->prepare("SELECT * FROM servicios WHERE servicio_codigo = ?");
    $st->execute([$verCodigo]);
    $servVer = $st->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
    <?php include("nav_gerente.php"); ?>
    <?php if (($msg ?? '') === 'nuevo_ok'): ?>
        <dialog open>
            <p><strong>✅ Servicio guardado correctamente.</strong><br>
            Código: <strong><?= e($_GET['nuevo_codigo'] ?? '') ?></strong>
            </p>
            <form method="get" action="servicios_edicion.php">
            <!-- preservo filtros si querés; si no, dejalo simple -->
            <?php if (!empty($svc_codigo)): ?><input type="hidden" name="svc_codigo" value="<?= e($svc_codigo) ?>"><?php endif; ?>
            <?php if (!empty($svc_nombre)): ?><input type="hidden" name="svc_nombre" value="<?= e($svc_nombre) ?>"><?php endif; ?>
            <?php if (!empty($svc_desc)):   ?><input type="hidden" name="svc_desc"   value="<?= e($svc_desc)   ?>"><?php endif; ?>
            <?php if ($incl_nd): ?><input type="hidden" name="incluir_no_disponibles" value="1"><?php endif; ?>
            <button type="submit">Aceptar</button>
            </form>
        </dialog>
<?php endif; ?>
<div class="servicios_ger">
    <h2>Listado de Servicios</h2>

    <div class="servicios_ger_tit">
        <?php if ($msg === 'incremento_ok'): ?><div style="color:#1e824c;">Incremento aplicado correctamente.</div><?php endif; ?>
        <?php if ($msg === 'eliminar_ok'): ?><div style="color:#1e824c;">Servicios marcados como no disponibles.</div><?php endif; ?>
        <?php if ($msg === 'edit_ok'): ?><div style="color:#1e824c;">Servicio modificado correctamente.</div><?php endif; ?>
        <?php if ($msg === 'sin_ids'): ?><div style="color:#c0392b;">Seleccioná al menos un servicio.</div><?php endif; ?>
        <?php if ($msg === 'porcentaje_invalido'): ?><div style="color:#c0392b;">Porcentaje inválido.</div><?php endif; ?>
        <?php if ($msg === 'nuevo_ok'): ?>
            <div style="color:#1e824c;">Servicio creado (código: <?= e($_GET['nuevo_codigo'] ?? '') ?>).</div>
        <?php elseif ($msg === 'nuevo_invalido'): ?>
            <div style="color:#c0392b;">Completá nombre y descripción.</div>
        <?php elseif ($msg === 'edit_fail'): ?>
            <div style="color:#c0392b;">No se pudo actualizar el servicio.</div>
        <?php endif; ?>
    </div>

    <!-- Buscador -->
    <form method="get" action="servicios_edicion.php">
        <label for="svc_codigo">Código</label>
        <input class="servicios_ger_input" type="text" id="svc_codigo" name="svc_codigo" value="<?= e($svc_codigo) ?>" placeholder="Ej: FRE001">

        <label for="svc_nombre">Nombre</label>
        <input class="servicios_ger_input" type="text" id="svc_nombre" name="svc_nombre" value="<?= e($svc_nombre) ?>" placeholder="Alineación, Frenado…">

        <label for="svc_desc">Descripción</label>
        <input class="servicios_ger_input" type="text" id="svc_desc" name="svc_desc" value="<?= e($svc_desc) ?>" placeholder="Buscar en descripción">

        <label>
            <input class="servicios_ger_check" type="checkbox" name="incluir_no_disponibles" value="1" <?= $incl_nd ? 'checked' : '' ?>>
            Incluir no disponibles
        </label>
      <div class="acciones_ger">
        <button type="submit" name="buscar">Buscar</button>
        <a href="servicios_edicion.php">Limpiar</a>
        <a href="servicios_edicion.php?<?= e($current_qs) ?>&nuevo=1">Nuevo</a>
        <a href="gerente.php">Volver</a>
      </div>
    </form>

    <!-- FORM PRINCIPAL: acciones + tabla + ids[] -->
    <form id="formServicios" method="post" action="servicios_edicion.php?<?= e($current_qs) ?>">
        <div class="acciones_ger">
            <button name="accion" value="incrementar">Incrementar precio</button>
            <button  name="accion" value="eliminar">Eliminar</button>
        </div>

        <table>
            <thead>
            <tr>
                <th>
                    <input  class="servicios_ger_check" type="checkbox" id="check_all"
                        onclick="document.querySelectorAll('.check_row').forEach(c=>c.checked=this.checked);">
                </th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th >Costo</th>
                <th>Estado</th>
                <th >Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$servicios): ?>
                <tr><td colspan="7">No se encontraron servicios con los filtros aplicados.</td></tr>
            <?php else: ?>
                <?php foreach ($servicios as $s): ?>
                    <?php
                        $noDisp = (int)$s['servicio_disponible'] === 0;
                        $rowClass = $noDisp ? 'fila-nd' : '';
                        $estadoTxt = $noDisp ? 'No disponible' : 'Disponible';
                        $estadoCls = $noDisp ? 'nodisp' : 'disp';
                        $verUrl = "servicios_edicion.php?ver=".urlencode($s['servicio_codigo'])."&".$current_qs;
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td><input class="servicios_ger_check" type="checkbox" name="ids[]" value="<?= e($s['servicio_codigo']) ?>"></td>
                        <td><?= e($s['servicio_codigo']) ?></td>
                        <td><?= e($s['servicio_nombre']) ?></td>
                        <td><?= e($s['servicio_descripcion']) ?></td>
                        <td >$ <?= nfmt($s['servicio_costo']) ?></td>
                        <td><span class="estado-tag <?= $estadoCls ?>"><?= $estadoTxt ?></span></td>
                        <td><a class="btn_serv_ger" href="<?= e($verUrl) ?>" title="Ver / Modificar">🔍</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<?php
/* ================ MODALES (render server-side) ================= */

/* Modal: Nuevo servicio */
if ($nuevo) { ?>
  <div class="modal-backdrop">
    <div class="modal">
      <h3>Nuevo servicio</h3>
      <form method="post" action="servicios_edicion.php?<?= e($current_qs) ?>">
        <input type="hidden" name="accion" value="nuevo_guardar">
        <!-- Persistir filtros -->
        <input type="hidden" name="f_codigo" value="<?= e($svc_codigo) ?>">
        <input type="hidden" name="f_nombre" value="<?= e($svc_nombre) ?>">
        <input type="hidden" name="f_desc"   value="<?= e($svc_desc) ?>">
        <?php if ($incl_nd): ?><input type="hidden" name="f_incluir_nd" value="1"><?php endif; ?>

        <label>Nombre:</label><br>
        <input type="text" name="servicio_nombre" maxlength="120" required style="min-width:420px;">
        <br><br>

        <label>Descripción:</label><br>
        <textarea name="servicio_descripcion" maxlength="500" required style="min-width:420px; height:90px;"></textarea>
        <br><br>

        <label>Costo:</label>
        <input type="number" step="1" min="0" name="servicio_costo" value="0" required>
        <br><br>

        <label>Estado:</label>
        <select name="servicio_disponible">
          <option value="1" selected>Disponible</option>
          <option value="0">No disponible</option>
        </select>

        <p><em>El <b>código</b> se generará automáticamente con las 3 primeras letras del nombre y numeración correlativa (p. ej. <b>FRE001</b>).</em></p>

        <div class="acciones">
          <button class="btn btn-primario" type="submit">Guardar</button>
          <a class="btn btn-neutro" href="servicios_edicion.php?<?= e($current_qs) ?>">Volver</a>
        </div>
      </form>
    </div>
  </div>
<?php }

/* Modal: Incrementar precio (GET POST accion=incrementar) */
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['accion'] ?? '')==='incrementar') {
    $ids = isset($_POST['ids']) ? array_filter(array_map('trim',(array)$_POST['ids'])) : []; ?>
    <div class="modal-backdrop">
      <div class="modal">
        <h3>Incrementar precio</h3>
        <?php if (empty($ids)): ?>
          <p>No seleccionaste servicios.</p>
          <div class="acciones">
            <a class="btn btn-neutro" href="servicios_edicion.php?<?= e($current_qs) ?>">Volver</a>
          </div>
        <?php else: ?>
          <p>Seleccionados: <strong><?= count($ids) ?></strong> servicio(s).</p>
          <form method="post" action="servicios_edicion.php?<?= e($current_qs) ?>">
            <?php foreach ($ids as $id): ?><input type="hidden" name="ids[]" value="<?= e($id) ?>"><?php endforeach; ?>
            <input type="hidden" name="f_codigo" value="<?= e($svc_codigo) ?>">
            <input type="hidden" name="f_nombre" value="<?= e($svc_nombre) ?>">
            <input type="hidden" name="f_desc"   value="<?= e($svc_desc) ?>">
            <?php if ($incl_nd): ?><input type="hidden" name="f_incluir_nd" value="1"><?php endif; ?>

            <label>Porcentaje (%): </label>
            <input type="number" name="porcentaje" step="1" min="0" required>
            <div class="acciones">
              <button class="btn btn-primario" name="accion" value="incrementar_aplicar">Aplicar</button>
              <a class="btn btn-neutro" href="servicios_edicion.php?<?= e($current_qs) ?>">Cancelar</a>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
<?php }

/* Modal: Confirmar eliminación lógica (GET POST accion=eliminar) */
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['accion'] ?? '')==='eliminar') {
    $ids = isset($_POST['ids']) ? array_filter(array_map('trim',(array)$_POST['ids'])) : []; ?>
    <div class="modal-backdrop">
      <div class="modal">
        <h3>Eliminar servicios (lógico)</h3>
        <?php if (empty($ids)): ?>
          <p>No seleccionaste servicios.</p>
          <div class="acciones">
            <a class="btn btn-neutro" href="servicios_edicion.php?<?= e($current_qs) ?>">Volver</a>
          </div>
        <?php else: ?>
          <p>Se marcarán como <strong>No disponibles</strong> <b><?= count($ids) ?></b> servicio(s).</p>
          <form method="post" action="servicios_edicion.php?<?= e($current_qs) ?>">
            <?php foreach ($ids as $id): ?><input type="hidden" name="ids[]" value="<?= e($id) ?>"><?php endforeach; ?>
            <input type="hidden" name="f_codigo" value="<?= e($svc_codigo) ?>">
            <input type="hidden" name="f_nombre" value="<?= e($svc_nombre) ?>">
            <input type="hidden" name="f_desc"   value="<?= e($svc_desc) ?>">
            <?php if ($incl_nd): ?><input type="hidden" name="f_incluir_nd" value="1"><?php endif; ?>

            <div class="acciones">
              <button class="btn btn-peligro" name="accion" value="eliminar_aplicar">Sí, eliminar</button>
              <a class="btn btn-neutro" href="servicios_edicion.php?<?= e($current_qs) ?>">Cancelar</a>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
<?php }

/* Modal: Ver/Editar servicio (lupa) */
if ($servVer) { ?>
  <div class="modal-backdrop">
    <div class="modal">
      <h3><?= e($servVer['servicio_nombre']) ?></h3>
      <table>
        <tr><th style="width:220px;">Código</th><td><?= e($servVer['servicio_codigo']) ?></td></tr>
        <tr><th>Descripción</th><td><?= e($servVer['servicio_descripcion']) ?></td></tr>
        <tr><th>Costo (actual)</th><td>$ <?= nfmt($servVer['servicio_costo']) ?></td></tr>
      </table>
      <br>
      <form method="post" action="servicios_edicion.php?<?= e($current_qs) ?>">
        <input type="hidden" name="accion" value="ver_guardar">
        <input type="hidden" name="servicio_codigo" value="<?= e($servVer['servicio_codigo']) ?>">
        <input type="hidden" name="f_codigo" value="<?= e($svc_codigo) ?>">
        <input type="hidden" name="f_nombre" value="<?= e($svc_nombre) ?>">
        <input type="hidden" name="f_desc"   value="<?= e($svc_desc) ?>">
        <?php if ($incl_nd): ?><input type="hidden" name="f_incluir_nd" value="1"><?php endif; ?>

        <label>Estado:</label>
        <select name="servicio_disponible">
          <option value="1" <?= ((int)$servVer['servicio_disponible'] ? 'selected':'') ?>>Disponible</option>
          <option value="0" <?= ((int)$servVer['servicio_disponible'] ? '' : 'selected') ?>>No disponible</option>
        </select>
        <br><br>

        <label>Nuevo costo:</label>
        <input type="number" step="1" min="0" name="servicio_costo" value="<?= e($servVer['servicio_costo']) ?>" required>

        <div class="acciones">
          <button class="btn btn-primario" type="submit">Guardar</button>
          <a class="btn btn-neutro" href="servicios_edicion.php?<?= e($current_qs) ?>">Volver</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
</body>
</html>
