<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_completo'])) {
    header("Location: login.php"); // Redirigir a login si no está autenticado
    exit();
}

$mensaje = ''; // Inicializar mensaje

// Guardar la disponibilidad si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fecha']) && isset($_POST['hora_inicio']) && isset($_POST['hora_fin'])) {
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];

    // Insertar la disponibilidad en la base de datos
    $stmt = $conn->prepare("INSERT INTO disponibilidad_profesionales (id_profesional, fecha, hora_inicio, hora_fin, estado) VALUES (?, ?, ?, ?, 'disponible')");
    
    // Verifica si la consulta se preparó correctamente
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("isss", $_SESSION['id'], $fecha, $hora_inicio, $hora_fin);

    if ($stmt->execute()) {
        // Mensaje de confirmación
        $mensaje = "Disponibilidad guardada correctamente Doctor@.";
    } else {
        $mensaje = "Error al guardar la disponibilidad: " . $stmt->error;
    }

    $stmt->close();
}

// Redirigir de vuelta al panel del profesional con el mensaje
header("Location: panel_profesional.php?mensaje=" . urlencode($mensaje));
exit();
?>
