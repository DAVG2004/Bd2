<?php
session_start();
include '../scripts/db.php';

// Seguridad: Solo el rol 'administrador' tiene acceso
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado. Se requieren privilegios de administrador.");
}

// 1. Cargar los datos actuales del artista para rellenar el formulario
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $res = mysqli_query($conexion, "SELECT * FROM artista WHERE id_artista = '$id'");
    $d = mysqli_fetch_assoc($res);
    
    if (!$d) {
        die("Artista no encontrado.");
    }
}

// 2. Procesar la actualización cuando se envía el formulario
if (isset($_POST['update'])) {
    $id_ori = mysqli_real_escape_string($conexion, $_POST['id_original']);
    $nom    = mysqli_real_escape_string($conexion, $_POST['nombre']); 
    $ape    = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $em     = mysqli_real_escape_string($conexion, $_POST['email']); 
    $tel    = mysqli_real_escape_string($conexion, $_POST['telefono']); 
    $nac    = mysqli_real_escape_string($conexion, $_POST['nacionalidad']);
    $tarifa = mysqli_real_escape_string($conexion, $_POST['tarifa_museo']); 
    $us     = mysqli_real_escape_string($conexion, $_POST['usuario']);

    $sql = "UPDATE artista SET 
                nombre='$nom', 
                apellido='$ape', 
                email='$em', 
                telefono='$tel', 
                nacionalidad='$nac', 
                tarifa_museo='$tarifa', 
                usuario='$us' 
            WHERE id_artista='$id_ori'";

    if (mysqli_query($conexion, $sql)) {
        // Redirige a la sección de usuarios del panel administrativo
        header("Location: admin_panel.php?view=usuarios&m=artista_updated");
        exit();
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Actualizar Artista</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .form-container { max-width: 700px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .full-width { grid-column: span 2; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50; font-size: 0.9em; }
        .btn-save { background: #27ae60; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; width: 100%; font-size: 1em; margin-top: 20px; font-weight: bold; }
        .btn-save:hover { background: #219150; }
        .header-back { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f4f4f4; padding-bottom: 10px; }
        .highlight-box { background: #f9f9f9; padding: 15px; border-radius: 5px; border-left: 5px solid #27ae60; margin-top: 10px; }
    </style>
</head>
<body style="background: #f4f7f6; font-family: sans-serif;">

<div class="form-container">
    <div class="header-back">
        <h2 style="margin:0; color: #2c3e50;">🎨 Editar Perfil de Artista</h2>
        <a href="admin_panel.php?view=usuarios" style="text-decoration:none; color:#e74c3c; font-weight: bold;">✖ Cancelar</a>
    </div>

    <form method="POST">
        <input type="hidden" name="id_original" value="<?php echo $d['id_artista']; ?>">
        
        <div class="form-grid">
            <div class="full-width">
                <label>Nombre(s):</label>
                <input type="text" name="nombre" value="<?php echo $d['nombre']; ?>" required>
            </div>
            
            <div class="full-width">
                <label>Apellidos:</label>
                <input type="text" name="apellido" value="<?php echo $d['apellido']; ?>" required>
            </div>

            <div>
                <label>Nombre de Usuario:</label>
                <input type="text" name="usuario" value="<?php echo $d['usuario']; ?>" required>
            </div>

            <div>
                <label>Nacionalidad:</label>
                <input type="text" name="nacionalidad" value="<?php echo $d['nacionalidad']; ?>">
            </div>

            <div class="full-width">
                <label>Correo Electrónico:</label>
                <input type="email" name="email" value="<?php echo $d['email']; ?>" required>
            </div>

            <div>
                <label>Teléfono de Contacto:</label>
                <input type="text" name="telefono" value="<?php echo $d['telefono']; ?>">
            </div>

            <div class="highlight-box full-width">
                <label style="color: #27ae60;">Tarifa del Museo (Comisión Decimal):</label>
                <input type="number" step="0.01" min="0" max="1" name="tarifa_museo" value="<?php echo $d['tarifa_museo']; ?>" required>
                <small style="color: #666;">Ejemplo: 0.15 para un 15% de comisión sobre la venta.</small>
            </div>
        </div>

        <button type="submit" name="update" class="btn-save">Actualizar Información del Artista</button>
    </form>
</div>

</body>
</html>