<?php
session_start();
include '../scripts/db.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'artista') {
    die("Acceso denegado.");
}

$id_obra = intval($_GET['id']);

// Traer datos actuales de la obra
$query = "SELECT * FROM obra WHERE id_obra = $id_obra";
$resultado = mysqli_query($conexion, $query);
$obra = mysqli_fetch_assoc($resultado);

if (!$obra) {
    die("Obra no encontrada.");
}

// Procesar el formulario de edición
if (isset($_POST['actualizar'])) {
    $nuevo_nombre = mysqli_real_escape_string($conexion, $_POST['nombre_obra']);
    $nuevo_precio = intval($_POST['precio']);
    $nuevo_genero = intval($_POST['id_genero']);

    $sql = "UPDATE obra SET nombre='$nuevo_nombre', precio='$nuevo_precio', id_genero='$nuevo_genero' 
            WHERE id_obra=$id_obra";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Obra actualizada correctamente'); window.location='index.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>

<head>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <a href="index.php">Cerrar Sesión</a>

    <form action="" method="POST">
        <h2>Editar obra</h2>

        <label>Nombre de la obra:</label>
        <input type="text" name="nombre_obra" value="<?php echo htmlspecialchars($obra['nombre']); ?>" required>

        <label>Precio de la obra:</label>
        <input type="number" name="precio" value="<?php echo $obra['precio']; ?>" required>

        <label>Género de la obra:</label>
        <select name="id_genero" required>
            <option value="1" <?php echo $obra['id_genero'] == 1 ? 'selected' : ''; ?>>Pintura</option>
            <option value="2" <?php echo $obra['id_genero'] == 2 ? 'selected' : ''; ?>>Escultura</option>
            <option value="3" <?php echo $obra['id_genero'] == 3 ? 'selected' : ''; ?>>Fotografía</option>
            <option value="4" <?php echo $obra['id_genero'] == 4 ? 'selected' : ''; ?>>Cerámica</option>
            <option value="5" <?php echo $obra['id_genero'] == 5 ? 'selected' : ''; ?>>Orfebrería</option>
        </select>

        <button type="submit" name="actualizar">Guardar cambios</button>
    </form>
</body>