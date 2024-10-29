<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="view/login.css">
</head>
<body>
    <video id="background-video" autoplay loop muted>
        <source src="videos/pasto.mp4" type="video/mp4">
        Tu navegador no soporta video.
    </video>

    <h2>Iniciar Sesión</h2>
    <form action="procesar_login.php" method="post"> <!-- Aquí se cambió la acción -->
        <label for="tipo_usuario">Seleccione tipo de usuario:</label><br>
        <select name="tipo_usuario" required>
            <option value="">Seleccione...</option>
            <option value="paciente">Paciente</option>
            <option value="familiar">Familiar</option>
            <option value="profesional">Profesional</option>
        </select><br><br>
        
        <label for="cedula">Cédula:</label><br>
        <input type="text" name="cedula" required><br><br>
        
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>
        
        <input type="submit" value="Iniciar Sesión">
        <button type="button" onclick="location.href='registro.php'">Crear Cuenta</button>
    </form>
    
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
</body>
</html>
