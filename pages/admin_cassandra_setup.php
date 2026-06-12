<?php
/**
 * admin_cassandra_setup.php
 * Script para inicializar las tablas (Column Families) de Cassandra
 * y cargar datos de prueba utilizando comandos cqlsh en el contenedor Docker.
 */

$log_output = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'setup_cassandra') {
        // Ejecutar los scripts cqlsh a través del contenedor de Docker
        $cmd1 = "docker exec cassandra-museo cqlsh -f /scripts/01_ddl_tablas.cql 2>&1";
        $cmd2 = "docker exec cassandra-museo cqlsh -f /scripts/02_dml_datos.cql 2>&1";
        
        $output1 = shell_exec($cmd1);
        $output2 = shell_exec($cmd2);
        
        $log_output = "=== Ejecución de 01_ddl_tablas.cql ===\n" . ($output1 ? $output1 : "Completado sin errores visibles.") . "\n\n";
        $log_output .= "=== Ejecución de 02_dml_datos.cql ===\n" . ($output2 ? $output2 : "Completado sin errores visibles.") . "\n";
        
        // Si no hay errores evidentes de conexión, asumimos éxito
        if (strpos($log_output, 'Connection error') === false && strpos($log_output, 'NoHostAvailable') === false) {
            $success = true;
        }
    }
}
?>

<div class="mongo-view" style="font-family: 'Segoe UI', sans-serif;">
    <h2 style="color: #2c3e50; border-left: 4px solid #27ae60; padding-left: 12px; margin-top: 0;">
        ⚙️ Configuración y Generación de Tablas Cassandra
    </h2>
    <p>Esta herramienta inicializa las familias de columnas (tablas) en el Keyspace <code>museo_logs</code> y carga algunos datos de prueba iniciales desde los archivos CQL.</p>

    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-top: 20px;">
        <h3>Acciones Disponibles</h3>
        <p style="color: #666; font-size: 0.9em;">Asegúrese de que el contenedor <strong>cassandra-museo</strong> esté corriendo antes de ejecutar esto.</p>
        
        <form method="POST">
            <input type="hidden" name="action" value="setup_cassandra">
            <button type="submit" style="background: #27ae60; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 10px;">
                🚀 Ejecutar Scripts de Generación
            </button>
        </form>
    </div>

    <?php if ($log_output !== ""): ?>
        <div style="margin-top: 30px; padding: 20px; border-radius: 8px; background: <?= $success ? '#e8f8f5' : '#fdedec' ?>; border: 1px solid <?= $success ? '#a3e4d7' : '#f5b7b1' ?>;">
            <h3 style="color: <?= $success ? '#117a65' : '#c0392b' ?>; margin-top: 0;">
                <?= $success ? '✅ Ejecución Completada' : '❌ Posibles Errores en la Ejecución' ?>
            </h3>
            <pre style="background: #333; color: #f1f1f1; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 0.85em;"><?= htmlspecialchars($log_output) ?></pre>
            
            <?php if ($success): ?>
                <p style="margin-top: 15px;">
                    <a href="admin_panel.php?view=cassandra_ver" style="display: inline-block; background: #2980b9; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                        ➡️ Ir a Visualizar Datos de Cassandra
                    </a>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
