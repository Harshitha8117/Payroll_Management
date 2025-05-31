<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Payroll Home</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
            text-align: center;
            max-width: 360px;
            width: 90vw;
        }
        h2 {
            margin-bottom: 35px;
            font-weight: 700;
            color: #222;
            letter-spacing: 1px;
        }
        a {
            text-decoration: none;
            display: block;
            margin-bottom: 18px;
        }
        button {
            width: 100%;
            padding: 14px 0;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,123,255,0.3);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 14px rgba(0,86,179,0.4);
        }
        button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payroll Management System</h2>
        <a href="add_employee.php"><button>Add Employee</button></a>
        <a href="view_employees.php"><button>View Employees</button></a>
        <a href="calculate_salary.php"><button>Calculate Salaries</button></a>
        <a href="delete_employee.php"><button>Delete Employee</button></a> <!-- Added -->
    </div>
</body>
</html>
