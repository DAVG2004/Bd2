<?php
// No incluimos db.php aquí porque se asume que este archivo es llamado desde admin_panel.php
// que ya tiene la conexión activa.

$query = "SELECT o.nombre AS nombre_crudo, 
                 o.precio AS precio_base, 
                 a.nombre AS nombre_artista, 
                 a.apellido AS apellido_artista,
                 c.nombre AS nombre_comprador, 
                 v.fecha AS fecha_venta,
                 v.total AS monto_total
          FROM obra o
          JOIN artista a ON o.id_artista = a.id_artista
          JOIN venta v ON o.id_obra = v.id_obra
          JOIN comprador c ON v.id_comprador = c.id_comprador
          WHERE o.status = 'vendida'
          ORDER BY v.fecha DESC";

$resultado = mysqli_query($conexion, $query);
?>

<div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-family: sans-serif;">
    <h2 style="color: #2c3e50; margin-top: 0;">🖼️ Catálogo de Obras Vendidas</h2>
    <p style="color: #7f8c8d;">Listado histórico de piezas con nombres limpios (sin IDs de archivo).</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background: #34495e; color: white; text-align: left;">
                <th style="padding: 12px;">Obra</th>
                <th style="padding: 12px;">Artista</th>
                <th style="padding: 12px;">Comprador</th>
                <th style="padding: 12px;">Fecha Venta</th>
                <th style="padding: 12px;">Precio Base</th>
                <th style="padding: 12px;">Total Facturado</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (mysqli_num_rows($resultado) > 0):
                while($reg = mysqli_fetch_assoc($resultado)): 
                    
                    // LÓGICA PARA LIMPIAR EL NOMBRE:
                    // Si el nombre viene como "123_cuadro.jpg", esto quita el ID y la extensión.
                    $nombre_archivo = $reg['nombre_crudo'];
                    $partes = explode('_', $nombre_archivo);
                    $nombre_sin_id = end($partes); // Toma lo que está después del último '_'
                    $nombre_final = pathinfo($nombre_sin_id, PATHINFO_FILENAME); // Quita el .jpg o .png
            ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">
                        <strong style="text-transform: capitalize; color: #2c3e50;">
                            <?php echo str_replace("-", " ", $nombre_final); ?>
                        </strong>
                    </td>
                    <td style="padding: 12px;"><?php echo $reg['nombre_artista'] . " " . $reg['apellido_artista']; ?></td>
                    <td style="padding: 12px;"><?php echo $reg['nombre_comprador']; ?></td>
                    <td style="padding: 12px;"><?php echo date("d/m/Y", strtotime($reg['fecha_venta'])); ?></td>
                    <td style="padding: 12px;">$<?php echo number_format($reg['precio_base'], 2); ?></td>
                    <td style="padding: 12px; color: #27ae60; font-weight: bold;">$<?php echo number_format($reg['monto_total'], 2); ?></td>
                </tr>
            <?php 
                endwhile; 
            else:
            ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding: 30px; color: #95a5a6;">No se han registrado ventas aún.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>