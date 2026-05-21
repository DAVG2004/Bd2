<?php
include '../scripts/db.php';

// 1. Rango de fechas para el reporte (mes actual por defecto)
$fecha_inicio = $_POST['desde'] ?? date('Y-m-01');
$fecha_fin = $_POST['hasta'] ?? date('Y-m-d');

// 2. Consulta de KPIs: Sumamos todas las columnas financieras de la tabla 'venta'
$query = "SELECT 
            COUNT(id_venta) as total_facturas, 
            SUM(precio_venta) as suma_subtotal,
            SUM(IVA) as suma_iva,
            SUM(tarifa_museo) as ganancia_museo, 
            SUM(total) as gran_total 
          FROM venta 
          WHERE DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'";

$res = mysqli_query($conexion, $query);
$datos = mysqli_fetch_assoc($res);
?>

<div style="color: #131212; background: white; padding: 20px; border-radius: 10px; font-family: sans-serif;">
    <h2 style="border-bottom: 2px solid #0914af; padding-bottom: 10px;">📊 Reporte de Facturación Completo</h2>
    
    <form method="POST" style="margin-bottom: 20px; background: #f4f4f4; padding: 15px; border-radius: 8px;">
        <label>Periodo de Auditoría: </label>
        <input type="date" name="desde" value="<?php echo $fecha_inicio; ?>">
        <label> al </label>
        <input type="date" name="hasta" value="<?php echo $fecha_fin; ?>">
        <button type="submit" style="background: #0914af; color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">Actualizar</button>
    </form>

    <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
        <div style="border: 1px solid #ddd; padding: 15px; flex: 1; min-width: 200px; border-left: 5px solid #27ae60;">
            <h4 style="margin: 0; color: #7f8c8d;">Comisiones Museo</h4>
            <p style="font-size: 1.6em; color: #27ae60; margin: 5px 0;">$<?php echo number_format($datos['ganancia_museo'] ?? 0, 2); ?></p>
            <small>Ganancia por tarifa de artistas</small>
        </div>
        <div style="border: 1px solid #ddd; padding: 15px; flex: 1; min-width: 200px; border-left: 5px solid #e67e22;">
            <h4 style="margin: 0; color: #7f8c8d;">IVA Recaudado (16%)</h4>
            <p style="font-size: 1.6em; color: #e67e22; margin: 5px 0;">$<?php echo number_format($datos['suma_iva'] ?? 0, 2); ?></p>
        </div>
        <div style="border: 1px solid #ddd; padding: 15px; flex: 1; min-width: 200px; border-left: 5px solid #0914af;">
            <h4 style="margin: 0; color: #7f8c8d;">Recaudación Bruta</h4>
            <p style="font-size: 1.6em; color: #0914af; margin: 5px 0;">$<?php echo number_format($datos['gran_total'] ?? 0, 2); ?></p>
            <small>Total cobrado a clientes</small>
        </div>
    </div>

    <h3>📜 Desglose Detallado de Facturas</h3>
    <table width="100%" border="1" style="border-collapse: collapse; text-align: left; font-size: 0.9em;">
        <thead style="background: #0914af; color: white;">
            <tr>
                <th style="padding: 10px;">Factura #</th>
                <th style="padding: 10px;">Fecha</th>
                <th style="padding: 10px;">Precio Obra (Subtotal)</th>
                <th style="padding: 10px;">IVA (16%)</th>
                <th style="padding: 10px;">Comisión Museo</th>
                <th style="padding: 10px;">Total Pagado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtenemos el detalle de cada venta
            $det = mysqli_query($conexion, "SELECT * FROM venta WHERE DATE(fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' ORDER BY fecha DESC");
            
            if (mysqli_num_rows($det) > 0) {
                while($v = mysqli_fetch_assoc($det)) {
                    echo "<tr style='border-bottom: 1px solid #eee;'>
                            <td style='padding: 10px;'>#{$v['id_venta']}</td>
                            <td style='padding: 10px;'>" . date('d/m/Y', strtotime($v['fecha'])) . "</td>
                            <td style='padding: 10px;'>$".number_format($v['precio_venta'], 2)."</td>
                            <td style='padding: 10px; color: #e67e22;'>$".number_format($v['IVA'], 2)."</td>
                            <td style='padding: 10px; color: #27ae60;'>$".number_format($v['tarifa_museo'], 2)."</td>
                            <td style='padding: 10px;'><strong>$".number_format($v['total'], 2)."</strong></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center; padding: 20px; color: #999;'>No se encontraron transacciones en este rango de fechas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>