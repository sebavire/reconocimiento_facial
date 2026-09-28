<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conexion.php';

$cedula = $_GET['cedula'] ?? '';

if (empty($cedula)) {
    echo json_encode(["status" => "error", "mensaje" => "Cédula no proporcionada"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, cedula, nombre, apellido, fecha_registro FROM personas WHERE cedula = :cedula");
$stmt->execute([':cedula' => $cedula]);
$persona = $stmt->fetch();

if ($persona) {
    echo json_encode([
        "status" => "success",
        "data"   => $persona
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "mensaje" => "Persona no encontrada"
    ]);
}
?>