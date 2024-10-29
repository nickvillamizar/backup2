<?php
include 'conexion.php'; // Asegúrate de que la conexión a la base de datos esté configurada correctamente

$mensaje = ''; // Variable para guardar el mensaje

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $pais = $_POST['pais'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $paciente_cedula = $_POST['paciente_cedula']; // Cédula del paciente (campo en el formulario y en la tabla familiares)
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña

    // Verificar si el paciente existe en la tabla pacientes
    $sql_verificar_paciente = "SELECT * FROM pacientes WHERE cedula = ?";
    
    if ($stmt_paciente = $conn->prepare($sql_verificar_paciente)) {
        // Vincular parámetros
        $stmt_paciente->bind_param("s", $paciente_cedula);
        
        // Ejecutar la consulta
        $stmt_paciente->execute();
        $resultado_paciente = $stmt_paciente->get_result();

        // Si el paciente existe, registrar al familiar
        if ($resultado_paciente->num_rows > 0) {
            // Preparar la consulta SQL para insertar al familiar
            $sql_insert_familiar = "INSERT INTO familiares (nombre_completo, cedula, correo, celular, pais, ciudad, direccion, paciente_cedula, contrasena)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt_familiar = $conn->prepare($sql_insert_familiar)) {
                // Vincular parámetros
                $stmt_familiar->bind_param("sssssssss", $nombre_completo, $cedula, $correo, $celular, $pais, $ciudad, $direccion, $paciente_cedula, $contrasena);
                
                // Ejecutar la consulta y verificar si fue exitosa
                if ($stmt_familiar->execute()) {
                    $mensaje = "Registro exitoso.";
                } else {
                    $mensaje = "Error al registrar: " . $stmt_familiar->error;
                }
                $stmt_familiar->close(); // Cerrar la declaración
            } else {
                $mensaje = "Error en la preparación de la consulta: " . $conn->error;
            }
        } else {
            // El paciente no existe, mostrar mensaje de error
            $mensaje = "La cédula del paciente que usted está registrando no se encuentra.";
        }
        $stmt_paciente->close(); // Cerrar la declaración
    } else {
        $mensaje = "Error al verificar el paciente: " . $conn->error;
    }
}

$conn->close(); // Cerrar la conexión a la base de datos

// Redirigir a la página de registro con el mensaje
header("Location: registro_familiar.php?mensaje=" . urlencode($mensaje));
exit;
?>
