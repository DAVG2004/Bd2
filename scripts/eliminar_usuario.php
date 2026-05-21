<?php
session_start();
include 'db.php';

// SEGURIDAD: Solo el admin puede borrar
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    die("No tienes permiso para realizar esta acción.");
}

if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = (int)$_GET['id'];
    $tipo = $_GET['tipo'];

    // Definir tabla y columna según el tipo
    switch ($tipo) {
        case 'artista':
            $tabla = 'artista';
            $columna = 'id_artista';
            break;
        case 'empleado':
            $tabla = 'empleado';
            $columna = 'id_empleado';
            break;
        case 'comprador':
            $tabla = 'comprador';
            $columna = 'id_comprador';
            break;
        case 'obra':
    $tabla = 'obra';
    $columna = 'id_obra';
    break;


        default:
            die("Tipo de usuario no válido.");
    }

    $sql = "DELETE FROM $tabla WHERE $columna = $id";

    if (mysqli_query($conexion, $sql)) {
        header("Location: ../admin/admin_panel.php?view=usuarios&msg=eliminado");
    } else {
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
}
?>