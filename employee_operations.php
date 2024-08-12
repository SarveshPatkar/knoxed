<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "knoxed";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $employee_name = $first_name . ' ' . $last_name; 
    $department_id = $_POST['department'];
    $editing_employee_id = $_POST['editing_employee_id'];

    if ($_POST['action'] == 'Add') {
        $sql = "INSERT INTO employees (employee_id, employee_name, department_id) VALUES ('$employee_id', '$employee_name', '$department_id')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['response'] = 'Record added successfully!';
        } else {
            $_SESSION['response'] = "Error adding record: " . $conn->error;
        }
    } elseif ($_POST['action'] == 'Update') {
        if (!empty($editing_employee_id)) {
            $sql = "UPDATE employees SET employee_id='$employee_id', employee_name='$employee_name', department_id='$department_id' WHERE employee_id='$editing_employee_id'";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['response'] = 'Record updated successfully!';
            } else {
                $_SESSION['response'] = "Error updating record: " . $conn->error;
            }
        }
    }
    header('Location: index.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $employee_id = $_GET['employee_id'];
    $sql = "DELETE FROM employees WHERE employee_id='$employee_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['response'] = 'Record deleted successfully!';
    } else {
        $_SESSION['response'] = "Error deleting record: " . $conn->error;
    }
    header('Location: index.php');
    exit;
}

$conn->close();
?>
