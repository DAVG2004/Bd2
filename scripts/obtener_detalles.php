<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id_obra = (int)$_GET['id'];
    
    // Obtener información básica de la obra y el artista
    $query_base = "SELECT o.id_obra, o.nombre as archivo, o.precio, o.status, o.fecha_publicacion,
                          a.nombre as artista_nombre, a.apellido as artista_apellido, a.nacionalidad, 
                          g.nombre as genero, g.ID as id_genero
                   FROM obra o
                   JOIN artista a ON o.id_artista = a.id_artista
                   JOIN genero g ON o.id_genero = g.ID
                   WHERE o.id_obra = $id_obra";
                   
    $res_base = mysqli_query($conexion, $query_base);
    
    if ($res_base && $obra = mysqli_fetch_assoc($res_base)) {
        
        // Limpiar nombre
        $partes = explode('_', $obra['archivo']);
        $obra['tituloLimpio'] = pathinfo(end($partes), PATHINFO_FILENAME);
        $obra['ruta_imagen'] = "../resources/" . $obra['archivo'];
        
        $detalles_extra = [];
        $id_genero = $obra['id_genero'];
        
        // Consultar según el género para detalles técnicos
        switch($id_genero) {
            case 1: // Pintura
                $q = "SELECT tecnica, soporte, alto, ancho FROM pintura WHERE id_pintura = $id_obra";
                break;
            case 2: // Escultura
                $q = "SELECT material, peso, alto, ancho FROM escultura WHERE id_escultura = $id_obra";
                break;
            case 3: // Fotografía
                $q = "SELECT tecnica, papel, alto, ancho FROM fotografia WHERE id_fotografia = $id_obra";
                break;
            case 4: // Ceramica
                $q = "SELECT arcilla as material_base, tecnica, peso, alto, ancho FROM ceramica WHERE id_ceramica = $id_obra";
                break;
            case 5: // Orfebreria
                $q = "SELECT material, tecnica, peso FROM orferbreria WHERE id_orfebreria = $id_obra";
                break;
            default:
                $q = "";
        }
        
        if ($q !== "") {
            $res_extra = mysqli_query($conexion, $q);
            if ($res_extra && $extra = mysqli_fetch_assoc($res_extra)) {
                $detalles_extra = $extra;
            }
        }
        
        $obra['detalles_tecnicos'] = $detalles_extra;
        
        echo json_encode(['status' => 'success', 'data' => $obra]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Obra no encontrada']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
}
?>
