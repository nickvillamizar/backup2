<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_completo'])) {
    header("Location: login.php"); // Redirigir a login si no está autenticado
    exit();
}

$nombre_completo = $_SESSION['nombre_completo'];
$paciente_id = null;
$diarios = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paciente_id'])) {
    $paciente_id = $_POST['paciente_id'];
    $stmt = $conn->prepare("SELECT fecha, contenido FROM diarios WHERE paciente_id = ? ORDER BY fecha DESC");
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $diarios[] = $row;
    }
    $stmt->close();
}

$pacientes = [];
$result = $conn->query("SELECT id, nombre_completo FROM pacientes");
while ($row = $result->fetch_assoc()) {
    $pacientes[] = $row;
}

$disponibilidades = [];
$stmt = $conn->prepare("SELECT fecha, hora_inicio, hora_fin, estado FROM disponibilidad_profesionales WHERE id_profesional = ? ORDER BY fecha ASC");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $disponibilidades[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Profesional</title>
    <link rel="stylesheet" type="text/css" href="view/panel_paciente.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="videos/pasto.mp4" type="video/mp4">
        Tu navegador no soporta reproducción de video.
    </video>

    <nav class="navbar">
        <ul>
            <li><a href="#seleccion-paciente">Seleccionar Paciente</a></li>
            <li><a href="#diarios">Entradas del Diario</a></li>
            <li><a href="#disponibilidad">Mi Disponibilidad</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="contenido">
        <h1>Bienvenido, <?php echo $nombre_completo; ?></h1>

        <section class="descripcion">
            <p>En Apuesta por ti, los profesionales pueden ayudar a los pacientes brindando acompañamiento y gestionando su disponibilidad.</p>
        </section>

        <h2 id="seleccion-paciente">Seleccionar Paciente</h2>
        <form method="POST" action="">
            <label for="paciente_id">Selecciona un paciente:</label>
            <select name="paciente_id" id="paciente_id" required>
                <?php foreach ($pacientes as $paciente): ?>
                    <option value="<?php echo $paciente['id']; ?>" <?php if ($paciente_id == $paciente['id']) echo 'selected'; ?>>
                        <?php echo $paciente['nombre_completo']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Ver Diario</button>
        </form>

        <h2 id="diarios">Entradas del Diario</h2>
        <div class="diario-entradas">
            <?php if (!empty($diarios)): ?>
                <ul>
                    <?php foreach ($diarios as $entrada): ?>
                        <li><strong><?php echo $entrada['fecha']; ?>:</strong> <?php echo $entrada['contenido']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No hay entradas en el diario para este paciente.</p>
            <?php endif; ?>
        </div>

        <h2 id="disponibilidad">Mi Disponibilidad</h2>
        <div class="disponibilidad">
            <?php if (!empty($disponibilidades)): ?>
                <ul>
                    <?php foreach ($disponibilidades as $disp): ?>
                        <li><strong>Fecha:</strong> <?php echo $disp['fecha']; ?>,
                            <strong>Hora:</strong> <?php echo $disp['hora_inicio'] . ' - ' . $disp['hora_fin']; ?>,
                            <strong>Estado:</strong> <?php echo $disp['estado']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tienes disponibilidad registrada.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="contacto">
            <h3>Información de Contacto</h3>
            <p>Email: apuestaportiportalweb@hotmail.com</p>
            <p>Teléfono: 3154526359</p>
            <p>Ceo: Nicolas Ramirez Villamizar</p>
        </div>
        <div class="ubicacion">
            <h3>"Apuesta por ti" es un portal web de ayuda 100% digital.</h3>
            <p>Manizales Caldas.</p>
        </div>
        <div class="mapa">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.0518351233564!2d-75.51222568518498!3d5.069168055883164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e49307eac768fd9%3A0x7d51a5a59f9b5d78!2sManizales%2C%20Caldas!5e0!3m2!1ses!2sco!4v1604583278403!5m2!1ses!2sco" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <div class="creditos">
            <p>Creado por Baloper Software</p>
        </div>
    </footer>
</body>
</html>
