<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php'; 
$paciente_id = $_SESSION['id'];
$profesionales = [];

// Obtener disponibilidad de profesionales
$stmt = $conn->query("SELECT p.id, p.nombre_completo, d.fecha, d.hora_inicio, d.hora_fin 
                      FROM profesionales p
                      JOIN disponibilidad_profesionales d ON p.id = d.id_profesional
                      WHERE d.estado = 'disponible'");
while ($row = $stmt->fetch_assoc()) {
    $profesionales[] = $row;
}

// Mostrar entradas de diario
$stmt = $conn->prepare("SELECT fecha, contenido FROM diarios WHERE paciente_id = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$result = $stmt->get_result();
$entradas_diario = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Obtener citas reservadas
$citas_reservadas = [];
$stmt = $conn->prepare("SELECT c.fecha, c.hora_inicio, c.hora_fin, p.nombre_completo FROM citas c
                        JOIN profesionales p ON c.id_profesional = p.id
                        WHERE c.id_paciente = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$result = $stmt->get_result();
$citas_reservadas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Paciente</title>
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
            <li><a href="#diario">Mis Entradas</a></li>
            <li><a href="#citas">Mis Citas</a></li>
            <li><a href="#agregar-diario">Agregar Diario</a></li>
            <li><a href="#reservar-cita">Reservar Cita</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="contenido">
        <h1>Bienvenido, <?php echo $_SESSION['nombre_completo']; ?></h1>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje-success"><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mensaje-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <section class="descripcion">
            <p>En Apuesta por ti, ofrecemos un espacio seguro y recursos para ayudar a quienes luchan contra la ludopatía.</p>
        </section>

        <h2 id="diario">Mis entradas del diario</h2>
        <div class="diario-entradas">
            <?php if (!empty($entradas_diario)): ?>
                <ul>
                    <?php foreach ($entradas_diario as $entrada): ?>
                        <li><strong><?php echo $entrada['fecha']; ?>:</strong> <?php echo $entrada['contenido']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tienes entradas en tu diario aún.</p>
            <?php endif; ?>
        </div>

        <h2 id="citas">Mis citas reservadas</h2>
        <div class="citas-reservadas">
            <?php if (!empty($citas_reservadas)): ?>
                <ul>
                    <?php foreach ($citas_reservadas as $cita): ?>
                        <li>
                            <strong>Fecha:</strong> <?php echo $cita['fecha']; ?>,
                            <strong>Hora:</strong> <?php echo $cita['hora_inicio'] . ' - ' . $cita['hora_fin']; ?>,
                            <strong>Profesional:</strong> <?php echo $cita['nombre_completo']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tienes citas reservadas aún.</p>
            <?php endif; ?>
        </div>

        <h2 id="agregar-diario">Agregar nueva entrada al diario</h2>
        <form action="guardar_diario.php" method="POST">
            <textarea name="contenido" rows="5" placeholder="Escribe tu entrada aquí..." required></textarea><br>
            <input type="submit" value="Guardar entrada">
        </form>

        <h2 id="reservar-cita">Reservar una cita</h2>
        <form action="reservar_cita.php" method="POST">
            <label for="profesional">Selecciona un profesional:</label>
            <select name="profesional" id="profesional" required>
                <?php foreach ($profesionales as $profesional): ?>
                    <option value="<?php echo $profesional['id']; ?>">
                        <?php echo $profesional['nombre_completo'] . ' - ' . $profesional['fecha'] . ' (' . $profesional['hora_inicio'] . ' - ' . $profesional['hora_fin'] . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            <input type="submit" value="Reservar cita">
        </form>
    </div>

    <footer>
    <div class="contacto">
        <h3>Información de Contacto</h3>
        <p>Email: apuestaportiportalweb@hotmail.com</p>
        <p>Teléfono: 3154526359</p>
        <p>Ceo: Nicolas Ramirez Villamizar</p>
    </div>
    <div class="ubicacion">
        <h3>Apuesta por ti" es un portal web de ayuda 100% digital. </h3>
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

