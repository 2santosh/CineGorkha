<section class="search-results">
    <h2>Search Results for "<?php echo htmlspecialchars($_GET['q']); ?>"</h2>
    
    <?php if ($stmt->rowCount() > 0): ?>
        <div class="movie-grid">
            <div class="grid">
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="movie-card">
                        <a href="/movie?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo $row['thumbnail']; ?>" alt="<?php echo $row['title']; ?>">
                            <div class="movie-info">
                                <h3><?php echo $row['title']; ?></h3>
                                <p><?php echo $row['release_year']; ?> • <?php echo $row['genre']; ?></p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else: ?>
        <p>No movies found matching your search.</p>
    <?php endif; ?>
</section>