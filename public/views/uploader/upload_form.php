<section class="upload-form">
    <h2>Upload Movie</h2>
    <form method="POST" action="/upload/process" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="release_year">Release Year</label>
            <input type="number" id="release_year" name="release_year" min="1900" max="<?php echo date('Y'); ?>" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (minutes)</label>
            <input type="number" id="duration" name="duration" min="1" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre</label>
            <input type="text" id="genre" name="genre" required>
        </div>
        <div class="form-group">
            <label for="director">Director</label>
            <input type="text" id="director" name="director" required>
        </div>
        <div class="form-group">
            <label for="cast">Cast</label>
            <input type="text" id="cast" name="cast" required>
        </div>
        <div class="form-group">
            <label for="thumbnail">Thumbnail</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="video">Video</label>
            <input type="file" id="video" name="video" accept="video/*" required>
        </div>
        <button type="submit">Upload</button>
    </form>
</section>