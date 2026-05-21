<?php
session_start();
include '../scripts/db.php'; 

// 1. LÓGICA DE FILTRADO DINÁMICO
$where = " WHERE 1=1 "; 

if(!empty($_GET['fecha'])) {
    $f = mysqli_real_escape_string($conexion, $_GET['fecha']);
    $where .= " AND DATE(fecha_publicacion) = '$f'";
}
if(!empty($_GET['precio_max'])) {
    $p = (int)$_GET['precio_max'];
    $where .= " AND precio <= $p";
}
if(!empty($_GET['artista'])) {
    $art = (int)$_GET['artista'];
    $where .= " AND id_artista = $art";
}

// Ejecutar consulta de obras disponibles
$query = "SELECT * FROM obra $where ORDER BY fecha_publicacion DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería de Arte - Panel Principal</title>
    <style>
        /* Mantenemos tus estilos originales */
        body { font-family: sans-serif; background-color: #1a1a1a; color: white; padding: 20px; margin: 0; }
        .header-main { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #00d4ff; padding-bottom: 15px; margin-bottom: 20px; }
        
        .filter-section { background: #262626; padding: 15px; border-radius: 8px; margin-bottom: 25px; display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
        .filter-section div { display: flex; flex-direction: column; gap: 5px; }
        .filter-section input, .filter-section select { padding: 8px; border-radius: 4px; border: 1px solid #444; background: #111; color: white; }
        .btn-filter { background: #00ff88; color: black; font-weight: bold; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }

        .upload-section { background: #2a2a2a; padding: 20px; border-radius: 12px; margin-bottom: 30px; border: 1px dashed #00d4ff; }
        .upload-section h3 { margin-top: 0; color: #00d4ff; }
        .form-pub { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .form-pub input, .form-pub select, .form-pub textarea { background: #111; color: white; border: 1px solid #444; padding: 10px; border-radius: 5px; }

        .galeria-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        .obra-card { background: #2a2a2a; border-radius: 12px; overflow: hidden; border: 1px solid #444; transition: 0.3s; display: flex; flex-direction: column; }
        .obra-card:hover { transform: translateY(-5px); }
        .obra-card img { width: 100%; height: 220px; object-fit: cover; }
        
        .obra-info { padding: 15px; text-align: center; }
        .obra-titulo { margin: 0 0 10px 0; font-size: 1.2em; color: #00d4ff; text-transform: capitalize; }
        
        /* Estilo nuevo para la descripción en la tarjeta */
        .obra-desc { font-size: 0.85em; color: #bbb; margin-bottom: 10px; font-style: italic; }

        .acciones { padding: 0 15px 15px; display: flex; flex-direction: column; gap: 8px; }
        .btn-acc { display: block; padding: 10px; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center; }
        .btn-edit { background: #f0ad4e; color: black; }
        .btn-del { background: #ff4d4d; color: white; }
        .btn-buy { background: #007bff; color: white; border: none; cursor: pointer; width: 100%; font-family: inherit; font-size: 1em; }
        .btn-admin { background: #00d4ff; color: black; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        
        .select-empleado { padding: 8px; border-radius: 5px; background: #111; color: white; border: 1px solid #444; margin-bottom: 5px; width: 100%; }
        .label-asesor { font-size: 0.85em; color: #00d4ff; margin-bottom: 2px; display: block; }
    </style>

    <script>
        // Función para mostrar campos según el género seleccionado
        function mostrarCamposExtra() {
    const genero = document.getElementById('id_genero').value;
    const contenedor = document.getElementById('campos_dinamicos');
    contenedor.innerHTML = ""; 
    contenedor.style.display = "grid";
    contenedor.style.gridTemplateColumns = "1fr 1fr";
    contenedor.style.gap = "10px";

    // Mapeo de campos según tu base de datos
    const campos = {
        "1": ` <input type="text" name="tecnica" placeholder="Técnica" required>
                <input type="text" name="soporte" placeholder="Soporte (Lienzo, Madera)">
                <input type="number" step="0.01" name="alto" placeholder="Alto (cm)">
                <input type="number" step="0.01" name="ancho" placeholder="Ancho (cm)">`,
        
        "2": ` <input type="text" name="material" placeholder="Material (Bronce, Mármol, Barro)" required>
                <input type="number" step="0.01" name="peso" placeholder="Peso (kg)">
                <input type="number" step="0.01" name="alto" placeholder="Alto (cm)">
                <input type="number" step="0.01" name="ancho" placeholder="Ancho (cm)">`,
        
        "3": ` <input type="text" name="tecnica" placeholder="Técnica (Digital, Análoga)" required>
                <input type="text" name="papel" placeholder="Tipo de Papel">
                <input type="number" step="0.01" name="alto" placeholder="Alto (cm)">
                <input type="number" step="0.01" name="ancho" placeholder="Ancho (cm)">`,
        
        "4": ` <input type="text" name="arcilla" placeholder="Tipo de material" required>
                <input type="text" name="tecnica" placeholder="Técnica">
                <input type="number" step="0.01" name="peso" placeholder="Peso (kg)">
                <input type="number" step="0.01" name="alto" placeholder="Alto (cm)">
                <input type="number" step="0.01" name="ancho" placeholder="Ancho (cm)">`,
        
        "5": ` <input type="text" name="material" placeholder="Material (Oro, Plata)" required>
                <input type="text" name="tecnica" placeholder="Técnica">
                <input type="number" step="0.01" name="peso" placeholder="Peso (gr)">`
    };

    contenedor.innerHTML = campos[genero] || "";
}
    </script>
</head>
<body>

<div class="header-main">
    <h1>Galería de Arte</h1>
    <div style="display:flex; align-items:center; gap:20px;">
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
            <a href="admin_panel.php" class="btn-admin">⚙️ Panel Admin</a>
        <?php endif; ?>

        <span>Bienvenido, <strong><?php echo $_SESSION['nombre'] ?? 'Invitado'; ?></strong></span>
        <a href="login.php" style="color:#ff4d4d; text-decoration:none; font-weight:bold;">Cerrar Sesión</a>
    </div>
</div>

<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'artista'): ?>
    <section class="upload-section">
        <h3>🎨 Publicar Nueva Obra</h3>
        <form action="../scripts/subir.php" method="POST" enctype="multipart/form-data" class="form-pub">
            <input type="file" name="foto_obra" required>
            <input type="text" name="nombre_obra" placeholder="Título de la obra" required style="flex:1;">
            <input type="number" name="precio" placeholder="Precio ($)" required style="width:100px;">
            
            <select name="id_genero" id="id_genero" onchange="mostrarCamposExtra()" required>
                <option value="" disabled selected>Selecciona Género</option>
                <?php
                $res_gen = mysqli_query($conexion, "SELECT * FROM genero");
                if($res_gen){
                    while($g = mysqli_fetch_assoc($res_gen)) {
                        echo "<option value='{$g['ID']}'>{$g['nombre']}</option>";
                    }
                }
                ?>
            </select>

            <!-- <textarea name="descripcion" placeholder="Descripción de la obra..." style="width:100%; margin-top:10px;"></textarea> -->
            
            <div id="campos_dinamicos" style="width:100%; display:flex; gap:10px;"></div>

            <button type="submit" name="enviar" style="background:#00d4ff; border:none; padding:10px 20px; border-radius:5px; cursor:pointer; font-weight:bold; margin-top:10px;">Publicar</button>
        </form>
    </section>
<?php endif; ?>

<form method="GET" class="filter-section">
    <div>
        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?php echo $_GET['fecha'] ?? ''; ?>">
    </div>
    <div>
        <label>Precio Máx:</label>
        <input type="number" name="precio_max" placeholder="Ej: 500" value="<?php echo $_GET['precio_max'] ?? ''; ?>">
    </div>
    <div>
        <label>Artista:</label>
        <select name="artista">
            <option value="">Todos</option>
            <?php
            $res_art = mysqli_query($conexion, "SELECT id_artista, nombre FROM artista");
            while($a = mysqli_fetch_assoc($res_art)) {
                $sel = (isset($_GET['artista']) && $_GET['artista'] == $a['id_artista']) ? 'selected' : '';
                echo "<option value='{$a['id_artista']}' $sel>{$a['nombre']}</option>";
            }
            ?>
        </select>
    </div>
    <button type="submit" class="btn-filter">Aplicar Filtros</button>
    <a href="index.php" style="color: #aaa; text-decoration: none; font-size: 0.9em;">Limpiar</a>
</form>

<div class="galeria-container">
    <?php while ($fila = mysqli_fetch_assoc($resultado)): 
        $id = $fila['id_obra']; 
        $nombre_archivo = $fila['nombre'];
        $ruta = "../resources/" . $nombre_archivo;
        $partes = explode('_', $nombre_archivo);
        $nombreLimpio = pathinfo(end($partes), PATHINFO_FILENAME);
    ?>
        <div class="obra-card">
            <?php if(file_exists($ruta)): ?>
                <img src="<?php echo $ruta; ?>" alt="Obra">
            <?php endif; ?>
            
            <div class="obra-info">
                <h3 class="obra-titulo"><?php echo $nombreLimpio; ?></h3>

                <p style="color:#00ff88; font-weight:bold; font-size:1.2em;">$<?php echo number_format($fila['precio'], 2); ?></p>
                <p style="font-size:0.8em; color:#888;">Estado: <?php echo strtoupper($fila['status']); ?></p>
            </div>
            
            <div class="acciones">
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'artista'): ?>
                    <a href="editar_obra.php?id=<?php echo $id; ?>" class="btn-acc btn-edit">✏️ Editar Obra</a>
                    <a href="../scripts/eliminar.php?id=<?php echo $id; ?>" 
                       class="btn-acc btn-del" 
                       onclick="return confirm('¿Estás seguro de eliminar esta obra?')">🗑️ Eliminar Obra</a>
                <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'comprador'): ?>
                    <form action="procesar_compra.php" method="GET">
                        <input type="hidden" name="id_obra" value="<?php echo $id; ?>">
                        
                        <label class="label-asesor">¿Quién le asesoró?</label>
                        <select name="id_empleado" class="select-empleado" required>
                            <option value="" disabled selected>Elegir empleado...</option>
                            <?php
                            $res_emp = mysqli_query($conexion, "SELECT id_empleado, nombre, apellido FROM empleado WHERE rol != 'administrador'");
                            while($emp = mysqli_fetch_assoc($res_emp)) {
                                echo "<option value='{$emp['id_empleado']}'>{$emp['nombre']} {$emp['apellido']}</option>";
                            }
                            ?>
                        </select>
                        
                        <button type="submit" class="btn-acc btn-buy">🛒 Comprar Obra</button>
                    </form>
                <?php else: ?>
                    <p style="text-align:center; font-size:0.9em; color:#aaa;">Inicia sesión como comprador para adquirir esta obra.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>