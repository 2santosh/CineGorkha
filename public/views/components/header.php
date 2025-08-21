<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include configuration
require_once __DIR__ . '/../../../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-brand">
                <a href="<?php echo SITE_URL; ?>"><?php echo SITE_NAME; ?></a>
            </div>
            <div class="nav-menu">
                <a href="<?php echo SITE_URL; ?>">Home</a>
                <a href="<?php echo SITE_URL; ?>search">Search</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'uploader' || $_SESSION['user_role'] === 'admin')): ?>
                        <a href="<?php echo SITE_URL; ?>upload">Upload</a>
                        <a href="<?php echo SITE_URL; ?>manage">Manage</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="<?php echo SITE_URL; ?>admin">Admin</a>
                    <?php endif; ?>
                    <a href="<?php echo SITE_URL; ?>logout">Logout (<?php echo $_SESSION['username']; ?>)</a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>login">Login</a>
                    <a href="<?php echo SITE_URL; ?>register">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    <main>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>