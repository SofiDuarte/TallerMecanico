<?php
require_once 'verificar_sesion_empleado.php';
require_once 'conexion_base.php';
require_once 'tcpdf/tcpdf.php';

// ---- Inputs desde el modal ----
$tipo              = $_POST['tipo'] ?? 'B';
$accion            = $_POST['accion'] ?? 'imprimir';   // imprimir | email
$email_destino     = trim($_POST['email_destino'] ?? '');
$orden_numero      = (int)($_POST['orden_numero'] ?? 0);
$orden_fecha       = $_POST['orden_fecha'] ?? '';
$cliente_nombre    = $_POST['cliente_nombre'] ?? '';
$cliente_dni       = $_POST['cliente_dni'] ?? '';
$cliente_direccion = $_POST['cliente_direccion'] ?? '';
$cliente_telefono  = $_POST['cliente_telefono'] ?? '';
$cliente_email     = $_POST['cliente_email'] ?? '';
$veh_patente       = $_POST['vehiculo_patente'] ?? '';
$veh_marca         = $_POST['vehiculo_marca'] ?? '';
$veh_modelo        = $_POST['vehiculo_modelo'] ?? '';
$serv_codigo       = $_POST['servicio_codigo'] ?? '';
$serv_nombre       = $_POST['servicio_nombre'] ?? '';
$orden_comentario  = $_POST['orden_comentario'] ?? '';
$costo_ajustado    = (float)($_POST['costo_ajustado'] ?? 0);

if ($accion === 'email' && $email_destino === '') {
  $email_destino = $cliente_email;
}

$empleado_dni = $_SESSION['empleado_dni'] ?? null;

// ---- Datos del taller (ajustá a los reales) ----
$taller_nombre = "WA SPORT - Taller Mecánico";
$taller_dir    = "Portela 1136 - CABA";
$taller_tel    = "Tel: 11-1234-5678";
$taller_mail   = "taller@wasport.com";
$logo_path     = 'iconos/WA_Sport.jpg';

// ========== 1) TRANSACCIÓN: numerar + insertar factura + marcar trabajo ==========
try {
  $conexion->beginTransaction();

  // 1.a) Numerador por tipo (A/B/C)
  $stmt = $conexion->prepare("SELECT proximo FROM factura_numeradores WHERE tipo = ? FOR UPDATE");
  $stmt->execute([$tipo]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    $conexion->prepare("INSERT INTO factura_numeradores (tipo, proximo) VALUES (?,1)")
             ->execute([$tipo]);
    $nro_comprobante = 1;
    $conexion->prepare("UPDATE factura_numeradores SET proximo = proximo + 1 WHERE tipo = ?")
             ->execute([$tipo]);
  } else {
    $nro_comprobante = (int)$row['proximo'];
    $conexion->prepare("UPDATE factura_numeradores SET proximo = proximo + 1 WHERE tipo = ?")
             ->execute([$tipo]);
  }

  // 1.b) Insertar factura
  $ins = $conexion->prepare("
    INSERT INTO facturas
      (tipo, nro_comprobante, orden_numero, servicio_codigo, cliente_dni, vehiculo_patente,
       total, pdf_nombre, email_destino, email_enviado, empleado_emisor)
    VALUES
      (:tipo, :nro, :orden, :serv, :dni, :patente, :total, NULL, :email, 0, :emp)
  ");
  $ins->execute([
    ':tipo'    => $tipo,
    ':nro'     => $nro_comprobante,
    ':orden'   => $orden_numero,
    ':serv'    => $serv_codigo,
    ':dni'     => $cliente_dni,
    ':patente' => $veh_patente,
    ':total'   => $costo_ajustado,
    ':email'   => ($accion === 'email') ? $email_destino : null,
    ':emp'     => $empleado_dni
  ]);
  $factura_id = (int)$conexion->lastInsertId();

  // 1.c) Marcar trabajo como facturado (enlazar factura)
  $up = $conexion->prepare("
    UPDATE orden_trabajo
       SET factura_id = :fid
     WHERE orden_numero = :orden AND servicio_codigo = :serv AND factura_id IS NULL
  ");
  $up->execute([
    ':fid'   => $factura_id,
    ':orden' => $orden_numero,
    ':serv'  => $serv_codigo
  ]);
  if ($up->rowCount() === 0) {
    throw new Exception("El trabajo ya estaba facturado o no existe.");
  }

  $conexion->commit();

} catch (Throwable $e) {
  $conexion->rollBack();
  die("Error al numerar o vincular factura: " . $e->getMessage());
}

// ========== 2) Generar PDF con TCPDF ==========
class PDF_FACT extends TCPDF {
    public $tipo;
    public $nro_comprobante;
    public $taller_nombre;
    public $taller_dir;
    public $taller_tel;
    public $taller_mail;
    public $logo_path;

    public function Header() {
        if (is_file($this->logo_path)) {
            $this->Image($this->logo_path, 10, 8, 35);
        }
        // Caja letra (A/B/C)
        $xBox = 60; $yBox = 10; $wBox = 18; $hBox = 18;
        $this->SetDrawColor(0,0,0);
        $this->Rect($xBox, $yBox, $wBox, $hBox);
        $this->SetFont('dejavusans','B',16);
        $this->SetXY($xBox, $yBox+2);
        $this->Cell($wBox, 8, $this->tipo, 0, 2, 'C');
        $this->SetFont('dejavusans','',8);
        $this->Cell($wBox, 6, 'Código N° 06', 0, 0, 'C');

        // membrete
        $this->SetXY(0, 8);
        $this->SetFont('dejavusans','B',14);
        $this->Cell(0,6,$this->taller_nombre,0,1,'R');
        $this->SetFont('dejavusans','',9);
        $this->Cell(0,5,$this->taller_dir,0,1,'R');
        $this->Cell(0,5,$this->taller_tel.' - '.$this->taller_mail,0,1,'R');

        // Título + Número de comprobante
        $this->Ln(2);
        $this->SetFont('dejavusans','B',16);
        $this->Cell(0,10,'FACTURA',0,1,'C');
        $this->SetFont('dejavusans','',11);
        $this->Cell(0,8,'Comprobante Nº '.str_pad($this->nro_comprobante, 8, '0', STR_PAD_LEFT), 0, 1, 'C');

        // línea separadora
        $this->SetDrawColor(255,142,49);
        $this->SetLineWidth(0.6);
        $this->Line(10,42,200,42);
        $this->Ln(3);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('dejavusans','I',8);
        $this->Cell(0,10,'Generado por WA SPORT - '.date('Y-m-d H:i'),0,0,'C');
    }
}

$pdf = new PDF_FACT('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->tipo            = $tipo;
$pdf->nro_comprobante = $nro_comprobante;
$pdf->taller_nombre   = $taller_nombre;
$pdf->taller_dir      = $taller_dir;
$pdf->taller_tel      = $taller_tel;
$pdf->taller_mail     = $taller_mail;
$pdf->logo_path       = $logo_path;

$pdf->SetCreator('WA SPORT');
$pdf->SetAuthor('WA SPORT');
$pdf->SetTitle("Factura $tipo - $nro_comprobante - Orden $orden_numero");
$pdf->SetSubject('Factura');

$pdf->SetMargins(10, 48, 10);
$pdf->SetHeaderMargin(8);
$pdf->SetFooterMargin(12);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->AddPage();
$pdf->SetFont('dejavusans','',10);

// Encabezado Orden/Fecha
$tbl = '
<table cellpadding="6" cellspacing="0" border="1" width="100%">
  <tr style="background-color:#FFC631;">
    <td width="50%"><b>Orden N°:</b> '.htmlspecialchars($orden_numero).'</td>
    <td width="50%"><b>Fecha:</b> '.htmlspecialchars($orden_fecha).'</td>
  </tr>
</table><br/>';
$pdf->writeHTML($tbl, true, false, false, false, '');

// Datos Cliente
$cliente_bloque = '
<h3>Datos del Cliente</h3>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
  <tr><td><b>Nombre:</b> '.htmlspecialchars($cliente_nombre).'</td></tr>
  <tr><td><b>DNI:</b> '.htmlspecialchars($cliente_dni).'</td></tr>
  <tr><td><b>Dirección:</b> '.htmlspecialchars($cliente_direccion).'</td></tr>
  <tr><td><b>Teléfono:</b> '.htmlspecialchars($cliente_telefono).'</td></tr>
  <tr><td><b>Email:</b> '.htmlspecialchars($cliente_email).'</td></tr>
</table><br/>';
$pdf->writeHTML($cliente_bloque, true, false, false, false, '');

// Datos Vehículo
$veh_bloque = '
<h3>Datos del Vehículo</h3>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
  <tr><td><b>Patente:</b> '.htmlspecialchars($veh_patente).'</td></tr>
  <tr><td><b>Marca:</b> '.htmlspecialchars($veh_marca).'</td></tr>
  <tr><td><b>Modelo:</b> '.htmlspecialchars($veh_modelo).'</td></tr>
</table><br/>';
$pdf->writeHTML($veh_bloque, true, false, false, false, '');

// Detalle Servicio
$detalle_tbl = '
<h3>Detalle del Servicio</h3>
<table cellpadding="6" cellspacing="0" border="1" width="100%">
  <tr style="background-color:#FFE0AA;">
    <th width="20%"><b>Código</b></th>
    <th width="55%"><b>Servicio</b></th>
    <th width="25%"><b>Costo</b></th>
  </tr>
  <tr>
    <td align="center">'.htmlspecialchars($serv_codigo).'</td>
    <td>'.htmlspecialchars($serv_nombre).'</td>
    <td align="right">$ '.number_format($costo_ajustado, 2, ',', '.').'</td>
  </tr>
</table>';
$pdf->writeHTML($detalle_tbl, true, false, false, false, '');

// Comentario
if (trim($orden_comentario) !== '') {
  $pdf->Ln(2);
  $coment_html = '
  <table cellpadding="5" cellspacing="0" border="0" width="100%">
    <tr><td><b>Comentario:</b><br/>'.nl2br(htmlspecialchars($orden_comentario)).'</td></tr>
  </table>';
  $pdf->writeHTML($coment_html, true, false, false, false, '');
}

// Total
$pdf->Ln(4);
$total_tbl = '
<table cellpadding="6" cellspacing="0" border="1" width="100%">
  <tr>
    <td width="75%" align="right"><b>TOTAL</b></td>
    <td width="25%" align="right"><b>$ '.number_format($costo_ajustado, 2, ',', '.').'</b></td>
  </tr>
</table>';
$pdf->writeHTML($total_tbl, true, false, false, false, '');

// ========== 3) Guardar SIEMPRE en /facturas y actualizar BD ==========
$nombreArchivo = 'Factura_'.$tipo.'_'.str_pad($nro_comprobante, 8, '0', STR_PAD_LEFT).'_Orden_'.$orden_numero.'.pdf';

$dirFact = __DIR__ . DIRECTORY_SEPARATOR . 'facturas';
if (!is_dir($dirFact)) { @mkdir($dirFact, 0777, true); }

$savePath = $dirFact . DIRECTORY_SEPARATOR . $nombreArchivo;
$pdf->Output($savePath, 'F');

$conexion->prepare("UPDATE facturas SET pdf_nombre = ? WHERE factura_id = ?")
         ->execute([$nombreArchivo, $factura_id]);

// ========== 4) Acción: imprimir inline o enviar por email ==========
if ($accion === 'imprimir') {
  // stream inline + (opcional) auto print
  $pdf->IncludeJS('print(true);');
  // Releer y enviar inline
  header('Content-Type: application/pdf');
  header('Content-Disposition: inline; filename="'.$nombreArchivo.'"');
  readfile($savePath);
  exit;
}

// Enviar por mail
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$ok_envio = false;
$err_envio = '';

try {
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Username   = 'wasportaller@gmail.com';
  $mail->Password   = 'kyozoppabnumingu';
  $mail->SMTPSecure = 'tls';
  $mail->Port       = 587;

  $mail->setFrom('wasportaller@gmail.com', 'WA SPORT');
  $mail->addAddress($email_destino ?: $cliente_email, $cliente_nombre ?: 'Cliente');
  $mail->Subject = 'Factura '.$tipo.' Nº '.str_pad($nro_comprobante, 8, '0', STR_PAD_LEFT).' - Orden '.$orden_numero;
  $mail->isHTML(true);
  $mail->Body = '
    <p>Hola <strong>'.htmlspecialchars($cliente_nombre).'</strong>,</p>
    <p>Te enviamos la factura correspondiente a tu orden <strong>#'.htmlspecialchars($orden_numero).'</strong>.</p>
    <p>¡Gracias por confiar en <strong>WA SPORT</strong>!</p>';
  $mail->addAttachment($savePath, $nombreArchivo);
  $mail->send();
  $ok_envio = true;

  $conexion->prepare("UPDATE facturas SET email_enviado = 1 WHERE factura_id = ?")
           ->execute([$factura_id]);

} catch (Exception $e) {
  $err_envio = $mail->ErrorInfo ?? $e->getMessage();
}

// Respuesta simple
?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="UTF-8"><title>Factura enviada</title>
<link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
</head>
<body>
<?php include 'nav_rec.php'; ?>
<section class="generar_fact">
  <?php if ($ok_envio): ?>
    <h2>Factura enviada correctamente</h2>
    <p>Tipo: <strong><?= htmlspecialchars($tipo) ?></strong> — Nº <strong><?= str_pad($nro_comprobante, 8, '0', STR_PAD_LEFT) ?></strong></p>
    <p>Destino: <strong><?= htmlspecialchars($email_destino ?: $cliente_email) ?></strong></p>
    <p class="generar_fac_bot" >
      <a class="generar_fact_btn" href="facturacion.php">Volver a Facturación</a>
      <a class="generar_fact_btn" href="<?= 'download.php?name='.urlencode($nombreArchivo) ?>">Descargar PDF</a>
    </p>
  <?php else: ?>
    <h2>Error al enviar la factura</h2>
    <p><?= htmlspecialchars($err_envio) ?></p>
    <p class="generar_fac_bot" >
      <a class="generar_fact_btn" href="<?= 'download.php?name='.urlencode($nombreArchivo) ?>">Descargar PDF</a>
      <a class="generar_fact_btn" href="facturacion.php">Volver</a>
    </p>
  <?php endif; ?>
</section>   
   <?php 
        include("piedepagina.php");
    ?>
</body>
</html>
