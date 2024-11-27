<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = "";
$users = [];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // Insert new user into the database
    if ($action === "insert") {
        $username = $_POST['username'];
        $email = $_POST['email'];

        if (!empty($username) && !empty($email)) {
            $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $email);

            if ($stmt->execute()) {
                $message = "User added successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }

            $stmt->close();
        } else {
            $message = "Username and Email cannot be empty!";
        }
    }

    // Fetch all records from the database
    if ($action === "fetch") {
        $sql = "SELECT username, email FROM users"; // Replace 'users' with your table name
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAMP Stack App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #007BFF;
        }
        form {
            margin-bottom: 1.5rem;
        }
        input[type="text"], input[type="email"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            padding: 0.8rem 1.5rem;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            display: block;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .results {
            margin-top: 1.5rem;
        }
        .user {
            padding: 0.8rem;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }
        .message {
            text-align: center;
            margin-bottom: 1rem;
            font-size: 1rem;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LAMP Stack App</h1>

        <!-- Success/Error Message -->
        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, 'Error') === false ? '' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Form to Add New User -->
        <h2>Add New User</h2>
        <form method="POST">
            <input type="hidden" name="action" value="insert">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <button type="submit">Add User</button>
        </form>

        <!-- Form to Fetch All Records -->
        <h2>Fetch All Users</h2>
        <form method="POST">
            <input type="hidden" name="action" value="fetch">
            <button type="submit">Fetch All Records</button>
        </form>

        <!-- Display Results -->
        <div class="results">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <div class="user">
                        <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?><br>
                        <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No records to display. Submit the form to fetch data.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
