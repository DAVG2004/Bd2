<?php
session_start();
include '../scripts/db.php';

// Seguridad: Solo el rol 'administrador' tiene acceso
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    die("Acceso denegado. Se requieren privilegios de administrador.");
}

// 1. Obtener datos actuales del empleado para rellenar el formulario
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $query = "SELECT * FROM empleado WHERE id_empleado = '$id'";
    $resultado = mysqli_query($conexion, $query);
    $empleado = mysqli_fetch_assoc($resultado);
    
    if (!$empleado) die("Empleado no encontrado.");
}

// 2. Procesar la actualización de datos
if (isset($_POST['btn_actualizar_emp'])) {
    $id_original = $_POST['id_original'];
    $id_nuevo = mysqli_real_escape_string($conexion, $_POST['id_empleado']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $salario = mysqli_real_escape_string($conexion, $_POST['salario']);
    $puesto = mysqli_real_escape_string($conexion, $_POST['puesto']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);

    $sql = "UPDATE empleado SET 
            id_empleado = '$id_nuevo', 
            nombre = '$nombre', 
            apellido = '$apellido', 
            telefono = '$telefono', 
            salario = '$salario', 
            puesto = '$puesto', 
            usuario = '$usuario' 
            WHERE id_empleado = '$id_original'";

    if (mysqli_query($conexion, $sql)) {
        // Redirección al panel de gestión de usuarios tras el éxito
        header("Location: admin_panel.php?view=usuarios&msg=emp_actualizado");
        exit();
    } else {
        $error = "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Actualizar Empleado</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .form-container { max-width: 700px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .full-width { grid-column: span 2; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #2c3e50; font-size: 0.9em; }
        .btn-save { background: #2c3e50; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; width: 100%; font-size: 1em; margin-top: 20px; font-weight: bold; }
        .btn-save:hover { background: #1a252f; }
        .header-back { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #f4f4f4; padding-bottom: 10px; }
        .error { color: #e74c3c; background: #fdedec; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body style="background: #f4f7f6; font-family: sans-serif;">

<div class="form-container">
    <div class="header-back">
        <h2 style="margin:0; color: #2c3e50;">👔 Editar Personal</h2>
        <a href="admin_panel.php?view=usuarios" style="text-decoration:none; color:#e74c3c; font-weight: bold;">✖ Cancelar</a>
    </div>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="hidden" name="id_original" value="<?php echo $empleado['id_empleado']; ?>">
        
        <div class="form-grid">
            <div>
                <label>ID / Identificación:</label>
                <input type="text" name="id_empleado" value="<?php echo $empleado['id_empleado']; ?>" required>
            </div>
            <div>
                <label>Nombre de Usuario:</label>
                <input type="text" name="usuario" value="<?php echo $empleado['usuario']; ?>" required>
            </div>

            <div>
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo $empleado['nombre']; ?>" required>
            </div>
            <div>
                <label>Apellido:</label>
                <input type="text" name="apellido" value="<?php echo $empleado['apellido']; ?>" required>
            </div>

            <div class="full-width">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo $empleado['telefono']; ?>">
            </div>

            <div style="background: #fdfefe; padding: 10px; border: 1px dashed #3498db; border-radius: 5px;">
                <label style="color: #3498db;">Puesto / Cargo:</label>
                <select name="puesto" required>
                    <option value="vendedor" <?php if($empleado['puesto'] == 'vendedor') echo 'selected'; ?>>Asesor de Ventas</option>
                    <option value="admin" <?php if($empleado['puesto'] == 'admin') echo 'selected'; ?>>Administrador</option>
                    <option value="guia" <?php if($empleado['puesto'] == 'guia') echo 'selected'; ?>>Guía de Museo</option>
                </select>
            </div>

            <div style="background: #fdfefe; padding: 10px; border: 1px dashed #27ae60; border-radius: 5px;">
                <label style="color: #27ae60;">Salario Mensual ($):</label>
                <input type="number" step="0.01" name="salario" value="<?php echo $empleado['salario']; ?>" required>
            </div>
        </div>

        <button type="submit" name="btn_actualizar_emp" class="btn-save">Guardar Cambios en Ficha de Empleado</button>
    </form>
</div>

</body>
</html>