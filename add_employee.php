<?php
// Database config
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

$messages = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $position = trim($_POST['position'] ?? '');
    $basic_salary = filter_var($_POST['basic_salary'] ?? '', FILTER_VALIDATE_FLOAT);

    $errors = [];

    if (!$name) $errors[] = 'Name is required.';
    if (!$email) $errors[] = 'Valid email is required.';
    if (!$position) $errors[] = 'Position is required.';
    if ($basic_salary === false || $basic_salary <= 0) $errors[] = 'Valid basic salary required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $messages = "<div class='error'>Email already exists. Use a different email.</div>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO employees (name, email, position, basic_salary) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $position, $basic_salary]);
            $messages = "<div class='success'>Employee added successfully.</div>";
            // Optional: clear fields or redirect here
        }
    } else {
        $messages = '<div class="error"><ul><li>' . implode('</li><li>', $errors) . '</li></ul></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Employee</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #eef2f7;
    padding: 40px;
    display: flex;
    justify-content: center;
  }
  form {
    background: #fff;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    width: 360px;
  }
  label {
    display: block;
    margin-bottom: 15px;
    font-weight: 600;
    color: #444;
  }
  input[type="text"],
  input[type="email"],
  input[type="number"] {
    width: 100%;
    padding: 10px 12px;
    margin-top: 6px;
    border: 1.8px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
    transition: border-color 0.25s ease;
  }
  input[type="text"]:focus,
  input[type="email"]:focus,
  input[type="number"]:focus {
    border-color: #007BFF;
    outline: none;
  }
  button {
    width: 100%;
    background: #007BFF;
    color: white;
    border: none;
    padding: 12px 0;
    font-size: 16px;
    border-radius: 7px;
    cursor: pointer;
    font-weight: 700;
    margin-top: 10px;
    transition: background-color 0.3s ease;
  }
  button:hover {
    background: #0056b3;
  }
  .error {
    background: #ffd6d6;
    color: #a70000;
    border: 1px solid #a70000;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 6px;
  }
  .error ul {
    margin: 0;
    padding-left: 20px;
  }
  .success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #155724;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 6px;
  }
</style>
</head>
<body>

<form method="post" action="add_employee.php" novalidate>
    <h2 style="text-align:center; margin-bottom: 20px; color:#222;">Add New Employee</h2>
    <?= $messages ?>
    <label>
      Name:
      <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </label>
    <label>
      Email:
      <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label>
    <label>
      Position:
      <input type="text" name="position" required value="<?= htmlspecialchars($_POST['position'] ?? '') ?>">
    </label>
    <label>
      Basic Salary:
      <input type="number" step="0.01" name="basic_salary" required value="<?= htmlspecialchars($_POST['basic_salary'] ?? '') ?>">
    </label>
    <button type="submit">Add Employee</button>
</form>

</body>
</html>
