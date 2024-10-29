<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Familiares</title>
    <link rel="stylesheet" href="view/registro_familiar.css"> <!-- Vincular el CSS -->
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4"> <!-- Cambia la ruta a tu video -->
        Tu navegador no soporta la etiqueta de video.
    </video>
    
    <h2>Registro de Familiares</h2>
    <form action="procesar_registro_familiar.php" method="post">
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
        
        <label for="paciente_cedula">Cédula del Paciente:</label><br>
        <input type="text" name="paciente_cedula" required><br> <!-- Cambié el nombre del campo a 'paciente_cedula' -->
        
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" name="contrasena" required><br>
        
        <input type="submit" value="Registrar">
    </form>

    <?php
    // Mostrar mensajes de registro si existen
    if (isset($_GET['mensaje'])) {
        echo '<p>' . htmlspecialchars($_GET['mensaje']) . '</p>';
    }
    ?>
</body>
</html>
