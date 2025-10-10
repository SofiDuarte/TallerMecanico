<?php
session_start();
require_once 'verificar_sesion_empleado.php';
require_once 'conexion_base.php';

// Trae SOLO finalizados y SIN facturar
$sql = "
SELECT 
  o.orden_numero, o.orden_fecha,
  c.cliente_nombre, c.cliente_DNI, c.cliente_direccion, c.cliente_telefono, c.cliente_email,
  v.vehiculo_patente, v.vehiculo_marca, v.vehiculo_modelo,
  s.servicio_codigo, s.servicio_nombre,
  ot.orden_comentario, ot.costo_ajustado
FROM orden_trabajo ot
JOIN ordenes   o ON o.orden_numero = ot.orden_numero
JOIN servicios s ON s.servicio_codigo = ot.servicio_codigo
JOIN vehiculos v ON v.vehiculo_patente = o.vehiculo_patente
JOIN clientes  c ON c.cliente_DNI = v.cliente_DNI
WHERE ot.orden_estado = 1       -- finalizado
  AND ot.factura_id IS NULL     -- sin facturar
ORDER BY o.orden_fecha DESC, o.orden_numero DESC
";
$rows = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Facturación</title>
  <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
  <style>
    .tabla-fact { width: 90%; margin: 20px auto; border-collapse: collapse; }
    .tabla-fact th, .tabla-fact td { border:1px solid #FF8E31; padding:8px; text-align:center; font-family:'Big_Shoulders_Regular'; }
    .tabla-fact th { background: #ff8e31a8; }
    .btn-icono { cursor:pointer; border:none; background:#3AAFAF; color:#000; padding:6px 10px; border-radius:8px; }
    .btn-icono:hover { filter:brightness(0.95); }
    .mini { font-size:12px; opacity:.8 }
    .fila-vacia td { color:#777; font-style:italic; }
    .modal-row { margin: 8px 0; text-align:left; }
    .modal-row label { margin-right: 10px; cursor: pointer; }
    .modal-actions { margin-top:12px; text-align:center; }
  </style>
</head>
<body>
<?php include 'nav_rec.php'; ?>

<main class="his_vehiculo_cli">
  <h2 style="text-align:center;margin:16px 0;">Trabajos finalizados pendientes de facturar</h2>

  <table class="tabla-fact">
    <thead>
      <tr>
        <th>Orden</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>DNI</th>
        <th>Patente</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Servicio</th>
        <th>Costo ajustado</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$rows): ?>
        <tr class="fila-vacia"><td colspan="10">No hay trabajos pendientes de facturar.</td></tr>
      <?php else: foreach ($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['orden_numero']) ?></td>
          <td><?= htmlspecialchars($r['orden_fecha']) ?></td>
          <td><?= htmlspecialchars($r['cliente_nombre']) ?></td>
          <td><?= htmlspecialchars($r['cliente_DNI']) ?></td>
          <td><?= htmlspecialchars($r['vehiculo_patente']) ?></td>
          <td><?= htmlspecialchars($r['vehiculo_marca']) ?></td>
          <td><?= htmlspecialchars($r['vehiculo_modelo']) ?></td>
          <td>
            <div><strong><?= htmlspecialchars($r['servicio_codigo']) ?></strong> - <?= htmlspecialchars($r['servicio_nombre']) ?></div>
            <?php if (trim((string)$r['orden_comentario'])!==''): ?>
              <div class="mini"><?= htmlspecialchars($r['orden_comentario']) ?></div>
            <?php endif; ?>
          </td>
          <td>$ <?= number_format((float)$r['costo_ajustado'], 2, ',', '.') ?></td>
          <td>
            <button type="button" class="btn-icono"
              onclick="abrirModalFactura(this)"
              data-orden_numero="<?= htmlspecialchars($r['orden_numero']) ?>"
              data-orden_fecha="<?= htmlspecialchars($r['orden_fecha']) ?>"
              data-cliente_nombre="<?= htmlspecialchars($r['cliente_nombre']) ?>"
              data-cliente_dni="<?= htmlspecialchars($r['cliente_DNI']) ?>"
              data-cliente_direccion="<?= htmlspecialchars($r['cliente_direccion']) ?>"
              data-cliente_telefono="<?= htmlspecialchars($r['cliente_telefono']) ?>"
              data-cliente_email="<?= htmlspecialchars($r['cliente_email']) ?>"
              data-vehiculo_patente="<?= htmlspecialchars($r['vehiculo_patente']) ?>"
              data-vehiculo_marca="<?= htmlspecialchars($r['vehiculo_marca']) ?>"
              data-vehiculo_modelo="<?= htmlspecialchars($r['vehiculo_modelo']) ?>"
              data-servicio_codigo="<?= htmlspecialchars($r['servicio_codigo']) ?>"
              data-servicio_nombre="<?= htmlspecialchars($r['servicio_nombre']) ?>"
              data-orden_comentario="<?= htmlspecialchars($r['orden_comentario']) ?>"
              data-costo_ajustado="<?= htmlspecialchars($r['costo_ajustado']) ?>"
            >🧾 Facturar</button>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</main>

<!-- MODAL -->
<dialog id="modal_factura">
  <form id="form_facturar" method="post" action="generar_factura.php"
        target="facturaWin" onsubmit="return onSubmitFactura(this)">
    <h3 style="margin-bottom:10px;">Emitir factura</h3>

    <div class="modal-row">
      <strong>Tipo:</strong>
      <label><input type="radio" name="tipo" value="A"> A</label>
      <label><input type="radio" name="tipo" value="B" checked> B</label>
      <label><input type="radio" name="tipo" value="C"> C</label>
    </div>

    <div class="modal-row">
      <strong>Acción:</strong>
      <label><input type="radio" name="accion" value="imprimir" checked> Imprimir</label>
      <label><input type="radio" name="accion" value="email"> Email</label>
    </div>

    <div id="email_box" class="modal-row" style="display:none;">
      <label for="email_destino"><strong>Email destino:</strong></label>
      <input type="email" name="email_destino" id="email_destino" style="width:260px;">
      <div class="mini">Si lo dejás vacío usa el email del cliente.</div>
    </div>

    <!-- Hidden inputs con todos los datos -->
    <input type="hidden" name="orden_numero">
    <input type="hidden" name="orden_fecha">
    <input type="hidden" name="cliente_nombre">
    <input type="hidden" name="cliente_dni">
    <input type="hidden" name="cliente_direccion">
    <input type="hidden" name="cliente_telefono">
    <input type="hidden" name="cliente_email">
    <input type="hidden" name="vehiculo_patente">
    <input type="hidden" name="vehiculo_marca">
    <input type="hidden" name="vehiculo_modelo">
    <input type="hidden" name="servicio_codigo">
    <input type="hidden" name="servicio_nombre">
    <input type="hidden" name="orden_comentario">
    <input type="hidden" name="costo_ajustado">

    <div class="modal-actions">
      <button class="guardar_rec" type="submit">Generar</button>
      <button class="cancelar_boton" type="button" onclick="cerrarModalFactura()">Cancelar</button>
    </div>
  </form>
</dialog>

<script>
// Abre y precarga el modal
function abrirModalFactura(btn){
  try {
    const d = document.getElementById('modal_factura');
    if (!d) { alert('No se encontró el modal de factura.'); return; }

    const f = document.getElementById('form_facturar');

    const names = [
      'orden_numero','orden_fecha','cliente_nombre','cliente_dni','cliente_direccion',
      'cliente_telefono','cliente_email','vehiculo_patente','vehiculo_marca','vehiculo_modelo',
      'servicio_codigo','servicio_nombre','orden_comentario','costo_ajustado'
    ];

    names.forEach(n => {
      if (f.elements[n]) f.elements[n].value = btn.dataset[n] ?? '';
    });

    // Defaults (B + imprimir)
    f.querySelectorAll('input[name="tipo"]').forEach(r => r.checked = (r.value === 'C'));
    f.querySelectorAll('input[name="accion"]').forEach(r => r.checked = (r.value === 'imprimir'));
    document.getElementById('email_box').style.display = 'none';
    document.getElementById('email_destino').value = '';

    if (typeof d.showModal === 'function') d.showModal();
    else d.setAttribute('open','open'); // fallback simple

  } catch (e) {
    console.error(e);
    alert('Error al abrir el modal de factura: ' + e.message);
  }
}

function cerrarModalFactura() {
  const d = document.getElementById('modal_factura');
  if (typeof d.close === 'function') d.close(); else d.removeAttribute('open');
}

// Mostrar/ocultar email si se elige esa acción
document.getElementById('form_facturar').addEventListener('change', (e)=>{
  if (e.target.name === 'accion') {
    document.getElementById('email_box').style.display =
      (e.target.value === 'email') ? 'block' : 'none';
  }
});

// Envío seguro: abre ventana, cierra modal y refresca
let _enviandoFactura = false;
function onSubmitFactura(form) {
  if (_enviandoFactura) return false; // anti doble click
  _enviandoFactura = true;

  // Abrimos/obtenemos la ventana destino ANTES de enviar (evita bloqueos)
  const win = window.open('', 'facturaWin');
  if (!win) {
    // Si el popup fue bloqueado, mandamos en la misma pestaña
    form.removeAttribute('target');
  }

  // Cerramos el modal YA
  cerrarModalFactura();

  // Damos tiempo a que se abra el PDF / procese email y refrescamos la tabla
  setTimeout(() => {
    window.location.reload();
    // o explícito: window.location.href = 'facturacion.php';
  }, 500);

  return true; // dejamos que el form se envíe
}
</script>

</body>
</html>
