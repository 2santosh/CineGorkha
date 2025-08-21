<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - MovieFlix Uploader</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Your custom CSS (after Bootstrap for overrides) -->
    <link rel="stylesheet" type="text/css" href="assets/css/header.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <style>
        .edit-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background-color: #2a2a2a;
            color: #f0f0f0;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        .edit-container h1 {
            color: #17c3ba;
            margin-bottom: 25px;
            font-size: 2.2em;
            text-align: center;
        }
        .edit-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #bbb;
        }
        .edit-form input[type="text"],
        .edit-form input[type="number"],
        .edit-form textarea,
        .edit-form select,
        .edit-form input[type="file"] {
            width: calc(100% - 22px); /* Account for padding/border */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: #f0f0f0;
        }
        .edit-form textarea {
            resize: vertical;
            min-height: 100px;
        }
        .edit-form input[type="file"] {
            border: 2px dashed #005f7d;
            background-color: #333;
            color: #fff;
            padding: 15px;
        }
        .edit-form .current-file {
            margin-top: -15px;
            margin-bottom: 15px;
            font-size: 0.9em;
            color: #ccc;
        }
        .edit-form button[type="submit"] {
            background-color: #e50914; /* Netflix red */
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        .edit-form button[type="submit"]:hover {
            background-color: #c40812;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.9em;
            text-align: center;
        }
        .message.success {
            background-color: #3cb37140;
            color: #3cb371;
            border: 1px solid #3cb371;
        }
        .message.error {
            background-color: #ff634740;
            color: #ff6347;
            border: 1px solid #ff6347;
        }
    </style>
</head>
<body>

    <?php include 'views/components/header.php'; ?>

    <main>
        <section class="edit-container">
            <h1>Edit Movie: <?php echo htmlspecialchars($movie['title'] ?? 'N/A'); ?></h1>
            <?php
            // Display flash messages from the controller
            if ($flash = $session->getFlash('manage_message')) {
                echo '<div class="message ' . htmlspecialchars($session->getFlash('manage_status')['status'] ?? '') . '">' . htmlspecialchars($flash['message']) . '</div>';
            }
            ?>
            <form action="/uploader/edit/<?php echo htmlspecialchars($movie['id'] ?? ''); ?>" method="POST" enctype="multipart/form-data" class="edit-form">
                <label for="title">Movie Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($movie['title'] ?? ''); ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($movie['description'] ?? ''); ?></textarea>

                <label for="release_year">Release Year:</label>
                <input type="number" id="release_year" name="release_year" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($movie['release_year'] ?? ''); ?>" required>

                <label for="director">Director:</label>
                <input type="text" id="director" name="director" value="<?php echo htmlspecialchars($movie['director'] ?? ''); ?>">

                <label for="genre">Genre:</label>
                <select id="genre" name="genre" required>
                    <option value="">Select a Genre</option>
                    <?php
                    // Example genres; in a real app, you might fetch these from DB or config
                    $genres = ['Action', 'Comedy', 'Drama', 'Sci-Fi', 'Thriller', 'Horror', 'Animation', 'Documentary'];
                    foreach ($genres as $g) {
                        $selected = ($movie['genre'] ?? '') == $g ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($g) . '" ' . $selected . '>' . htmlspecialchars($g) . '</option>';
                    }
                    ?>
                </select>

                <label for="duration">Duration (minutes):</label>
                <input type="number" id="duration" name="duration" min="1" value="<?php echo htmlspecialchars($movie['duration'] ?? ''); ?>" required>

                <label for="poster">Movie Poster (Leave blank to keep current):</label>
                <?php if (!empty($movie['poster_path'])): ?>
                    <p class="current-file">Current: <a href="<?php echo htmlspecialchars($movie['poster_path']); ?>" target="_blank"><?php echo basename($movie['poster_path']); ?></a></p>
                    <img src="<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="Current Poster" style="max-width: 150px; height: auto; margin-bottom: 15px; border-radius: 5px;">
                <?php endif; ?>
                <input type="file" id="poster" name="poster" accept="image/*">


                <label for="video_file">Movie Video File (Leave blank to keep current):</label>
                <?php if (!empty($movie['video_path'])): ?>
                    <p class="current-file">Current: <a href="<?php echo htmlspecialchars($movie['video_path']); ?>" target="_blank"><?php echo basename($movie['video_path']); ?></a></p>
                <?php endif; ?>
                <input type="file" id="video_file" name="video_file" accept="video/mp4,video/webm">

                <button type="submit">Update Movie</button>
            </form>
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
