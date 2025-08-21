<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MovieFlix</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Your custom CSS (always after Bootstrap for overrides) -->
    <link rel="stylesheet" type="text/css" href="assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <style>
        /* Specific styles for this page, complementing Bootstrap */
        .auth-container {
            max-width: 450px; /* Consistent width with login.php */
            background-color: #2a2a2a; /* Pulled from style.css */
            color: #f0f0f0;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        .auth-container h1 {
            color: #17c3ba; /* Pulled from style.css */
            margin-bottom: 25px;
            font-size: 2em;
            text-align: center;
        }
        .auth-form label {
            color: #bbb; /* Custom color for labels for better contrast */
        }
        /* Button styles handled by Bootstrap classes btn btn-primary */
    </style>
</head>
<body class="bg-dark text-light">

    <?php include 'views/components/header.php'; ?>

    <main class="container d-flex justify-content-center align-items-center my-5">
        <section class="auth-container p-4 rounded shadow-lg">
            <h1 class="mb-4">User Registration</h1>
            <?php
            // Display flash messages after registration attempt
            // $session object is available from index.php dispatch
            if ($flash = $session->getFlash('register_error')) {
                echo '<div class="alert alert-danger text-center" role="alert">' . htmlspecialchars($flash['message']) . '</div>';
            }
            if ($flash = $session->getFlash('register_success')) {
                echo '<div class="alert alert-success text-center" role="alert">' . htmlspecialchars($flash['message']) . '</div>';
            }
            ?>
            <form action="register" method="POST" class="auth-form">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
            </form>
            <p class="mt-3 text-center">Already have an account? <a href="login" class="text-info text-decoration-none">Login here</a></p>
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
