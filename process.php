<?php

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Close database connection
    $conn->close();
} else {
    // Return a response with a status code indicating a method not allowed
    http_response_code(405);
    echo json_encode(array("error" => "Method not allowed."));
}

?>