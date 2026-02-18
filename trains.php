<?php
// Add new train
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO trains (...) VALUES (...)");
    $stmt->bind_param("sssssi", ...);
    $stmt->execute();
}
?>

<!-- Train Management Form -->
<form method="post">
    <input type="text" name="train_name" placeholder="Train Name" required>
    <input type="text" name="from_station" placeholder="From" required>
    <input type="text" name="to_station" placeholder="To" required>
    <input type="time" name="departure" required>
    <input type="number" name="seats" min="1" required>
    <button type="submit">Add Train</button>
</form>

<!-- Trains List -->
<table class="table">
    <tr>
        <th>Train</th>
        <th>Route</th>
        <th>Departure</th>
        <th>Actions</th>
    </tr>
    <?php while($train = $trains->fetch_assoc()): ?>
    <tr>
        <td><?= $train['name'] ?></td>
        <td><?= $train['from_station'] ?> to <?= $train['to_station'] ?></td>
        <td><?= $train['departure_time'] ?></td>
        <td>
            <a href="?edit=<?= $train['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="?delete=<?= $train['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>