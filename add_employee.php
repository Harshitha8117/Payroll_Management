<?php require 'db.php'; ?>

<!DOCTYPE html>
<html>
<head><title>Add Employee</title></head>
<body>
    <h2>Add Employee</h2>
    <form method="POST">
        Name: <input type="text" name="name" required><br>
        Salary: <input type="number" name="salary" required><br>
        <button type="submit">Add</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST['name'];
        $salary = $_POST['salary'];

        $stmt = $pdo->prepare("INSERT INTO employees (name, salary) VALUES (?, ?)");
        $stmt->execute([$name, $salary]);

        echo "<p>Employee added.</p>";
    }
    ?>
</body>
</html>
