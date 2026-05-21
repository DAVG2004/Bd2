<?php
include '../scripts/db.php';

if (isset($_POST['registrar'])) {
    $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $tel = mysqli_real_escape_string($conexion, $_POST['telefono']); 
    $nac = mysqli_real_escape_string($conexion, $_POST['nacionalidad']); 
    $fnac = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']); 
    $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Foto de perfil
    $foto_nombre = $_FILES['foto']['name'];
    $foto_temp = $_FILES['foto']['tmp_name'];
    $ruta_destino = "../resources/perfiles/" . uniqid() . "_" . $foto_nombre;

    if (move_uploaded_file($foto_temp, $ruta_destino)) {
        // Asegúrate de que los nombres de las columnas coincidan 
        $sql = "INSERT INTO artista (id_artista, nombre, apellido, email, telefono, nacionalidad, fecha_nacimiento, usuario, password, foto_perfil) 
                VALUES ('$cedula', '$nombre', '$apellido', '$email', '$tel', '$nac', '$fnac','$user', '$pass', '$ruta_destino')";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: login.php?success=1");
        } else {
            echo "Error en SQL: " . mysqli_error($conexion);
        }
    } else {
        echo "Error al subir la foto de perfil.";
    }
}
?>

<head>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<form method="POST" enctype="multipart/form-data" class="form-grid">
    <h2>Registro de Artista</h2>
    <input type="text" name="cedula" placeholder="Cédula" required>
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellido" placeholder="Apellido" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="telefono" placeholder="Telefono" required>
    <input type="text" name="nacionalidad" placeholder="Nacionalidad" required>
    <input type="date" name="fecha_nacimiento" placeholder="Fecha nacimiento" required>
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <label>Foto de Perfil:</label>
    <input type="file" name="foto" required>
    <button type="submit" name="registrar">Crear Cuenta de Artista</button>
</form>