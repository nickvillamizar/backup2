<?php
include 'conexion.php'; // Asegúrate de que este archivo contiene la conexión correcta a la base de datos

// Iniciar la sesión para almacenar el mensaje
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = !empty($_POST['celular']) ? $_POST['celular'] : null; // Si está vacío, se establece como NULL
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $nombre_acompanante = $_POST['nombre_acompanante'];
    $telefono_acompanante = $_POST['telefono_acompanante'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña

    // Preparar la consulta SQL
    $sql = "INSERT INTO pacientes (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, nombre_acompanante, telefono_acompanante, contrasena)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Aquí verificamos si $celular es NULL para poder usar 'sss' en lugar de 'ssss'
        if ($celular === null) {
            $stmt->bind_param("ssssssssss", $nombre_completo, $cedula, $correo, $pais, $ciudad, $direccion, $nombre_acompanante, $telefono_acompanante, $contrasena, $celular);
        } else {
            $stmt->bind_param("sssssssssi", $nombre_completo, $cedula, $correo, $celular, $pais, $ciudad, $direccion, $nombre_acompanante, $telefono_acompanante, $contrasena);
        }
        
        // Ejecutar y verificar si se insertaron los datos
        if ($stmt->execute()) {
            // Mensaje de éxito
            $_SESSION['registro_exitoso'] = "Registro exitoso.";
        } else {
            $error = "Error al registrar: " . $stmt->error;
            $_SESSION['error'] = $error; // Guardar mensaje de error en la sesión
        }
        $stmt->close();
    } else {
        $error = "Error en la preparación de la consulta: " . $conn->error;
        $_SESSION['error'] = $error; // Guardar mensaje de error en la sesión
    }

    $conn->close();
    
    // Redirigir de nuevo al formulario
    header("Location: registro_paciente.php");
    exit;
}
?>
