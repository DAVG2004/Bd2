<?php
include '../scripts/db.php';

if (isset($_POST['registrar_empleado'])) {
    $id_empleado = mysqli_real_escape_string($conexion, $_POST['id_empleado']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $salario = $_POST['salario'];
    $puesto = mysqli_real_escape_string($conexion, $_POST['puesto']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    
    // Encriptación de contraseña
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO empleado (id_empleado, nombre, apellido, telefono, salario, puesto, usuario, password, fecha_registro) 
            VALUES ('$id_empleado', '$nombre', '$apellido', '$telefono', '$salario', '$puesto', '$usuario', '$password', NOW())";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Empleado registrado con éxito'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Empleado</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Registro de Personal</h2>
        <form method="POST">
            <input type="text" name="id_empleado" placeholder="Cédula de Identidad (CI)" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="number" step="0.01" name="salario" placeholder="Salario Mensual" required>
            
            <label>Puesto / Rol:</label>
            <select name="puesto" required>
                <option value="vendedor">Vendedor</option>
                <option value="guia">Guía</option>
                <option value="admin">Administrador</option>
            </select>

            <input type="text" name="usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            
            <button type="submit" name="registrar_empleado">Registrar Empleado</button>
        </form>
        <p><a href="login.php">Volver al Login</a></p>
    </div>
</body>
</html>