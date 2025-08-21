<section class="hero">
    <div class="hero-content">
        <h1>Welcome to <?php echo SITE_NAME; ?></h1>
        <p>Discover the best Nepali movies</p>
        <form action="<?php echo SITE_URL; ?>search" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search movies..." required>
            <button type="submit">Search</button>
        </form>
    </div>
</section>

<section class="movie-grid">
    <h2>Latest Movies</h2>
    <div class="grid">
        <?php 
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="movie-card">
                    <a href="<?php echo SITE_URL; ?>movie?id=<?php echo $row['id']; ?>">
                        <img src="<?php echo SITE_URL . $row['thumbnail']; ?>" alt="<?php echo $row['title']; ?>">
                        <div class="movie-info">
                            <h3><?php echo $row['title']; ?></h3>
                            <p><?php echo $row['release_year']; ?> • <?php echo $row['genre']; ?></p>
                        </div>
                    </a>
                </div>
            <?php endwhile;
        } else {
            echo '<p>No movies found.</p>';
        }
        ?>
    </div>
</section>