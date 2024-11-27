<?php
// Backend logic: Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database credentials
    $db_host = "<RDS-Endpoint>";
    $db_user = "admin";
    $db_pass = "password";
    $db_name = "MyAppDB";

    // Create connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get data from POST request
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Insert data into users table
    $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";

    if ($conn->query($sql) === TRUE) {
        $message = "User registered successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h1>Register</h1>
    <?php
    // Show success/error message
    if (isset($message)) {
        echo "<p>$message</p>";
    }
    ?>
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
