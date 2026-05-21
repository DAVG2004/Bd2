<?php
session_start();
include '../scripts/db.php';

// Seguridad: Solo el rol 'administrador' tiene acceso a este script
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado. Se requieren privilegios de administrador.");
}

// 1. Cargar los datos actuales del comprador para rellenar el formulario
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $res = mysqli_query($conexion, "SELECT * FROM comprador WHERE id_comprador = '$id'");
    $d = mysqli_fetch_assoc($res);
    
    if (!$d) {
        die("Comprador no encontrado.");
    }
}

// 2. Procesar la actualización cuando se envía el formulario
if (isset($_POST['update'])) {
    $id_ori = mysqli_real_escape_string($conexion, $_POST['id_original']);
    $id_n   = mysqli_real_escape_string($conexion, $_POST['id_comprador']); 
    $nom    = mysqli_real_escape_string($conexion, $_POST['nombre']); 
    $ape    = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $em     = mysqli_real_escape_string($conexion, $_POST['email']); 
    $tel    = mysqli_real_escape_string($conexion, $_POST['telefono']); 
    $dir    = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $tc     = mysqli_real_escape_string($conexion, $_POST['tcredito']); 
    $cs     = mysqli_real_escape_string($conexion, $_POST['cod_seguridad']); 
    $us     = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $p1     = mysqli_real_escape_string($conexion, $_POST['p1']); 
    $p2     = mysqli_real_escape_string($conexion, $_POST['p2']); 
    $p3     = mysqli_real_escape_string($conexion, $_POST['p3']);

    $sql = "UPDATE comprador SET 
                id_comprador='$id_n', 
                nombre='$nom', 
                apellido='$ape', 
                email='$em', 
                telefono='$tel', 
                direccion='$dir', 
                tcredito='$tc', 
                cod_seguridad='$cs', 
                usuario='$us', 
                p1='$p1', 
                p2='$p2', 
                p3='$p3' 
            WHERE id_comprador='$id_ori'";

    if (mysqli_query($conexion, $sql)) {
        // Redirige de vuelta a la sección de usuarios del panel administrativo
        header("Location: admin_panel.php?view=usuarios&m=update_success");
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
    <title>Panel Admin - Actualizar Comprador</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .form-container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .full-width { grid-column: span 2; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50; font-size: 0.9em; }
        .btn-save { background: #3498db; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; width: 100%; font-size: 1em; margin-top: 20px; }
        .btn-save:hover { background: #2980b9; }
        .header-back { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    </style>
</head>
<body style="background: #f4f7f6; font-family: sans-serif;">

<div class="form-container">
    <div class="header-back">
        <h2 style="margin:0;">Editar Perfil de Comprador</h2>
        <a href="admin_panel.php?view=usuarios" style="text-decoration:none; color:#e74c3c;">✖ Cancelar</a>
    </div>

    <form method="POST">
        <input type="hidden" name="id_original" value="<?php echo $d['id_comprador']; ?>">
        
        <div class="form-grid">
            <div>
                <label>ID Comprador:</label>
                <input type="text" name="id_comprador" value="<?php echo $d['id_comprador']; ?>" required>
            </div>
            <div>
                <label>Usuario de Acceso:</label>
                <input type="text" name="usuario" value="<?php echo $d['usuario']; ?>" required>
            </div>
            <div>
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $d['nombre']; ?>" required>
            </div>
            <div>
                <label>Apellido:</label>
                <input type="text" name="apellido" value="<?php echo $d['apellido']; ?>" required>
            </div>
            <div class="full-width">
                <label>Correo Electrónico:</label>
                <input type="email" name="email" value="<?php echo $d['email']; ?>" required>
            </div>
            <div>
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo $d['telefono']; ?>">
            </div>
            <div>
                <label>Nro. Tarjeta Crédito:</label>
                <input type="text" name="tcredito" value="<?php echo $d['tcredito']; ?>">
            </div>
            <div class="full-width">
                <label>Dirección de Domicilio:</label>
                <input type="text" name="direccion" value="<?php echo $d['direccion']; ?>">
            </div>
        </div>

        <h3 style="border-top: 1px solid #eee; padding-top: 15px; margin-top: 20px; color: #7f8c8d;">Preguntas de Seguridad</h3>
        <div class="form-grid">
            <input type="text" name="p1" value="<?php echo $d['p1']; ?>" placeholder="Pregunta 1">
            <input type="text" name="p2" value="<?php echo $d['p2']; ?>" placeholder="Pregunta 2">
            <input type="text" name="p3" value="<?php echo $d['p3']; ?>" placeholder="Pregunta 3" class="full-width">
        </div>

        <button type="submit" name="update" class="btn-save">Guardar Cambios en Comprador</button>
    </form>
</div>

</body>
</html>