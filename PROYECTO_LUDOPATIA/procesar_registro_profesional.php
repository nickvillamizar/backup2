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
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña

    // Preparar la consulta SQL
    $sql = "INSERT INTO profesionales (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, tarjeta_profesional, contrasena)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        // Aquí verificamos si $celular es NULL para poder usar 'sss' en lugar de 'ssss'
        if ($celular === null) {
            $stmt->bind_param("sssssssss", $nombre_completo, $cedula, $correo, $pais, $ciudad, $direccion, $numero_tarjeta, $contrasena, $celular);
        } else {
            $stmt->bind_param("ssssssssi", $nombre_completo, $cedula, $correo, $celular, $pais, $ciudad, $direccion, $numero_tarjeta, $contrasena);
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
    header("Location: registro_profesional.php");
    exit;
}
?>
