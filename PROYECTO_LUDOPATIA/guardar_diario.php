<?php
// Activar la visualización de errores para depuración
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Incluir conexión a la base de datos
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contenido'])) {
    $paciente_id = $_SESSION['id'];
    $contenido = $_POST['contenido']; // Aquí se obtiene el contenido del textarea
    $fecha = date('Y-m-d'); // Fecha actual

    // Preparar la declaración SQL
    $stmt = $conn->prepare("INSERT INTO diarios (paciente_id, fecha, contenido) VALUES (?, ?, ?)");
    
    if ($stmt) {
        // Vincular los parámetros
        $stmt->bind_param("iss", $paciente_id, $fecha, $contenido);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Entrada guardada con éxito."; // Mensaje de éxito
        } else {
            $_SESSION['error'] = "Error al guardar la entrada del diario: " . $stmt->error; // Mensaje de error
        }
        $stmt->close(); // Cerrar la declaración
    } else {
        $_SESSION['error'] = "Error en la preparación de la declaración: " . $conn->error; // Mensaje de error
    }
}

// Redirigir a la página del panel del paciente
header("Location: panel_paciente.php");
exit();
?>
