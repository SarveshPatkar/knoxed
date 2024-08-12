<?php
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

$limit = 5; // Number of entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$sql = "SELECT employees.employee_id, employees.employee_name, departments.name AS department 
        FROM employees 
        INNER JOIN departments ON employees.department_id = departments.id 
        LIMIT $start, $limit";
$result = $conn->query($sql);

$total_result = $conn->query("SELECT COUNT(*) FROM employees")->fetch_row()[0];
$total_pages = ceil($total_result / $limit);

echo "<tbody>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['employee_id'] . "</td>";
        echo "<td>" . $row['employee_name'] . "</td>";
        echo "<td>" . $row['department'] . "</td>";
        echo "<td>";
        echo "<a href='#' onclick='editEmployee(\"" . $row['employee_id'] . "\", \"" . $row['employee_name'] . "\", \"" . $row['department'] . "\")'>Edit</a> | ";
        echo "<a href='employee_operations.php?action=delete&employee_id=" . $row['employee_id'] . "'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}

echo "</tbody>";
echo "</table>";

// Pagination links
echo "<div class='pagination' style='float: right;'>";
echo "<ul>";
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<li><a href='?page=$i'>$i</a></li>";
}
echo "</ul>";
echo "</div>";

$conn->close();
?>
