<?php
session_start();
include '../scripts/db.php'; // Conexión a la base de datos

// Seguridad: Permitir acceso a usuarios cuyo rol sea "administrador" o "admin"
if (!isset($_SESSION['rol']) || !in_array(strtolower($_SESSION['rol']), ['administrador', 'admin'])) {
    header("Location: login.php");
    exit();
}

// Capturamos la sección desde la URL para cargar el contenido dinámico
$seccion = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Administrador</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar h2 { border-bottom: 1px solid #34495e; padding-bottom: 10px; }
        .sidebar nav { display: flex; flex-direction: column; gap: 5px; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px; border-radius: 5px; }
        .sidebar a:hover, .active { background: #34495e; color: #00d4ff !important; }
        .main-content { flex: 1; padding: 40px; background: #f4f7f6; color: #333; }
        
        /* Estilos generales para las tablas que se carguen */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #34495e; color: white; }
        .btn-update { background: #f0ad4e; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-delete { background: #d9534f; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <h2>Gestión Museo</h2>
            <nav>
                <a href="admin_panel.php?view=dashboard" class="<?= $seccion == 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
                <a href="admin_panel.php?view=usuarios" class="<?= $seccion == 'usuarios' ? 'active' : '' ?>">👥 Gestionar Usuarios</a>
                <a href="admin_panel.php?view=facturacion" class="<?= $seccion == 'facturacion' ? 'active' : '' ?>">💰 Reporte Facturación</a>
                <a href="admin_panel.php?view=obras_vendidas" class="<?= $seccion == 'obras_vendidas' ? 'active' : '' ?>">🖼️ Obras Vendidas</a>
                <hr style="width: 100%; border: 0; border-top: 1px solid #444; margin: 10px 0;">
                <a href="admin_panel.php?view=mongo_migrar" class="<?= $seccion == 'mongo_migrar' ? 'active' : '' ?>">🚀 Migrar a MongoDB</a>
                <a href="admin_panel.php?view=mongo_ver"    class="<?= $seccion == 'mongo_ver'    ? 'active' : '' ?>">🗄️ Ver Datos Mongo</a>
                <hr style="width: 100%; border: 0; border-top: 1px solid #444; margin: 10px 0;">
                <a href="admin_panel.php?view=cassandra_setup" class="<?= $seccion == 'cassandra_setup' ? 'active' : '' ?>">⚙️ Setup Cassandra</a>
                <a href="admin_panel.php?view=cassandra_ver"   class="<?= $seccion == 'cassandra_ver'   ? 'active' : '' ?>">🗄️ Ver Logs Cassandra</a>
                <hr style="width: 100%; border: 0; border-top: 1px solid #444; margin: 10px 0;">
                <a href="index.php">🏠 Volver a Galería</a>
                <a href="../scripts/logout.php" style="color: #ff4d4d;">🚪 Cerrar Sesión</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php 
                // Lógica de ruteo para incluir los archivos correspondientes
                switch($seccion) {
                    case 'usuarios':
                        // Incluye la gestión de Artistas, Compradores y Empleados
                        include 'admin_crud.php';
                        break;
                    case 'facturacion':
                        // Incluye el reporte de ingresos e IVA
                        include 'admin_rep_facturacion.php';
                        break;
                    case 'obras_vendidas':
                        // Incluye el catálogo histórico de ventas
                        include 'admin_rep_obras.php';
                        break;
                    case 'mongo_migrar':
                        // Formulario para migrar artistas y obras de MySQL → MongoDB
                        include 'admin_mongo_migrate.php';
                        break;
                    case 'mongo_ver':
                        // Visualización de colecciones directamente desde MongoDB
                        include 'admin_mongo_view.php';
                        break;
                    case 'cassandra_setup':
                        // Inicialización de tablas Cassandra y carga de prueba
                        include 'admin_cassandra_setup.php';
                        break;
                    case 'cassandra_ver':
                        // Visor de bitácoras de Cassandra
                        include 'admin_cassandra_view.php';
                        break;
                    default:
                        echo "<h1>Bienvenido, Administrador</h1>";
                        echo "<p>Seleccione una opción del menú lateral para gestionar la plataforma.</p>";
                        break;
                }
            ?>
        </main>
    </div>
</body>
</html>