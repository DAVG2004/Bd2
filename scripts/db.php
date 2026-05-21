<?php
// Configuración de los parámetros de conexión
$host     = "localhost";    // El servidor siempre es localhost en XAMPP
$user     = "root";         // Usuario por defecto de XAMPP
$password = "";             // Por defecto, XAMPP no tiene contraseña
$db_name  = "galeria_db";   // El nombre que le pusiste a tu BD en phpMyAdmin

// 1. Crear la conexión
$conexion = mysqli_connect($host, $user, $password, $db_name);

// 2. Verificar si la conexión falló
if (!$conexion) {
    die("Error crítico: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}

// 3. Ajustar el conjunto de caracteres a UTF-8 (importante para tildes y ñ)
mysqli_set_charset($conexion, "utf8");

// Si llegamos aquí, la conexión fue exitosa
?>