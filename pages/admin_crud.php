<?php
// admin_crud.php - Se carga dinámicamente dentro de admin_panel.php
include '../scripts/db.php'; // Conexión a la base de datos

// 1. Consultas para obtener los usuarios y las obras
$artistas = mysqli_query($conexion, "SELECT id_artista, nombre, apellido, usuario, nacionalidad FROM artista");
$empleados = mysqli_query($conexion, "SELECT id_empleado, nombre, apellido, puesto, salario FROM empleado");
$compradores = mysqli_query($conexion, "SELECT id_comprador, nombre, usuario FROM comprador");

// Consulta extendida para obras, incluyendo el nombre del artista (JOIN)
$obras = mysqli_query($conexion, "SELECT o.*, a.nombre as autor_nombre, a.apellido as autor_apellido 
                                  FROM obra o 
                                  JOIN artista a ON o.id_artista = a.id_artista");
?>

<div class="crud-section" style="font-family: sans-serif;">
    <h2 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">🛠️ Panel de Gestión Global</h2>

    <div style="margin-bottom: 25px; padding: 15px; background: #e8f4f8; border-radius: 8px;">
        <strong>Registros Nuevos:</strong> 
        <a href="registro_artista.php" style="margin-left:10px; text-decoration:none; color:#2980b9;">+ Artista</a> | 
        <a href="registro_empleado.php" style="margin-left:10px; text-decoration:none; color:#2980b9;">+ Empleado</a> | 
        <a href="registro_comprador.php" style="margin-left:10px; text-decoration:none; color:#2980b9;">+ Comprador</a>
    </div>

    <h3 style="background: #34495e; color: white; padding: 10px; border-radius: 4px;">🎨 Artistas Registrados</h3>
    <table width="100%" style="border-collapse: collapse; margin-bottom: 30px; background: white;">
        <thead>
            <tr style="background: #f2f2f2; text-align: left;">
                <th style="padding: 12px; border: 1px solid #ddd;">Nombre Completo</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Usuario</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($art = mysqli_fetch_assoc($artistas)): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $art['nombre'] . " " . $art['apellido']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $art['usuario']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <a href="actualizar_artista.php?id=<?php echo $art['id_artista']; ?>" style="color: #f39c12; text-decoration:none; font-weight:bold;">Actualizar</a> | 
                    <a href="../scripts/eliminar_usuario.php?id=<?php echo $art['id_artista']; ?>&tipo=artista" style="color: #e74c3c; text-decoration:none;" onclick="return confirm('¿Eliminar artista?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3 style="background: #e67e22; color: white; padding: 10px; border-radius: 4px;">🖼️ Inventario de Obras de Arte</h3>
    <table width="100%" style="border-collapse: collapse; margin-bottom: 30px; background: white;">
        <thead>
            <tr style="background: #fdf2e9; text-align: left;">
                <th style="padding: 12px; border: 1px solid #ddd;">Título</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Artista</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Precio</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Estado</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($ob = mysqli_fetch_assoc($obras)): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $ob['nombre']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $ob['autor_nombre'] . " " . $ob['autor_apellido']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">$<?php echo number_format($ob['precio'], 2); ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <span style="font-weight:bold; color: <?php echo (strtolower($ob['status']) == 'disponible') ? '#27ae60' : '#e74c3c'; ?>;">
                        <?php echo strtoupper($ob['status']); ?>
                    </span>
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <a href="actualizar_obra.php?id=<?php echo $ob['id_obra']; ?>" style="color: #e67e22; text-decoration:none; font-weight:bold;">Editar</a> | 
                    <a href="../scripts/eliminar_usuario.php?id=<?php echo $ob['id_obra']; ?>&tipo=obra" style="color: #c0392b; text-decoration:none;" onclick="return confirm('¿Eliminar esta obra?')">Borrar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3 style="background: #34495e; color: white; padding: 10px; border-radius: 4px;">🛒 Cartera de Compradores</h3>
    <table width="100%" style="border-collapse: collapse; margin-bottom: 30px; background: white;">
        <thead>
            <tr style="background: #f2f2f2; text-align: left;">
                <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Nombre</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Usuario</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($com = mysqli_fetch_assoc($compradores)): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $com['id_comprador']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $com['nombre']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $com['usuario']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <a href="actualizar_comprador.php?id=<?php echo $com['id_comprador']; ?>" style="color: #f39c12; text-decoration:none; font-weight:bold;">Actualizar</a> | 
                    <a href="../scripts/eliminar_usuario.php?id=<?php echo $com['id_comprador']; ?>&tipo=comprador" style="color: #e74c3c; text-decoration:none;" onclick="return confirm('¿Eliminar comprador?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3 style="background: #34495e; color: white; padding: 10px; border-radius: 4px;">👔 Personal del Museo</h3>
    <table width="100%" style="border-collapse: collapse; background: white;">
        <thead>
            <tr style="background: #f2f2f2; text-align: left;">
                <th style="padding: 12px; border: 1px solid #ddd;">Nombre Completo</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Puesto</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($emp = mysqli_fetch_assoc($empleados)): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $emp['nombre'] . " " . $emp['apellido']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $emp['puesto']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <a href="actualizar_empleado.php?id=<?php echo $emp['id_empleado']; ?>" style="color: #f39c12; text-decoration:none; font-weight:bold;">Actualizar</a> | 
                    <a href="../scripts/eliminar_usuario.php?id=<?php echo $emp['id_empleado']; ?>&tipo=empleado" style="color: #e74c3c; text-decoration:none;" onclick="return confirm('¿Eliminar empleado?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>