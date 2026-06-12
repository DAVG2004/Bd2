<?php
session_start();
include '../scripts/db.php'; 

// 1. LÓGICA DE FILTRADO DINÁMICO
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'artista') {
    // Si es artista, ve todas sus obras sin importar si están disponibles o vendidas
    $id_art = (int)$_SESSION['id'];
    $where = " WHERE id_artista = $id_art ";
} elseif (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'comprador' && $_SESSION['rol'] !== 'artista') {
    // Es empleado (administrador, encargado, etc.). Ve TODAS las obras independientemente de su estado.
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
} else {
    // Para compradores e invitados, solo ven las disponibles
    $where = " WHERE status = 'disponible' "; 

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
}

// Ejecutar consulta de obras disponibles
$query = "SELECT * FROM obra $where ORDER BY fecha_publicacion DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería Eclipse - Inicio</title>
    <style>
        /* Mantenemos tus estilos originales */
        body { font-family: sans-serif; background-color: #1a1a1a; color: white; padding: 20px; margin: 0; }
        .header-main { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #00d4ff; padding-bottom: 15px; margin-bottom: 20px; }
        
        /* Sidebar Filter Styles */
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 900; backdrop-filter: blur(2px); }
        .filter-sidebar { position: fixed; top: 0; left: -350px; width: 320px; height: 100%; background: #1a1a1a; border-right: 2px solid #00d4ff; z-index: 1000; transition: 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); padding: 25px; box-sizing: border-box; display: flex; flex-direction: column; gap: 20px; box-shadow: 5px 0 15px rgba(0,0,0,0.5); }
        .filter-sidebar.open { left: 0; }
        .filter-sidebar h3 { margin-top: 0; color: #00d4ff; border-bottom: 1px solid #444; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .btn-close-sidebar { background: none; border: none; color: #ff4d4d; font-size: 1.8em; cursor: pointer; font-weight: bold; padding: 0; }
        .filter-sidebar div { display: flex; flex-direction: column; gap: 8px; }
        .filter-sidebar input, .filter-sidebar select { padding: 12px; border-radius: 6px; border: 1px solid #444; background: #111; color: white; font-size: 1em; }
        .btn-filter { background: #00ff88; color: black; font-weight: bold; border: none; padding: 12px; border-radius: 6px; cursor: pointer; width: 100%; font-size: 1.05em; transition: 0.2s; }
        .btn-filter:hover { background: #00cc6a; }
        .btn-toggle-filter { position: fixed; top: 50%; left: 0; transform: translateY(-50%); background: #00d4ff; color: black; border: none; padding: 15px 12px; border-radius: 0 8px 8px 0; cursor: pointer; font-weight: bold; z-index: 800; font-size: 1em; display: flex; flex-direction: column; align-items: center; gap: 5px; box-shadow: 3px 0 15px rgba(0, 212, 255, 0.4); transition: 0.3s; }
        .btn-toggle-filter:hover { padding-left: 20px; background: #00bfff; }

        /* Upload Sidebar Styles */
        .upload-sidebar { position: fixed; top: 0; right: -400px; width: 360px; height: 100%; background: #1a1a1a; border-left: 2px solid #00ff88; z-index: 1000; transition: 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); padding: 25px; box-sizing: border-box; display: flex; flex-direction: column; gap: 20px; box-shadow: -5px 0 15px rgba(0,0,0,0.5); overflow-y: auto; }
        .upload-sidebar.open { right: 0; }
        .btn-toggle-upload { position: fixed; top: 50%; right: 0; transform: translateY(-50%); background: #00ff88; color: black; border: none; padding: 15px 12px; border-radius: 8px 0 0 8px; cursor: pointer; font-weight: bold; z-index: 800; font-size: 1em; display: flex; flex-direction: column; align-items: center; gap: 5px; box-shadow: -3px 0 15px rgba(0, 255, 136, 0.4); transition: 0.3s; }
        .btn-toggle-upload:hover { padding-right: 20px; background: #00cc6a; }
        .form-pub { display: flex; flex-direction: column; gap: 15px; }
        .form-pub input, .form-pub select, .form-pub textarea { background: #111; color: white; border: 1px solid #444; padding: 12px; border-radius: 5px; width: 100%; box-sizing: border-box; }

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
        
        /* Modal Styles */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #2a2a2a; padding: 25px; border-radius: 12px; border: 1px solid #00d4ff; width: 90%; max-width: 700px; position: relative; color: white; box-shadow: 0 0 20px rgba(0, 212, 255, 0.2); }
        .modal-close { position: absolute; top: 15px; right: 20px; font-size: 1.8em; cursor: pointer; color: #ff4d4d; font-weight: bold; }
        .modal-grid { display: flex; gap: 20px; margin-top: 15px; }
        .modal-img { width: 300px; height: auto; max-height: 400px; border-radius: 8px; object-fit: contain; background: #111; }
        .modal-info { flex: 1; }
        .modal-info h2 { margin-top: 0; color: #00d4ff; text-transform: capitalize; border-bottom: 1px solid #444; padding-bottom: 10px; }
        .modal-info p { margin: 8px 0; color: #ccc; font-size: 0.95em; }
        .modal-info strong { color: white; }
        .modal-tech-box { background: #111; padding: 15px; border-radius: 8px; margin-top: 15px; border: 1px solid #444; }
        .modal-tech-box h4 { margin-top: 0; color: #00ff88; margin-bottom: 10px; }
        .btn-ver-detalles { background: #444; color: white; border: 1px solid #666; padding: 8px; text-align: center; border-radius: 6px; cursor: pointer; display: block; text-decoration: none; font-size: 0.9em; margin-bottom: 10px; }
        .btn-ver-detalles:hover { background: #555; }
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
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="../resources/logo_eclipse.png" alt="Logo Eclipse" style="height: 60px; width: 60px; object-fit: cover; border-radius: 50%; box-shadow: 0 0 15px rgba(0, 212, 255, 0.6); border: 2px solid #00d4ff;">
        <div style="display: flex; flex-direction: column;">
            <h1 style="margin: 0; font-size: 2em; letter-spacing: 2px; text-transform: uppercase;">Galería <span style="color: #00d4ff;">Eclipse</span></h1>
            <a href="presentacion.php" style="color: #00ff88; text-decoration: none; font-size: 0.95em; font-weight: bold; margin-top: 2px;">✨ Conócenos</a>
        </div>
    </div>
    <div style="display:flex; align-items:center; gap:20px;">
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
            <a href="admin_panel.php" class="btn-admin">⚙️ Panel Admin</a>
        <?php endif; ?>

        <span>Bienvenido, <strong><?php echo $_SESSION['nombre'] ?? 'Invitado'; ?></strong></span>
        <?php if (isset($_SESSION['id'])): ?>
            <a href="../scripts/logout.php" style="color:#ff4d4d; text-decoration:none; font-weight:bold;">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php" style="color:#00ff88; text-decoration:none; font-weight:bold;">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'artista'): ?>
    <button class="btn-toggle-upload" onclick="toggleUploadSidebar()">
        <span style="font-size: 1.5em;">🎨</span>
        <span style="writing-mode: vertical-rl; text-orientation: mixed; margin-top: 10px; letter-spacing: 2px;">PUBLICAR</span>
    </button>

    <div class="sidebar-overlay" id="uploadOverlay" onclick="toggleUploadSidebar()"></div>

    <section class="upload-sidebar" id="uploadSidebar">
        <h3 style="margin-top: 0; color: #00ff88; border-bottom: 1px solid #444; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
            <span>🎨 Publicar Obra</span>
            <button type="button" class="btn-close-sidebar" onclick="toggleUploadSidebar()">&times;</button>
        </h3>
        <form action="../scripts/subir.php" method="POST" enctype="multipart/form-data" class="form-pub">
            <div>
                <label style="color:#bbb; font-size:0.9em; margin-bottom:5px; display:block;">Foto de la obra:</label>
                <input type="file" name="foto_obra" required style="padding:5px;">
            </div>
            
            <input type="text" name="nombre_obra" placeholder="Título de la obra" required>
            
            <div>
                <label style="color:#bbb; font-size:0.9em; margin-bottom:5px; display:block;">Precio de venta ($):</label>
                <input type="number" name="precio" placeholder="Ej: 1500" required>
            </div>
            
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

            <div id="campos_dinamicos" style="width:100%; display:flex; flex-direction:column; gap:15px; margin-top:5px;"></div>

            <button type="submit" name="enviar" class="btn-filter" style="margin-top:20px;">Publicar Obra</button>
        </form>
    </section>
<?php endif; ?>

<?php if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'artista'): ?>
<button class="btn-toggle-filter" onclick="toggleSidebar()">
    <span style="font-size: 1.5em;">🔍</span>
    <span style="writing-mode: vertical-rl; text-orientation: mixed; margin-top: 10px; letter-spacing: 2px;">FILTROS</span>
</button>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<form method="GET" class="filter-sidebar" id="filterSidebar">
    <h3>
        <span>🔍 Filtros de Búsqueda</span> 
        <button type="button" class="btn-close-sidebar" onclick="toggleSidebar()">&times;</button>
    </h3>
    
    <div>
        <label>Fecha de Publicación:</label>
        <input type="date" name="fecha" value="<?php echo $_GET['fecha'] ?? ''; ?>">
    </div>
    
    <div>
        <label>Precio Máximo ($):</label>
        <input type="number" name="precio_max" placeholder="Ej: 500" value="<?php echo $_GET['precio_max'] ?? ''; ?>">
    </div>
    
    <div>
        <label>Artista:</label>
        <select name="artista">
            <option value="">Todos los artistas</option>
            <?php
            $res_art = mysqli_query($conexion, "SELECT id_artista, nombre, apellido FROM artista");
            while($a = mysqli_fetch_assoc($res_art)) {
                $sel = (isset($_GET['artista']) && $_GET['artista'] == $a['id_artista']) ? 'selected' : '';
                echo "<option value='{$a['id_artista']}' $sel>{$a['nombre']} {$a['apellido']}</option>";
            }
            ?>
        </select>
    </div>
    
    <div style="margin-top: 20px;">
        <button type="submit" class="btn-filter">Aplicar Filtros</button>
        <a href="index.php" style="color: #aaa; text-decoration: none; font-size: 0.9em; text-align: center; display: block; margin-top: 15px; padding: 10px; border: 1px solid #444; border-radius: 6px;">🗑️ Limpiar todo</a>
    </div>
</form>
<?php endif; ?>

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
                <a href="javascript:void(0)" onclick="abrirModal(<?php echo $id; ?>)">
                    <img src="<?php echo $ruta; ?>" alt="Obra">
                </a>
            <?php endif; ?>
            
            <div class="obra-info">
                <a href="javascript:void(0)" onclick="abrirModal(<?php echo $id; ?>)" style="text-decoration: none;">
                    <h3 class="obra-titulo"><?php echo $nombreLimpio; ?></h3>
                </a>

                <p style="color:#00ff88; font-weight:bold; font-size:1.2em;">$<?php echo number_format($fila['precio'], 2); ?></p>
                <p style="font-size:0.8em; color:#888;">Estado: <?php echo strtoupper($fila['status']); ?></p>
            </div>
            
            <div class="acciones">
                <button type="button" class="btn-ver-detalles" onclick="abrirModal(<?php echo $id; ?>)">🔍 Ver Detalles</button>
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
                            $res_emp = mysqli_query($conexion, "SELECT id_empleado, nombre, apellido FROM empleado WHERE puesto != 'administrador'");
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

<!-- MODAL DE DETALLES -->
<div id="obraModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" onclick="cerrarModal()">&times;</span>
        <div id="modal-cargando">Cargando detalles...</div>
        <div id="modal-datos" style="display: none;">
            <div class="modal-grid">
                <div>
                    <img id="m-img" class="modal-img" src="" alt="Obra">
                </div>
                <div class="modal-info">
                    <h2 id="m-titulo">Título</h2>
                    <p style="color:#00ff88; font-weight:bold; font-size:1.3em;" id="m-precio">$0.00</p>
                    <p><strong>Autor:</strong> <span id="m-autor"></span></p>
                    <p><strong>Nacionalidad:</strong> <span id="m-nac"></span></p>
                    <p><strong>Género:</strong> <span id="m-genero"></span></p>
                    <p><strong>Fecha Pub.:</strong> <span id="m-fecha"></span></p>
                    
                    <div class="modal-tech-box" id="m-tech-box">
                        <h4>Detalles Técnicos</h4>
                        <div id="m-tech-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModal(idObra) {
    const modal = document.getElementById('obraModal');
    const cargando = document.getElementById('modal-cargando');
    const datos = document.getElementById('modal-datos');
    
    modal.style.display = 'flex';
    cargando.style.display = 'block';
    datos.style.display = 'none';

    fetch('../scripts/obtener_detalles.php?id=' + idObra)
        .then(response => response.json())
        .then(res => {
            cargando.style.display = 'none';
            if(res.status === 'success') {
                const o = res.data;
                document.getElementById('m-titulo').innerText = o.tituloLimpio;
                document.getElementById('m-img').src = o.ruta_imagen;
                document.getElementById('m-precio').innerText = "$" + parseFloat(o.precio).toFixed(2);
                document.getElementById('m-autor').innerText = o.artista_nombre + " " + o.artista_apellido;
                document.getElementById('m-nac').innerText = o.nacionalidad || 'N/A';
                document.getElementById('m-genero').innerText = o.genero;
                document.getElementById('m-fecha').innerText = o.fecha_publicacion;
                
                let techHTML = "";
                if(o.detalles_tecnicos) {
                    for (const [key, value] of Object.entries(o.detalles_tecnicos)) {
                        techHTML += `<p style="text-transform: capitalize;"><strong>${key.replace('_', ' ')}:</strong> ${value}</p>`;
                    }
                }
                
                if (techHTML === "") {
                    techHTML = "<p>No hay detalles técnicos registrados.</p>";
                }
                document.getElementById('m-tech-content').innerHTML = techHTML;
                
                datos.style.display = 'block';
            } else {
                cargando.innerHTML = `<span style="color:red">Error: ${res.message}</span>`;
            }
        })
        .catch(err => {
            cargando.innerHTML = `<span style="color:red">Error de red.</span>`;
        });
}

function cerrarModal() {
    document.getElementById('obraModal').style.display = 'none';
}

// Cerrar modal al hacer clic fuera del contenido
window.onclick = function(event) {
    const modal = document.getElementById('obraModal');
    if (event.target == modal) {
        cerrarModal();
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('filterSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
        overlay.style.display = 'none';
    } else {
        sidebar.classList.add('open');
        overlay.style.display = 'block';
    }
}

function toggleUploadSidebar() {
    const sidebar = document.getElementById('uploadSidebar');
    const overlay = document.getElementById('uploadOverlay');
    if (sidebar && overlay) {
        if (sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            overlay.style.display = 'none';
        } else {
            sidebar.classList.add('open');
            overlay.style.display = 'block';
        }
    }
}
</script>

</body>
</html>