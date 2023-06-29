<?php
if(isset($_POST['select'])){
    $landName = $_POST['land_name'];
    $size = $_POST['size'];
    $price = $_POST['price'];

    // Connect to your database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "junky real estate";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Insert the data into the database
    $sql = "INSERT INTO `land info` (`land_Name`, `size`, `price`) VALUES ('$landName', '$size', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo "Data submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>
