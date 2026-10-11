<?php 
include 'dbcon.php'; 

if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, created_at) VALUES (?, ?, NOW())");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $name, $email);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php?status=added");
                exit();
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP and MYSQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header class="d-flex align-items-center justify-content-center m-5">
        <h1 class="fw-bold">User Management System</h1>
    </header>

    <div class="container">
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'added'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    New user has been successfully added!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['status'] == 'updated'): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    User information has been updated!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['status'] == 'deleted'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    User has been deleted!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row g-5">
            <div class="col-md-6 shadow-sm p-4 bg-white rounded">
                <h4>Add New User</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100 py-2">Submit</button>
                </form>
            </div>

            <div class="col-md-6 shadow-sm p-4 bg-white rounded">
                <h4>Registered Users</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="fw-bold">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $fetch_query = "SELECT * FROM users ORDER BY id DESC";
                            $result = mysqli_query($conn, $fetch_query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr> 
                                            <td>" . htmlspecialchars($row['id']) . "</td> 
                                            <td>" . htmlspecialchars($row['name']) . "</td> 
                                            <td>" . htmlspecialchars($row['email']) . "</td> 
                                            <td>" . htmlspecialchars($row['created_at']) . "</td> 
                                            <td> 
                                                <a href='edit.php?id=" . (int)$row['id'] . "' class='btn btn-sm btn-warning'>Edit</a> 
                                                <a href='delete.php?id=" . (int)$row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a> 
                                            </td> 
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No registered users found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
