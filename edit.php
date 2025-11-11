<?php
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM students WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    if ($_FILES['photo']['name'] != '') {
        $photo = $_FILES['photo']['name'];
        $target = 'uploads/' . basename($photo);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $updatePhoto = ", photo='$photo'";
    } else {
        $updatePhoto = '';
    }

    $updateSql = "UPDATE students SET name='$name', email='$email', course='$course' $updatePhoto WHERE id=$id";

    if (mysqli_query($conn, $updateSql)) {
        header('Location: index.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="mb-4">Edit Student</h3>
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Course</label>
          <input type="text" name="course" class="form-control" value="<?php echo $row['course']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Current Photo</label><br>
          <img src="uploads/<?php echo $row['photo']; ?>" width="100" class="rounded mb-2"><br>
          <input type="file" name="photo" class="form-control">
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
