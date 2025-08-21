<?php
// Include configuration
require_once __DIR__ . '/../config/config.php';

// Start session
require_once __DIR__ . '/../core/Session.php';
$session = new Session();

// Include header
require_once __DIR__ . '/components/header.php';
?>

<section class="error-page">
    <div class="error-content">
        <h1>404 - Page Not Found</h1>
        <p>The page you are looking for does not exist.</p>
        <a href="<?php echo SITE_URL; ?>" class="btn">Go Home</a>
    </div>
</section>

<?php
// Include footer
require_once __DIR__ . '/components/footer.php';
?>