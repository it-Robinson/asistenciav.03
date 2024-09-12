<?php
// get_birthdays.php
$host = 'localhost';
$db = 'asistencia';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT idusuario, nombre, fecha_nacimiento FROM usuarios");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $currentYear = date('Y');
    $birthdays = [];

    foreach ($usuarios as $usuario) {
        $birthDate = new DateTime($usuario['fecha_nacimiento']);
        $birthdayThisYear = $birthDate->setDate($currentYear, $birthDate->format('m'), $birthDate->format('d'))->format('Y-m-d');
        
        $birthdays[] = [
            'title' => $usuario['nombre'] . ' - Cumpleaños',
            'start' => $birthdayThisYear,
            'color' => '#ffcc00' // Color de cumpleaños
        ];
    }

    echo json_encode($birthdays);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
