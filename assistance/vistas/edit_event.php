<?php
// edit_event.php
$host = 'localhost';
$db = 'asistencia';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $title = $data['title'];
    $start = $data['start'];
    $color = $data['color'];

    $stmt = $conn->prepare("UPDATE eventos SET title = :title, start = :start, color = :color WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':color', $color);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
