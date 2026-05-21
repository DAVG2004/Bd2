
<?php
include '../scripts/db.php';

if (isset($_POST['registrar'])) {
    $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $tel = mysqli_real_escape_string($conexion, $_POST['telefono']); 
    $direc = mysqli_real_escape_string($conexion, $_POST['direccion']);
    $tcred = mysqli_real_escape_string($conexion, $_POST['tcredito']);
    $codigo_seg = rand(1000, 9999); // Generamos el código de 4 dígitos
    $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $p1 = $_POST['p1']; 
    $p2 = $_POST['p2']; 
    $p3 = $_POST['p3'];

    $sql = "INSERT INTO comprador (id_comprador, apellido, nombre, email, telefono, direccion, tcredito, cod_seguridad, usuario, password, p1, p2, p3) 
            VALUES ('$cedula', '$apellido', '$nombre', '$email', '$tel', '$direc', '$tcred', '$codigo_seg', '$user', '$pass', '$p1', '$p2', '$p3')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Tu código de seguridad es: $codigo_seg. GUÁRDALO BIEN.'); window.location='login.php';</script>";
    }
}
?>

<head>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<form method="POST">
    <h2>Registro de Comprador</h2>
    <input type="text" name="cedula" placeholder="Cédula" required>
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellido" placeholder="Apellido" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="telefono" placeholder="Telefono" required>
    <input type="text" name="direccion" placeholder="Direccion" required>
    <input type="number" name="tcredito" placeholder="ingrese los ultimos 4 digitos de su tarjeta de credito" required>
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="contraseña" required>
    
    <h3>Preguntas de Seguridad</h3>
    <input type="text" name="p1" placeholder="¿Nombre de tu primera mascota?" required>
    <input type="text" name="p2" placeholder="¿Ciudad de nacimiento?" required>
    <input type="text" name="p3" placeholder="¿Nombre de tu abuela?" required>
    
    <button type="submit" name="registrar">Registrarse y obtener código</button>
</form>
