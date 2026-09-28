<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula   = trim($_POST['cedula'] ?? '');
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $foto     = $_POST['foto'] ?? '';

    if (empty($cedula) || empty($nombre) || empty($apellido) || empty($foto)) {
        echo json_encode(["status" => "error", "mensaje" => "Todos los campos y la foto son obligatorios."]);
        exit;
    }

    try {
        // 1. Guardar o registrar en MySQL
        $sql = "INSERT INTO personas (cedula, nombre, apellido) VALUES (:cedula, :nombre, :apellido)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cedula'   => $cedula,
            ':nombre'   => $nombre,
            ':apellido' => $apellido
        ]);

        // 2. Crear la carpeta para la cédula
        $dir_destino = __DIR__ . "/dataset_fotos/" . $cedula;
        if (!file_exists($dir_destino)) {
            mkdir($dir_destino, 0777, true);
        }

        // 3. Procesar y guardar la imagen Base64
        // Formato recibido: "data:image/jpeg;base64,/9j/4AAQSkZJRg..."
        list($type, $foto_data) = explode(';', $foto);
        list(, $foto_data)      = explode(',', $foto_data);
        $foto_decodificada      = base64_decode($foto_data);

        // Guardar archivo foto1.jpg
        $nombre_archivo = $dir_destino . "/foto1.jpg";
        file_put_contents($nombre_archivo, $foto_decodificada);

        echo json_encode([
            "status"  => "success",
            "mensaje" => "✅ Alumno registrado correctamente y foto guardada en dataset_fotos/$cedula/foto1.jpg"
        ]);

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(["status" => "error", "mensaje" => "⚠️ La cédula $cedula ya está registrada."]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "Error BD: " . $e->getMessage()]);
        }
    }
}
?>