<?php
require '../db/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $usuario = $_POST['usuario'];
    $direccion = $_POST['direccion'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); 
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $carrera = $_POST['carrera'];
    $campus = $_POST['campus'];

    try {
        // Preparar la consulta (same syntax works for SQLite)
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombres, apellidos, usuario, direccion, contrasena, email, telefono, carrera, campus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Ejecutar la consulta
        $stmt->execute([$nombres, $apellidos, $usuario, $direccion, $contrasena, $email, $telefono, $carrera, $campus]);
        
        echo "Registro exitoso. ¡Bienvenid@!";
        
    } catch (PDOException $e) {
        echo "Error al registrarse: " . $e->getMessage();
    }
}
?>