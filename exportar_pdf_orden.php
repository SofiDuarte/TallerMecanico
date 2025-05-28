<?php
require_once('tcpdf/tcpdf.php');

$ordenNum = $_GET['orden'] ?? '';

if (!$ordenNum) {
    die('Orden no especificada.');
}

$pdo = new PDO("mysql:host=localhost;dbname=bdd_taller_mecanico_mysql;port=3307", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("
    SELECT o.orden_numero, o.orden_fecha, o.vehiculo_patente,
           v.vehiculo_marca, v.vehiculo_modelo, v.vehiculo_anio,
           s.servicio_nombre, ot.complejidad, ot.orden_kilometros,
           ot.orden_comentario, ot.orden_estado
    FROM ordenes o
    JOIN vehiculos v ON o.vehiculo_patente = v.vehiculo_patente
    JOIN orden_trabajo ot ON o.orden_numero = ot.orden_numero
    JOIN servicios s ON ot.servicio_codigo = s.servicio_codigo
    WHERE o.orden_numero = :orden
");
$stmt->execute(['orden' => $ordenNum]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$html = "
<h1>Orden Nº {$data['orden_numero']}</h1>
<p><strong>Fecha:</strong> {$data['orden_fecha']}</p>
<p><strong>Vehículo:</strong> {$data['vehiculo_patente']} - {$data['vehiculo_marca']} - {$data['vehiculo_modelo']} ({$data['vehiculo_anio']})</p>
<p><strong>Servicio:</strong> {$data['servicio_nombre']}</p>
<p><strong>Complejidad:</strong> {$data['complejidad']}</p>
<p><strong>Kilometraje:</strong> {$data['orden_kilometros']}</p>
<p><strong>Comentario:</strong> {$data['orden_comentario']}</p>
<p><strong>Estado:</strong> " . ($data['orden_estado'] ? 'Finalizada' : 'Pendiente') . "</p>
";
$pdf->writeHTML($html);
$pdf->Output("orden_{$ordenNum}.pdf", 'I');