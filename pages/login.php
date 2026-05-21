<?php
session_start();
include '../scripts/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $pass = $_POST['password'];

    // Mapeo de tablas y sus columnas de ID correspondientes
    $tablas = [
        'artista' => 'id_artista',
        'comprador' => 'id_comprador',
        'empleado' => 'id_empleado' 
    ];

    foreach ($tablas as $tabla => $id_col) {
        $query = "SELECT * FROM $tabla WHERE usuario = '$user'";
        $res = mysqli_query($conexion, $query);
        
        if ($fila = mysqli_fetch_assoc($res)) {
            // IMPORTANTE: Cambia 'password_verify' por ($pass == $fila['password']) 
            // si no usas encriptación en tu base de datos.
            if (password_verify($pass, $fila['password']) || $pass == $fila['password']) {
                
                // Guardamos los datos genéricos en la sesión
                $_SESSION['id'] = $fila[$id_col];
                $_SESSION['nombre'] = $fila['nombre'];

                // Si el usuario es un empleado, asignamos su rol específico (admin o encargado)
                if ($tabla == 'empleado') {
                    // Usamos la columna 'rol' que agregamos a la tabla empleado
                    $_SESSION['rol'] = strtolower($fila['rol']); 
                } else {
                    $_SESSION['rol'] = $tabla;
                }
                
                header("Location: index.php"); 
                exit;
            }
        }
    }
    $error = "Usuario o contraseña incorrectos.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Galería de Arte</title>
    <link rel="stylesheet" href="../styles/login.css">
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Iniciar Sesión</h2>
        <?php if(isset($error)) echo "<p class='error' style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        <p>¿Eres nuevo? Regístrate como <a href="registro_artista.php">Artista</a> o <a href="registro_comprador.php">Comprador</a></p>
    </div>
</body>
</html>