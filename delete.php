<?php
include 'db.php';

$id = $_GET['id'];

$sql = "SELECT photo FROM students WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$photoPath = 'uploads/' . $row['photo'];

if (file_exists($photoPath)) {
    unlink($photoPath);
}

$deleteSql = "DELETE FROM students WHERE id = $id";
if (mysqli_query($conn, $deleteSql)) {
    header('Location: index.php');
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
?>