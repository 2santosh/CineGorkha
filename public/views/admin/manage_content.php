<section class="manage-content">
    <h2>Manage Content</h2>
    
    <?php if ($stmt->rowCount() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Genre</th>
                    <th>Uploaded By</th>
                    <th>Uploaded On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['release_year']; ?></td>
                        <td><?php echo $row['genre']; ?></td>
                        <td><?php echo $row['uploaded_by']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
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
        <p>No content found.</p>
    <?php endif; ?>
</section>