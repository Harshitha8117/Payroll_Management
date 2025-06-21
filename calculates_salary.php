<?php require 'db.php'; ?>

<!DOCTYPE html>
<html>
<head><title>Salary Calculation</title></head>
<body>
    <h2>Calculate Monthly Salary</h2>
    <form method="POST">
        Employee ID: <input type="number" name="id" required><br>
        Days Worked: <input type="number" name="days" required><br>
        <button type="submit">Calculate</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = $_POST['id'];
        $days = $_POST['days'];

        $stmt = $pdo->prepare("SELECT salary FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        $employee = $stmt->fetch();

        if ($employee) {
            $dailyRate = $employee['salary'] / 30;
            $total = $dailyRate * $days;
            echo "<p>Total Salary: ₹" . number_format($total, 2) . "</p>";
        } else {
            echo "<p>Employee not found.</p>";
        }
    }
    ?>
</body>
</html>
