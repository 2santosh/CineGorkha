<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin - MovieFlix</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Your custom CSS (after Bootstrap for overrides) -->
    <link rel="stylesheet" type="text/css" href="assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

    <?php include 'views/components/header.php'; ?>

    <main class="container my-5">
        <section class="edit-container p-4 rounded shadow-lg bg-dark text-light mx-auto">
            <h1 class="text-center mb-4 text-danger">Edit User: <?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></h1>
            <?php
            // Display flash messages from the controller
            if ($flash = $session->getFlash('admin_message')) {
                // Assuming $session->getFlash returns ['message' => '...', 'status' => 'success/error']
                $alertClass = ($flash['status'] ?? '') === 'success' ? 'alert-success' : 'alert-danger';
                echo '<div class="alert ' . htmlspecialchars($alertClass) . ' text-center" role="alert">' . htmlspecialchars($flash['message']) . '</div>';
            }
            ?>
            <form action="/admin/users/edit/<?php echo htmlspecialchars($user['id'] ?? ''); ?>" method="POST" class="edit-form">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label">Role:</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="user" <?php echo (($user['role'] ?? '') == 'user' ? 'selected' : ''); ?>>User</option>
                        <option value="uploader" <?php echo (($user['role'] ?? '') == 'uploader' ? 'selected' : ''); ?>>Uploader</option>
                        <option value="admin" <?php echo (($user['role'] ?? '') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Update User</button>
            </form>
            <div class="text-center mt-3">
                <a href="/admin/users" class="text-info text-decoration-none">Back to User Management</a>
            </div>
        </section>
    </main>

    <?php include 'views/components/footer.php'; ?>

    <!-- Bootstrap JS (Popper.js first, then Bootstrap's bundle) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" xintegrity="sha384-I7E8VVD/ismYTF4hNIPjVpZVxpLtGnMqBCxpJtPMV/xuf9NnK/N/P92g/4rKj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" xintegrity="sha384-0pUGZvbkm6XF6gxjEnlco9W9vFhG6A716S0j5g2I1Q4w2F+4W2a5I1h4x6d9l4x6" crossorigin="anonymous"></script>
    <!-- Your custom JavaScript files -->
    <script src="assets/js/dropdown.js"></script>
</body>
</html>
