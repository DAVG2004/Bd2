<?php
session_start();
include '../scripts/db.php';

// Seguridad: Solo Administradores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado.");
}

// 1. Obtener datos de la obra
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $res = mysqli_query($conexion, "SELECT * FROM obra WHERE id_obra = '$id'");
    $obra = mysqli_fetch_assoc($res);
    
    if (!$obra) die("Obra no encontrada.");
}

// 2. Procesar Actualización
if (isset($_POST['update_obra'])) {
    $id_ori = $_POST['id_original'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $status = mysqli_real_escape_string($conexion, $_POST['status']);
    $desc   = mysqli_real_escape_string($conexion, $_POST['descripcion']);

    $sql = "UPDATE obra SET 
                nombre='$nombre', 
                precio='$precio', 
                status='$status', 
                descripcion='$desc' 
            WHERE id_obra='$id_ori'";

    if (mysqli_query($conexion, $sql)) {
        header("Location: admin_panel.php?view=usuarios&m=obra_ok");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Editar Obra</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .form-container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #34495e; }
        .btn-save { background: #e67e22; color: white; border: none; padding: 15px; border-radius: 6px; cursor: pointer; width: 100%; font-size: 1.1em; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body style="background: #f0f2f5; font-family: 'Segoe UI', sans-serif;">

<div class="form-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin:0; color: #e67e22;">🖼️ Editar Obra de Arte</h2>
        <a href="admin_panel.php?view=usuarios" style="text-decoration:none; color:#7f8c8d;">Volver</a>
    </div>

    <form method="POST">
        <input type="hidden" name="id_original" value="<?php echo $obra['id_obra']; ?>">
        
        <div class="form-grid">
            <div style="grid-column: span 2;">
                <label>Nombre de la Obra:</label>
                <input type="text" name="nombre" value="<?php echo $obra['nombre']; ?>" required>
            </div>

            <div>
                <label>Precio ($):</label>
                <input type="number" step="0.01" name="precio" value="<?php echo $obra['precio']; ?>" required>
            </div>

            <div>
                <label>Estado actual:</label>
                <select name="status">
                    <option value="disponible" <?php echo ($obra['status'] == 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                    <option value="vendida" <?php echo ($obra['status'] == 'vendida') ? 'selected' : ''; ?>>Vendida</option>
                </select>
            </div>

            <div style="grid-column: span 2;">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="4"><?php echo $obra['descripcion']; ?></textarea>
            </div>
        </div>

        <button type="submit" name="update_obra" class="btn-save">Guardar Cambios en Inventario</button>
    </form>
</div>
</body>
</html>