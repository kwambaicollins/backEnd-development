<?php
$servername = "localhost";
$username = "root"; // Replace with your actual username
$password = ""; // Replace with your actual password
$dbname = "junky real estate"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $homeName = $_POST["home_name"];
    $size = $_POST["size"];
    $price = $_POST["price"];

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO home (home_name, size, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $homeName, $size, $price);
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
        echo "Data inserted successfully";
    } else {
        echo "Error inserting data";
    }

    $stmt->close();
}

$conn->close();
?>
