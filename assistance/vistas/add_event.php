<?php
$host = 'localhost'; // o el host donde se encuentre tu base de datos
$db = 'asistencia';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $start = $_POST['start'];
        $color = $_POST['color'];

        $stmt = $pdo->prepare('INSERT INTO eventos (title, start, color) VALUES (?, ?, ?)');
        $stmt->execute([$title, $start, $color]);

        header('Location: calendar.php');
        exit();
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
