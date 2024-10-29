<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Profesionales</title>
    <link rel="stylesheet" href="view/registro_profesionales.css"> <!-- Asegúrate de que el archivo de estilos esté en la ubicación correcta -->
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4"> <!-- Cambia esto a la ruta de tu video -->
        Tu navegador no soporta video.
    </video>

    <h2>Registro de Profesionales</h2>
    
    <?php
    session_start(); // Iniciar la sesión para acceder a los mensajes
    // Mostrar mensaje de registro exitoso si está disponible
    if (isset($_SESSION['registro_exitoso'])) {
        echo "<p>" . $_SESSION['registro_exitoso'] . "</p>";
        unset($_SESSION['registro_exitoso']); // Limpiar el mensaje después de mostrarlo
    }

    // Mostrar mensaje de error si existe
    if (isset($_SESSION['error'])) {
        echo "<p>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); // Limpiar el mensaje después de mostrarlo
    }
    ?>

    <form action="procesar_registro_profesional.php" method="post"> <!-- Cambiar a procesar_registro_profesional.php -->
        <label for="nombre_completo">Nombre Completo:</label><br>
        <input type="text" name="nombre_completo" required><br>
        
        <label for="cedula">Cédula:</label><br>
        <input type="text" name="cedula" required><br>
        
        <label for="correo">Correo:</label><br>
        <input type="email" name="correo" required><br>
        
        <label for="celular">Celular:</label><br>
        <input type="text" name="celular"><br>
        
        <label for="pais">País:</label><br>
        <input type="text" name="pais" required><br>
        
        <label for="ciudad">Ciudad:</label><br>
        <input type="text" name="ciudad" required><br>
        
        <label for="direccion">Dirección:</label><br>
        <input type="text" name="direccion" required><br>
        
        <label for="numero_tarjeta">Número de Tarjeta Profesional:</label><br>
        <input type="text" name="numero_tarjeta" required><br>
        
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" required><br>
        
        <input type="submit" value="Registrar">
    </form>
</body>
</html>
