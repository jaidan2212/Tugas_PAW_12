<?php
include 'db.php';

// Search keyword
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination setup
$limit = 5; // tampil 5 data per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Hitung total data
$countSql = "SELECT COUNT(*) AS total FROM students WHERE name LIKE '%$search%'";
$countResult = mysqli_query($conn, $countSql);
if (!$countResult) {
    die("SQL Count Error: " . mysqli_error($conn));
}
$countRow = mysqli_fetch_assoc($countResult);
$total = $countRow['total'];
$pages = ($total > 0) ? ceil($total / $limit) : 1;

// Ambil data dengan limit & search
$sql = "SELECT * FROM students WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("SQL Fetch Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student CRUD App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Student CRUD</a>
    <a href="add.php" class="btn btn-light">+ Add Student</a>
  </div>
</nav>

<!-- Content -->
<div class="container">
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>

      <?php if ($total == 0) { ?>
        <div class="alert alert-info">No students found.</div>
      <?php } else { ?>

      <table class="table table-bordered table-striped align-middle">
        <thead class="table-primary text-center">
          <tr>
            <th width="5%">No</th>
            <th width="20%">Name</th>
            <th width="25%">Email</th>
            <th width="20%">Course</th>
            <th width="15%">Photo</th>
            <th width="15%">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = $start + 1; // agar nomor berlanjut antar halaman
          while ($row = mysqli_fetch_assoc($result)) { 
          ?>
          <tr>
            <td class="text-center"><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['course']); ?></td>
            <td class="text-center">
              <?php if (!empty($row['photo']) && file_exists('uploads/' . $row['photo'])) { ?>
                <img src="uploads/<?php echo $row['photo']; ?>" width="80" class="rounded">
              <?php } else { ?>
                <span class="text-muted">No photo</span>
              <?php } ?>
            </td>
            <td class="text-center">
              <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Edit</a>
              <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <nav>
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $pages; $i++) { ?>
            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>">
                <?php echo $i; ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </nav>

      <?php } ?>
    </div>
  </div>
</div>

</body>
</html>
