<section class="movie-details">
    <div class="movie-poster">
        <img src="<?php echo $movie->thumbnail; ?>" alt="<?php echo $movie->title; ?>">
    </div>
    <div class="movie-info">
        <h1><?php echo $movie->title; ?> (<?php echo $movie->release_year; ?>)</h1>
        <p class="movie-meta"><?php echo $movie->duration; ?> min • <?php echo $movie->genre; ?></p>
        <p class="movie-director">Director: <?php echo $movie->director; ?></p>
        <p class="movie-cast">Cast: <?php echo $movie->cast; ?></p>
        <p class="movie-description"><?php echo $movie->description; ?></p>
        
        <div class="movie-player">
            <video controls>
                <source src="<?php echo $movie->video_url; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</section>