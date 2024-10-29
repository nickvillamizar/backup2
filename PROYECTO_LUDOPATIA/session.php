<?php
// session.php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_completo']) || !isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirigir a login si no está autenticado
    exit();
}

// Obtener los datos del usuario de la sesión
$nombre_completo = $_SESSION['nombre_completo'];
$paciente_id = $_SESSION['id'];
?>
