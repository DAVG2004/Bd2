<?php
session_start();
include '../scripts/db.php';

// 1. Verificar si el usuario es artista
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'artista') {
    header("Location: index.php");
    exit;
}

// 2. Obtener los datos actuales de la obra
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM obra WHERE id_obra = $id";
    $res = mysqli_query($conexion, $query);
    $obra = mysqli_fetch_assoc($res);

    if (!$obra) {
        die("La obra no existe.");
    }
}

// 3. Procesar la actualización cuando se presiona "Guardar Cambios"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_actualizar = (int)$_POST['id_obra'];
    $nuevo_precio = (float)$_POST['precio'];
    $nuevo_status = mysqli_real_escape_string($conexion, $_POST['status']);
    
    // Aquí puedes añadir más campos si quieres editar el nombre o género
    $update_query = "UPDATE obra SET precio = $nuevo_precio, status = '$nuevo_status' WHERE id_obra = $id_actualizar";

    if (mysqli_query($conexion, $update_query)) {
        echo "<script>alert('Obra actualizada con éxito'); window.location.href='index.php';</script>";
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Obra - Galería</title>
    <style>
        body { font-family: sans-serif; background-color: #1a1a1a; color: white; padding: 40px; }
        .edit-container { max-width: 500px; margin: auto; background: #2a2a2a; padding: 30px; border-radius: 15px; border: 1px solid #00d4ff; }
        h2 { color: #00d4ff; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #aaa; }
        input, select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #444; background: #111; color: white; box-sizing: border-box; }
        .preview-img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 20px; }
        .btn-save { background: #00ff88; color: black; border: none; padding: 12px; width: 100%; font-weight: bold; border-radius: 5px; cursor: pointer; }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; color: #ff4d4d; text-decoration: none; font-size: 0.9em; }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>✏️ Editar Obra</h2>
    
    <img src="../resources/<?php echo $obra['nombre']; ?>" class="preview-img">
    
    <form method="POST">
        <input type="hidden" name="id_obra" value="<?php echo $obra['id_obra']; ?>">

        <div class="form-group">
            <label>Título (No editable):</label>
            <input type="text" value="<?php echo $obra['nombre']; ?>" disabled>
        </div>

        <div class="form-group">
            <label>Precio ($):</label>
            <input type="number" name="precio" step="0.01" value="<?php echo $obra['precio']; ?>" required>
        </div>

        <div class="form-group">
            <label>Estado de la obra:</label>
            <select name="status">
                <option value="disponible" <?php if($obra['status'] == 'disponible') echo 'selected'; ?>>Disponible</option>
                <option value="vendida" <?php if($obra['status'] == 'vendida') echo 'selected'; ?>>Vendida</option>
            </select>
        </div>

        <button type="submit" class="btn-save">💾 Guardar Cambios</button>
        <a href="index.php" class="btn-cancel">Cancelar y volver</a>
    </form>
</div>

</body>
</html>