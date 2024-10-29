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

// Conectar a la base de datos
include 'conexion.php'; 

// Asignar el ID del paciente de la sesión
$paciente_id = $_SESSION['id'];

// Verificar que se hayan enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profesional_id = $_POST['profesional'];

    // Obtener la disponibilidad del profesional
    $stmt = $conn->prepare("SELECT d.id_disponibilidad, d.fecha, d.hora_inicio, d.hora_fin FROM disponibilidad_profesionales d 
                            WHERE d.id_profesional = ? AND d.estado = 'disponible'");
    $stmt->bind_param("i", $profesional_id);
    
    // Ejecutar y verificar la consulta
    if (!$stmt->execute()) {
        $_SESSION['error'] = "Error al ejecutar la consulta de disponibilidad: " . $stmt->error;
        header("Location: panel_paciente.php");
        exit();
    }
    
    $result = $stmt->get_result();

    // Verificar si hay disponibilidad
    if ($row = $result->fetch_assoc()) {
        $id_disponibilidad = $row['id_disponibilidad']; // Obtén el ID de disponibilidad
        $fecha = $row['fecha'];
        $hora_inicio = $row['hora_inicio'];
        $hora_fin = $row['hora_fin'];
        $estado = 'pendiente'; // Establecer estado por defecto

        // Insertar la cita en la base de datos
        $stmt = $conn->prepare("INSERT INTO citas (id_paciente, id_profesional, id_disponibilidad, fecha, hora_inicio, hora_fin, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiissss", $paciente_id, $profesional_id, $id_disponibilidad, $fecha, $hora_inicio, $hora_fin, $estado);
        
        // Ejecutar la inserción
        if ($stmt->execute()) {
            // Verificar si se ha insertado una fila
            if ($stmt->affected_rows > 0) {
                // Actualizar el estado de disponibilidad a 'no disponible'
                $stmt = $conn->prepare("UPDATE disponibilidad_profesionales SET estado = 'no disponible' WHERE id_profesional = ? AND id_disponibilidad = ?");
                $stmt->bind_param("ii", $profesional_id, $id_disponibilidad);
                
                // Ejecutar la actualización
                if (!$stmt->execute()) {
                    $_SESSION['error'] = "Error al actualizar la disponibilidad: " . $stmt->error; // Error al actualizar la disponibilidad
                } else {
                    $_SESSION['mensaje'] = "Cita reservada con éxito.";
                }
            } else {
                $_SESSION['error'] = "No se pudo reservar la cita. Intenta nuevamente."; // Mensaje si no se afectó ninguna fila
            }
        } else {
            $_SESSION['error'] = "Error al reservar la cita: " . $stmt->error; // Muestra el error de la consulta de inserción
        }
    } else {
        $_SESSION['error'] = "El profesional no está disponible en la fecha y hora seleccionadas.";
    }
}

// Redirigir al panel del paciente
header("Location: panel_paciente.php");
exit();
?>
