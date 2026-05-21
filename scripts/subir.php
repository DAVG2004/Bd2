<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'artista') {
    die("Acceso denegado.");
}

if (isset($_POST['enviar'])) {
    $id_artista = $_SESSION['id'];
    $titulo_obra = mysqli_real_escape_string($conexion, $_POST['nombre_obra']);
    $precio = $_POST['precio'];
    $id_genero = $_POST['id_genero']; // Asegúrate que en la tabla genero: 1=Pintura, 2=Orfebreria, etc.
    
    // 1. GESTIÓN DE LA IMAGEN
    $extension = pathinfo($_FILES['foto_obra']['name'], PATHINFO_EXTENSION);
    $nombre_archivo_db = time() . "_" . $titulo_obra . "." . $extension; 
    $ruta_destino = "../resources/" . $nombre_archivo_db;

    if (!move_uploaded_file($_FILES['foto_obra']['tmp_name'], $ruta_destino)) {
        die("Error al subir el archivo físico.");
    }

    // 2. INICIAR TRANSACCIÓN PARA EVITAR DATOS HUÉRFANOS
    mysqli_begin_transaction($conexion);

    try {
        // A. INSERTAR EN TABLA OBRA
        // Nota: Asegúrate que las columnas sean exactamente: id_artista, id_genero, nombre, precio, status, fecha_publicacion
        $query_obra = "INSERT INTO obra (id_artista, id_genero, nombre, precio, status, fecha_publicacion) 
                        VALUES ('$id_artista', '$id_genero', '$nombre_archivo_db', '$precio', 'disponible', NOW())";
        
        if (!mysqli_query($conexion, $query_obra)) {
            throw new Exception("Error en tabla Obra: " . mysqli_error($conexion));
        }
        
        $id_generado = mysqli_insert_id($conexion); // Este es el ID que vincula todo

        // B. INSERTAR EN TABLA ESPECIALIZADA
        $sql_esp = "";

        switch ($id_genero) {
            case "1": // PINTURA
                $tecnica = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $soporte = mysqli_real_escape_string($conexion, $_POST['soporte']);
                $alto = !empty($_POST['alto']) ? $_POST['alto'] : 0;
                $ancho = !empty($_POST['ancho']) ? $_POST['ancho'] : 0;
                $sql_esp = "INSERT INTO pintura (id_pintura, tecnica, soporte, alto, ancho) 
                            VALUES ('$id_generado', '$tecnica', '$soporte', '$alto', '$ancho')";
                break;

            case "2": // ESCULTURA
                $material = mysqli_real_escape_string($conexion, $_POST['material']);
                $peso = !empty($_POST['peso']) ? $_POST['peso'] : 0;
                $alto = !empty($_POST['alto']) ? $_POST['alto'] : 0;
                $ancho = !empty($_POST['ancho']) ? $_POST['ancho'] : 0;
                $sql_esp = "INSERT INTO escultura (id_escultura, material, peso, alto, ancho) 
                            VALUES ('$id_generado', '$material', '$peso', '$alto', '$ancho')";
                break;

            case "3": // FOTOGRAFIA
                $tecnica = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $papel = mysqli_real_escape_string($conexion, $_POST['papel']);
                $alto = !empty($_POST['alto']) ? $_POST['alto'] : 0;
                $ancho = !empty($_POST['ancho']) ? $_POST['ancho'] : 0;
                $sql_esp = "INSERT INTO fotografia (id_fotografia, tecnica, papel, alto, ancho) 
                            VALUES ('$id_generado', '$tecnica', '$papel', '$alto', '$ancho')";
                break;

            case "4": // CERAMICA
                $arcilla = mysqli_real_escape_string($conexion, $_POST['arcilla']);
                $tecnica = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $peso = !empty($_POST['peso']) ? $_POST['peso'] : 0;
                $alto = !empty($_POST['alto']) ? $_POST['alto'] : 0;
                $ancho = !empty($_POST['ancho']) ? $_POST['ancho'] : 0;
                $sql_esp = "INSERT INTO ceramica (id_ceramica, arcilla, tecnica, peso, alto, ancho) 
                            VALUES ('$id_generado', '$arcilla', '$tecnica', '$peso', '$alto', '$ancho')";
                break;

            case "5": // ORFEBRERIA
                $material = mysqli_real_escape_string($conexion, $_POST['material']);
                $tecnica = mysqli_real_escape_string($conexion, $_POST['tecnica']);
                $peso = !empty($_POST['peso']) ? $_POST['peso'] : 0;
                $sql_esp = "INSERT INTO orferbreria (id_orfebreria, material, tecnica, peso) 
                            VALUES ('$id_generado', '$material', '$tecnica', '$peso')";
                break;
        }

        if ($sql_esp != "") {
            if (!mysqli_query($conexion, $sql_esp)) {
                throw new Exception("Error en tabla técnica ($id_genero): " . mysqli_error($conexion));
            }
        }

        mysqli_commit($conexion);
        header("Location: ../pages/index.php?status=success");

    } catch (Exception $e) {
        mysqli_rollback($conexion);
        if (file_exists($ruta_destino)) unlink($ruta_destino); // Borrar foto si falló la DB
        die("Error crítico: " . $e->getMessage());
    }
}