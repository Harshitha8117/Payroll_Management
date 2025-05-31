<?php
// Database config - match your setup
$host = 'localhost';
$db = 'payroll_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

// Fetch employees ordered by ascending ID
$stmt = $pdo->query("SELECT id, name, position, basic_salary FROM employees ORDER BY id ASC");
$employees = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Employee List</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;  /* vertical center */
            align-items: center;      /* horizontal center */
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #333;
        }
        h2 {
            margin-bottom: 30px;
            color: #222;
            font-weight: 700;
            letter-spacing: 1px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 700px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        thead tr {
            background: #007BFF;
            color: white;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        th, td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tbody tr:hover {
            background-color: #f1f9ff;
            cursor: pointer;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        td {
            font-weight: 500;
        }
    </style>
</head>
<body>

<h2>Employee List</h2>

<table>
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Position</th><th>Basic Salary</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($employees): ?>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?= htmlspecialchars($emp['id']) ?></td>
                    <td><?= htmlspecialchars($emp['name']) ?></td>
                    <td><?= htmlspecialchars($emp['position']) ?></td>
                    <td><?= htmlspecialchars(number_format($emp['basic_salary'], 2)) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">No employees found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

