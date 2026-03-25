<?php
$conn = mysqli_connect("localhost", "root", "", "students");

if (!$conn) {
    die("Connection failed");
}

$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conn);
?>