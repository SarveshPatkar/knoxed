<?php
class EmployeeManager {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function addEmployee($employee_id, $first_name, $last_name, $department_id) {
        $employee_name = $first_name . ' ' . $last_name; // Combine first and last name
        $sql = "INSERT INTO employees (employee_id, employee_name, department_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $employee_id, $employee_name, $department_id);
        
        if ($stmt->execute()) {
            return "Record added successfully!";
        } else {
            return "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    public function updateEmployee($employee_id, $first_name, $last_name, $department_id) {
        $employee_name = $first_name . ' ' . $last_name; // Combine first and last name
        $sql = "UPDATE employees SET employee_name=?, department_id=? WHERE employee_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $employee_name, $department_id, $employee_id);
        
        if ($stmt->execute()) {
            return "Record updated successfully!";
        } else {
            return "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    public function deleteEmployee($employee_id) {
        $sql = "DELETE FROM employees WHERE employee_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $employee_id);
        
        if ($stmt->execute()) {
            return "Record deleted successfully!";
        } else {
            return "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    public function getEmployees() {
        $sql = "SELECT employees.employee_id, employees.employee_name, departments.name AS department 
                FROM employees 
                INNER JOIN departments ON employees.department_id = departments.id";
        return $this->conn->query($sql);
    }

    public function close() {
        $this->conn->close();
    }
}
?>
