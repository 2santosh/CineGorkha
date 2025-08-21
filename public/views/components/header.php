<?php
// We'll include the header.css file from the public/assets/css directory
// Note: The path is relative to the location where this component is included (e.g., in index.php)
?>
<link rel="stylesheet" type="text/css" href="assets/css/header.css">

<div class="header_main">
    <div class="navBar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#browse">Browse</a></li>
            <li><a href="index.php#genres">Genres</a></li>
            <li><a href="index.php#latest">Latest Movies</a></li>
            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">Login</button>
                <div class="dropdown-content" id="dropdown">
                    <a href="admin/admin_dashboard.php">Admin</a>
                    <a href="uploader/upload_form.php">Uploader</a>
                    <a href="auth/login.php">User</a>
                </div>
            </div>
        </ul>
    </div>
</div>

<a href="index.php">
    <div class="logo-name">
        <div class="logo">
            <img class="logo_img" src="assets/images/logo.png" alt="Movie Website Logo">
        </div>
        <div class="name">
            <h5>MovieFlix</h5>
            <h6>Your ultimate movie destination</h6>
        </div>
    </div>
</a>

<div class="movie_categories">
    <div class="category">
        <a href="#">Action</a>
    </div>
    <div class="category">
        <a href="#">Comedy</a>
    </div>
    <div class="category">
        <a href="#">Sci-Fi</a>
    </div>
    <div class="category">
        <a href="#">Thriller</a>
    </div>
</div>

<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("dropdown");
        dropdown.classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>