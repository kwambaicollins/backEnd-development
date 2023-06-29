<html>
<body>

<?php
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "junky real estate";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into the database
    $sql = "INSERT INTO users VALUES ('$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        $conn->close();
        header("Location: buy.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

?>
</body>
</html>
