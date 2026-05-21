<?php
session_start();
include 'db.php'; 

// 1. Verificación de permisos y consistencia de sesión
// IMPORTANTE: Verifica si usas 'id_usuario' o solo 'id' en tu login.php
$id_artista_sesion = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : (isset($_SESSION['id']) ? $_SESSION['id'] : null);

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'artista' || !$id_artista_sesion) {
    die("Acceso denegado: Sesión inválida o permisos insuficientes.");
}

if (isset($_GET['id'])) {
    $id_obra = (int)$_GET['id'];

    // 2. Consulta de verificación
    $query_verificar = "SELECT nombre, id_artista FROM obra WHERE id_obra = $id_obra";
    $res_verificar = mysqli_query($conexion, $query_verificar);

    // Verificamos si la consulta SQL tuvo éxito para evitar el Fatal Error de la imagen
    if (!$res_verificar) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($res_verificar) > 0) {
        $fila = mysqli_fetch_assoc($res_verificar);
        
        // 3. VALIDACIÓN DE SEGURIDAD REFORZADA
        // Usamos (string) para asegurar que la comparación no falle por tipo de dato
        if ((string)$fila['id_artista'] !== (string)$id_artista_sesion) {
            die("<script>alert('Error: El ID de la obra ({$fila['id_artista']}) no coincide con tu ID de sesión ($id_artista_sesion).'); window.location.href='../pages/index.php';</script>");
        }

        $nombre_archivo = $fila['nombre'];
        $ruta_completa = "../resources/" . $nombre_archivo;

        // 4. ELIMINACIÓN
        $sql_delete = "DELETE FROM obra WHERE id_obra = $id_obra";
        
        if (mysqli_query($conexion, $sql_delete)) {
            // Borrar el archivo físico si existe
            if (!empty($nombre_archivo) && file_exists($ruta_completa)) {
                unlink($ruta_completa);
            }
            echo "<script>alert('Obra eliminada con éxito.'); window.location.href='../pages/index.php';</script>";
        } else {
            echo "Error al eliminar en base de datos: " . mysqli_error($conexion);
        }
    } else {
        echo "<script>alert('La obra no existe en la base de datos.'); window.location.href='../pages/index.php';</script>";
    }
} else {
    header("Location: ../pages/index.php");
}
?>