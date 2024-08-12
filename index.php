<?php
session_start();
$message = '';
if (isset($_SESSION['response'])) {
    $message = $_SESSION['response'];
    unset($_SESSION['response']);
}

// Database connection
$conn = new mysqli("localhost", "root", "", "knoxed");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch departments
$departments = $conn->query("SELECT id, name FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Your CSS here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            margin: 0;
        }

        form {
            margin-left: 20px;
        }

        form label {
            display: inline-block;
            width: 150px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input[type="text"],
        form select {
            width: calc(100% - 160px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form input[type="text"],
        form input[type="hidden"],
        form select {
            width: 300px;
        }

        form input[type="submit"],
        form input[type="reset"] {
            width: 100px;
            background-color: #333;
            color: #fff;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 1rem;
        }

        form input[type="submit"]:hover,
        form input[type="reset"]:hover {
            background-color: #555;
        }

        h2 {
            text-align: center;
            margin-top: 2rem;
        }

        table {
            width: 80%;
            margin: 2rem auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 0.5rem;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        td a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline;
        }

        ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            margin-top: 2rem;
        }

        ul li {
            display: inline;
            margin: 0 0.5rem;
        }

        ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        ul li a:hover {
            text-decoration: underline;
        }

        #messages {
            margin-bottom: 20px;
        }

        .alert {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: <?php echo empty($message) ? 'none' : 'block'; ?>;
        }
    </style>
</head>
<body>

<div id="messages">
    <p id="message" class="alert"><?php echo $message; ?></p>
</div>

<form id="employeeForm" method="post" action="employee_operations.php">
    <input type="hidden" id="editing_employee_id" name="editing_employee_id">

    <label for="employee_id">Employee ID:</label>
    <input type="text" id="employee_id" name="employee_id" required><br>

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required><br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required><br>

    <label for="department">Department:</label>
    <select id="department" name="department" required>
        <option value="">Select Department</option>
        <?php
        if ($departments->num_rows > 0) {
            while ($row = $departments->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        } else {
            echo "<option value=''>No departments available</option>";
        }
        ?>
    </select><br>

    <input type="submit" name="action" value="Add">
    <input type="submit" name="action" value="Update">
    <input type="reset" value="Reset">
</form>

<table>
    <thead>
        <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="employeeTable">
        <?php include 'display_employees.php'; ?>
    </tbody>
</table>

<script>
    // JavaScript to hide the message after 3 seconds (3000 milliseconds)
    setTimeout(function() {
        document.getElementById('message').style.display = 'none';
    }, 3000); // 3000 milliseconds = 3 seconds

    // Function to handle edit action
    function editEmployee(employeeId, employeeName, department) {
    document.getElementById('editing_employee_id').value = employeeId;
    document.getElementById('employee_id').value = employeeId;

    // Split the employee name into first and last names
    let names = employeeName.split(' ');
    let firstName = names[0];
    let lastName = names.slice(1).join(' '); // Handle cases with middle names

    document.getElementById('first_name').value = firstName;
    document.getElementById('last_name').value = lastName;
    document.getElementById('department').value = department;

    document.querySelector('input[name="action"][value="Add"]').disabled = true;
    document.querySelector('input[name="action"][value="Update"]').disabled = false;
}

</script>
</body>
</html>


