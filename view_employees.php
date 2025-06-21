<?php require 'db.php'; ?>

<!DOCTYPE html>
<html>
<head><title>Employees</title></head>
<body>
    <h2>All Employees</h2>
    <table border="1">
        <tr><th>ID</th><th>Name</th><th>Salary</th><th>Action</th></tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM employees");
        while ($row = $stmt->fetch()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['salary']}</td>
                    <td><a href='delete_employee.php?id={$row['id']}'>Delete</a></td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>
