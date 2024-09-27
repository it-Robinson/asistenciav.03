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

// Obtener datos de los usuarios con JOIN para departamentos y tipos de usuario
$sql = "SELECT u.nombre, u.apellidos, u.fecha_nacimiento, u.iddepartamento, u.idtipousuario, u.imagen, u.empresa, d.nombre as departamento_nombre, t.nombre as tipousuario_nombre 
        FROM usuarios u
        LEFT JOIN departamento d ON u.iddepartamento = d.iddepartamento
        LEFT JOIN tipousuario t ON u.idtipousuario = t.idtipousuario";

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
