<?php
// delete_event.php
$host = 'localhost';
$db = 'asistencia';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $stmt = $conn->prepare("DELETE FROM eventos WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
