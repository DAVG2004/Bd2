<?php
session_start();
include '../scripts/db.php'; 

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'comprador') {
    die("Acceso denegado.");
}

if (isset($_GET['id_obra']) && isset($_GET['id_empleado'])) {
    $id_obra = (int)$_GET['id_obra'];
    $id_empleado = (int)$_GET['id_empleado'];
    $id_comprador = $_SESSION['id'];
    $fecha_actual = date("Y-m-d H:i:s");

    // Verificar que el asesor sea válido
    $check_emp = mysqli_query($conexion, "SELECT rol FROM empleado WHERE id_empleado = $id_empleado");
    $datos_emp = mysqli_fetch_assoc($check_emp);

    if (!$datos_emp || strtolower($datos_emp['rol']) === 'administrador') {
        echo "<script>alert('Asesor no válido.'); window.location.href='index.php';</script>";
        exit();
    }

    // NUEVA CONSULTA: Obtenemos el precio de la obra y la tarifa_museo desde la tabla artista
    $query_obra = "SELECT o.precio, o.status, a.tarifa_museo 
                   FROM obra o 
                   JOIN artista a ON o.id_artista = a.id_artista 
                   WHERE o.id_obra = $id_obra";
    
    $res_obra = mysqli_query($conexion, $query_obra);
    $datos_obra = mysqli_fetch_assoc($res_obra);

    if ($datos_obra && strtolower($datos_obra['status']) === 'disponible') {
        $precio = $datos_obra['precio'];
        $iva = $precio * 0.16; 
        
        // TARIFA DINÁMICA: Se toma el valor de la tabla artista
        $porcentaje_artista = $datos_obra['tarifa_museo']; 
        $tarifa_calculada = $precio * $porcentaje_artista; 
        
        $total = $precio + $iva;

        // Registro en la tabla 'venta' con la tarifa dinámica obtenida
        $sql_venta = "INSERT INTO venta (id_comprador, id_obra, id_empleado, fecha, tipo_de_pago, precio_venta, IVA, tarifa_museo, total) 
                      VALUES ($id_comprador, $id_obra, $id_empleado, '$fecha_actual', 'Tarjeta de Crédito', $precio, $iva, $tarifa_calculada, $total)";

        if (mysqli_query($conexion, $sql_venta)) {
            mysqli_query($conexion, "UPDATE obra SET status = 'vendida' WHERE id_obra = $id_obra");
            echo "<script>alert('Compra exitosa. Tarifa aplicada: " . ($porcentaje_artista * 100) . "%'); window.location.href='index.php';</script>";
        }
    }
}
?>