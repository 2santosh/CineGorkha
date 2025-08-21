<section class="manage-movies">
    <h2>Manage Movies</h2>
    
    <?php if ($stmt->rowCount() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Genre</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['release_year']; ?></td>
                        <td><?php echo $row['genre']; ?></td>
                        <td>
                            <a href="/movie?id=<?php echo $row['id']; ?>">View</a>
                            <a href="#">Edit</a>
                            <a href="#">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No movies uploaded yet.</p>
    <?php endif; ?>
</section>