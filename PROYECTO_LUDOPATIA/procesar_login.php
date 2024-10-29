<?php
session_start(); // Iniciar la sesión
include 'conexion.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_usuario = $_POST['tipo_usuario'];
    $cedula = $_POST['cedula'];
    $contrasena = $_POST['contrasena'];

    // Verificar en la tabla correspondiente según el tipo de usuario
    if ($tipo_usuario == 'paciente') {
        $stmt = $conn->prepare("SELECT * FROM pacientes WHERE cedula = ?");
    } elseif ($tipo_usuario == 'familiar') {
        $stmt = $conn->prepare("SELECT * FROM familiares WHERE cedula = ?");
    } elseif ($tipo_usuario == 'profesional') {
        $stmt = $conn->prepare("SELECT * FROM profesionales WHERE cedula = ?");
    }

    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contrasena, $row['contrasena'])) {
            // Guardar el nombre del usuario en la sesión
            $_SESSION['nombre_completo'] = $row['nombre_completo'];
            $_SESSION['id'] = $row['id']; // Guardar también el ID del usuario

            // Redirigir según el tipo de usuario
            if ($tipo_usuario == 'paciente') {
                header("Location: panel_paciente.php");
            } elseif ($tipo_usuario == 'familiar') {
                header("Location: panel_familiar.php");
            } elseif ($tipo_usuario == 'profesional') {
                header("Location: panel_profesional.php");
            }
            exit();
        } else {
            // Credenciales incorrectas
            $error = "Cédula o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
        $error = "Cédula o contraseña incorrectos.";
    }
}
?>
