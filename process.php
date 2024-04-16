<?php

// Connect to your database (Replace these values with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comfylearn";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Return a response with a status code indicating an internal server error
    http_response_code(500);
    echo json_encode(array("error" => "Failed to connect to the database."));
    exit();
}

// Check if the request method is POST
if (isset($_POST['email'])) {
    // Retrieve the email from the POST data
    $email = trim($_POST["email"]);

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Return a response with a status code indicating a bad request
        http_response_code(400);
        echo json_encode(array("error" => "Invalid email address."));
        exit();
    }

    // Sanitize the email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if the email already exists in the waitlist table
    $sql = "SELECT * FROM waitlist WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email already exists in the waitlist table
        // Return a response with a status code indicating a conflict
        http_response_code(409);
        echo json_encode(array("error" => "Email already exists in the waitlist."));
    } else {
        // Email doesn't exist in the waitlist table, add it with the current date and time
        $currentDateTime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO waitlist (email, registration_date) VALUES ('$email', '$currentDateTime')";
        
        if ($conn->query($sql) === TRUE) {
            // Return a response with a status code indicating success
            http_response_code(200);
            echo json_encode(array("message" => "Email successfully added to the waitlist."));
        } else {
            // Return a response with a status code indicating an internal server error
            http_response_code(500);
            echo json_encode(array("error" => "Failed to add email to the waitlist."));
        }
    }
}

// Check if username and password are sent via POST request
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Hard-coded username and password for demonstration
    $hardCodedUsername = 'admin';
    $hardCodedPassword = 'password123';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password match the hard-coded values
    if ($username === $hardCodedUsername && $password === $hardCodedPassword) {
        // Fetch emails and dates from the wait-list table
        $sql = "SELECT * FROM waitlist ORDER BY registration_date DESC";
        $result = $conn->query($sql);
        $emails = array();
        while ($row = mysqli_fetch_array($result)) {
            $emails[] = array(
                'email' => $row['email'],
                'date' => $row['registration_date']
            );
        }
        // Return JSON response with emails data
        echo json_encode($emails);
    } else {
        // Return JSON response with error message
        echo json_encode(['error' => 'Invalid username or password']);
    }
}

?>