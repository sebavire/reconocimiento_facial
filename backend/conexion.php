<?php
$host     = "localhost";
$db       = "reconocimiento_facial";
$user     = "root"; // Cambia esto si tienes usuario configurado
$pass     = "";     // Cambia esto si tienes contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>