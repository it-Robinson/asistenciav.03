<?php
// getUsers.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asistencia";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener datos de los usuarios
$sql = "SELECT nombre, fecha_nacimiento FROM usuarios";
$result = $conn->query($sql);

$usuarios = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

$conn->close();

// Devolver datos en formato JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
?>
