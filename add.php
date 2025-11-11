<?php
include 'db.php';

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);

    // Pastikan folder uploads ada
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Ambil info file foto
    $photo = $_FILES['photo']['name'];
    $tmpName = $_FILES['photo']['tmp_name'];
    $target = $uploadDir . basename($photo);

    // Validasi file sebelum upload
    if (!empty($photo)) {
        if (move_uploaded_file($tmpName, $target)) {
            // Query simpan ke database
            $sql = "INSERT INTO students (name, email, course, photo)
                    VALUES ('$name', '$email', '$course', '$photo')";

            if (mysqli_query($conn, $sql)) {
                // Redirect ke index setelah sukses
                header('Location: index.php');
                exit;
            } else {
                echo "<div class='alert alert-danger m-3'>SQL Error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning m-3'>Photo upload failed!</div>";
        }
    } else {
        echo "<div class='alert alert-warning m-3'>Please select a photo to upload.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Student CRUD</a>
  </div>
</nav>

<div class="container">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="mb-4 text-primary">Add New Student</h3>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-semibold">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Course</label>
          <input type="text" name="course" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Photo</label>
          <input type="file" name="photo" class="form-control" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
