<?php
session_start();
include '../scripts/db.php';

// 1. Verificar si el usuario es artista
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'artista') {
    header("Location: index.php");
    exit;
}

// 2. Obtener los datos actuales de la obra
if (!isset($_GET['id'])) {
    die("Falta ID de la obra.");
}

$id = (int)$_GET['id'];
$query = "SELECT * FROM obra WHERE id_obra = $id";
$res = mysqli_query($conexion, $query);
$obra = mysqli_fetch_assoc($res);

if (!$obra) {
    die("La obra no existe.");
}

$id_genero = $obra['id_genero'];
$sub_data = [];

// Obtener datos técnicos según el género
switch ($id_genero) {
    case 1: 
        $res_sub = mysqli_query($conexion, "SELECT * FROM pintura WHERE id_pintura = $id"); 
        if($res_sub) $sub_data = mysqli_fetch_assoc($res_sub);
        break;
    case 2: 
        $res_sub = mysqli_query($conexion, "SELECT * FROM escultura WHERE id_escultura = $id"); 
        if($res_sub) $sub_data = mysqli_fetch_assoc($res_sub);
        break;
    case 3: 
        $res_sub = mysqli_query($conexion, "SELECT * FROM fotografia WHERE id_fotografia = $id"); 
        if($res_sub) $sub_data = mysqli_fetch_assoc($res_sub);
        break;
    case 4: 
        $res_sub = mysqli_query($conexion, "SELECT * FROM ceramica WHERE id_ceramica = $id"); 
        if($res_sub) $sub_data = mysqli_fetch_assoc($res_sub);
        break;
    case 5: 
        $res_sub = mysqli_query($conexion, "SELECT * FROM orferbreria WHERE id_orfebreria = $id"); 
        if($res_sub) $sub_data = mysqli_fetch_assoc($res_sub);
        break;
}

// 3. Procesar la actualización cuando se presiona "Guardar Cambios"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_precio = (float)$_POST['precio'];
    $nuevo_status = mysqli_real_escape_string($conexion, $_POST['status']);
    
    // Iniciar transacción para actualizar ambas tablas (obra y su técnica)
    mysqli_begin_transaction($conexion);
    
    try {
        $update_query = "UPDATE obra SET precio = $nuevo_precio, status = '$nuevo_status' WHERE id_obra = $id";
        if (!mysqli_query($conexion, $update_query)) {
            throw new Exception("Error al actualizar la tabla principal de la obra: " . mysqli_error($conexion));
        }
        
        $sql_esp = "";
        switch ($id_genero) {
            case 1:
                $t = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $s = mysqli_real_escape_string($conexion, $_POST['soporte']);
                $a = (int)$_POST['alto'];
                $w = (int)$_POST['ancho'];
                $sql_esp = "UPDATE pintura SET tecnica='$t', soporte='$s', alto=$a, ancho=$w WHERE id_pintura=$id";
                break;
            case 2:
                $m = mysqli_real_escape_string($conexion, $_POST['material']);
                $p = (float)$_POST['peso'];
                $a = (int)$_POST['alto'];
                $w = (int)$_POST['ancho'];
                $sql_esp = "UPDATE escultura SET material='$m', peso=$p, alto=$a, ancho=$w WHERE id_escultura=$id";
                break;
            case 3:
                $t = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $p = mysqli_real_escape_string($conexion, $_POST['papel']);
                $a = (int)$_POST['alto'];
                $w = (int)$_POST['ancho'];
                $sql_esp = "UPDATE fotografia SET tecnica='$t', papel='$p', alto=$a, ancho=$w WHERE id_fotografia=$id";
                break;
            case 4:
                $ar = mysqli_real_escape_string($conexion, $_POST['arcilla']);
                $t = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $p = (float)$_POST['peso'];
                $a = (int)$_POST['alto'];
                $w = (int)$_POST['ancho'];
                $sql_esp = "UPDATE ceramica SET arcilla='$ar', tecnica='$t', peso=$p, alto=$a, ancho=$w WHERE id_ceramica=$id";
                break;
            case 5:
                $m = mysqli_real_escape_string($conexion, $_POST['material']);
                $t = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $p = (float)$_POST['peso'];
                $sql_esp = "UPDATE orferbreria SET material='$m', tecnica='$t', peso=$p WHERE id_orfebreria=$id";
                break;
        }
        
        if ($sql_esp != "") {
            if (!mysqli_query($conexion, $sql_esp)) {
                throw new Exception("Error al actualizar los detalles técnicos: " . mysqli_error($conexion));
            }
        }
        
        mysqli_commit($conexion);
        echo "<script>alert('Obra actualizada con éxito'); window.location.href='index.php';</script>";
        exit;
        
    } catch(Exception $e) {
        mysqli_rollback($conexion);
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Obra - Galería Eclipse</title>
    <style>
        body { font-family: sans-serif; background-color: #1a1a1a; color: white; padding: 40px; }
        .edit-container { max-width: 800px; margin: auto; background: #2a2a2a; padding: 30px; border-radius: 15px; border: 1px solid #00d4ff; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h2 { color: #00d4ff; text-align: center; margin-top: 0; font-size: 2em; letter-spacing: 1px; }
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #aaa; font-weight: bold; font-size: 0.9em; text-transform: uppercase; }
        input, select { width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #444; background: #111; color: white; box-sizing: border-box; transition: 0.3s; }
        input:focus, select:focus { border-color: #00d4ff; outline: none; box-shadow: 0 0 8px rgba(0,212,255,0.3); }
        input:disabled { opacity: 0.6; cursor: not-allowed; }
        .preview-img { width: 100%; height: 250px; object-fit: contain; border-radius: 10px; margin-bottom: 20px; border: 2px solid #444; background: #111; }
        .btn-save { background: #00ff88; color: black; border: none; padding: 15px; width: 100%; font-weight: bold; font-size: 1.1em; border-radius: 5px; cursor: pointer; margin-top: 30px; transition: 0.3s; box-shadow: 0 0 15px rgba(0,255,136,0.3); }
        .btn-save:hover { background: #00cc6a; transform: translateY(-2px); box-shadow: 0 5px 20px rgba(0,255,136,0.5); }
        .btn-cancel { display: block; text-align: center; margin-top: 20px; color: #ff4d4d; text-decoration: none; font-size: 1em; transition: 0.3s; }
        .btn-cancel:hover { color: #ff1a1a; text-decoration: underline; }
        .section-title { color: #00ff88; font-size: 1.2em; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        
        @media (max-width: 768px) {
            .grid-layout { grid-template-columns: 1fr; gap: 20px; }
        }
    </style>
</head>
<body>

<div class="edit-container">
    <h2>✏️ Editar Obra</h2>
    
    <form method="POST">
        <input type="hidden" name="id_obra" value="<?php echo $obra['id_obra']; ?>">

        <div class="grid-layout">
            <!-- COLUMNA IZQUIERDA: Info General -->
            <div>
                <h3 class="section-title">Información General</h3>
                <img src="../resources/<?php echo htmlspecialchars($obra['nombre']); ?>" class="preview-img">
                
                <div class="form-group">
                    <label>Título / Archivo (No editable):</label>
                    <input type="text" value="<?php echo htmlspecialchars($obra['nombre']); ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Precio ($):</label>
                    <input type="number" name="precio" step="0.01" value="<?php echo htmlspecialchars($obra['precio']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Estado de la obra:</label>
                    <select name="status">
                        <option value="disponible" <?php if($obra['status'] == 'disponible') echo 'selected'; ?>>Disponible</option>
                        <option value="vendida" <?php if($obra['status'] == 'vendida') echo 'selected'; ?>>Vendida</option>
                    </select>
                </div>
            </div>

            <!-- COLUMNA DERECHA: Ficha Técnica -->
            <div>
                <h3 class="section-title">Ficha Técnica</h3>
                <?php if ($id_genero == 1): // Pintura ?>
                    <div class="form-group">
                        <label>Técnica:</label>
                        <input type="text" name="tecnica" value="<?php echo htmlspecialchars($sub_data['tecnica'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Soporte:</label>
                        <input type="text" name="soporte" value="<?php echo htmlspecialchars($sub_data['soporte'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Alto (cm):</label>
                        <input type="number" name="alto" value="<?php echo htmlspecialchars($sub_data['alto'] ?? 0); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Ancho (cm):</label>
                        <input type="number" name="ancho" value="<?php echo htmlspecialchars($sub_data['ancho'] ?? 0); ?>" required>
                    </div>

                <?php elseif ($id_genero == 2): // Escultura ?>
                    <div class="form-group">
                        <label>Material:</label>
                        <input type="text" name="material" value="<?php echo htmlspecialchars($sub_data['material'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Peso (kg):</label>
                        <input type="number" step="0.01" name="peso" value="<?php echo htmlspecialchars($sub_data['peso'] ?? 0); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Alto (cm):</label>
                        <input type="number" name="alto" value="<?php echo htmlspecialchars($sub_data['alto'] ?? 0); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Ancho (cm):</label>
                        <input type="number" name="ancho" value="<?php echo htmlspecialchars($sub_data['ancho'] ?? 0); ?>" required>
                    </div>

                <?php elseif ($id_genero == 3): // Fotografia ?>
                    <div class="form-group">
                        <label>Técnica:</label>
                        <input type="text" name="tecnica" value="<?php echo htmlspecialchars($sub_data['tecnica'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Papel:</label>
                        <input type="text" name="papel" value="<?php echo htmlspecialchars($sub_data['papel'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Alto (cm):</label>
                        <input type="number" name="alto" value="<?php echo htmlspecialchars($sub_data['alto'] ?? 0); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Ancho (cm):</label>
                        <input type="number" name="ancho" value="<?php echo htmlspecialchars($sub_data['ancho'] ?? 0); ?>" required>
                    </div>

                <?php elseif ($id_genero == 4): // Ceramica ?>
                    <div class="form-group">
                        <label>Tipo de Arcilla:</label>
                        <input type="text" name="arcilla" value="<?php echo htmlspecialchars($sub_data['arcilla'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Técnica:</label>
                        <input type="text" name="tecnica" value="<?php echo htmlspecialchars($sub_data['tecnica'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Peso (kg):</label>
                        <input type="number" step="0.01" name="peso" value="<?php echo htmlspecialchars($sub_data['peso'] ?? 0); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Alto (cm):</label>
                        <input type="number" name="alto" value="<?php echo htmlspecialchars($sub_data['alto'] ?? 0); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Ancho (cm):</label>
                        <input type="number" name="ancho" value="<?php echo htmlspecialchars($sub_data['ancho'] ?? 0); ?>" required>
                    </div>

                <?php elseif ($id_genero == 5): // Orfebreria ?>
                    <div class="form-group">
                        <label>Material:</label>
                        <input type="text" name="material" value="<?php echo htmlspecialchars($sub_data['material'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Técnica:</label>
                        <input type="text" name="tecnica" value="<?php echo htmlspecialchars($sub_data['tecnica'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Peso (kg):</label>
                        <input type="number" step="0.01" name="peso" value="<?php echo htmlspecialchars($sub_data['peso'] ?? 0); ?>" required>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn-save">💾 Guardar Cambios</button>
        <a href="index.php" class="btn-cancel">Cancelar y volver</a>
    </form>
</div>

</body>
</html>