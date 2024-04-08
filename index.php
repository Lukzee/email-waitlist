<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ComfyLearn - Join the Waitlist</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f3f3f3;
    }
    
    .container {
      text-align: center;
      padding: 20px;
    }
    
    h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
      color: #333;
    }
    
    p {
      font-size: 1.2rem;
      color: #666;
      margin-bottom: 30px;
    }
    
    .form-container {
      max-width: 400px;
      margin: 0 auto;
    }
    
    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    @media (max-width: 700px) {
      input[type="email"] {
        width: 90%;
      }
    }
    
    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    
    input[type="submit"]:hover {
      background-color: #45a049;
    }

    img {
      width: 100px;
    }
  </style>
</head>
<body>

<div class="container">
  <img src="cl.png" alt="">
  <h1>Join the Waitlist</h1>
  <p>Be the first to know when we launch! Sign up now and get exclusive early access.</p>
  <div class="form-container">
    <form id="waitlistForm">
      <input type="email" id="email" name="email" placeholder="Your Email Address" required><br>
      <input type="submit" value="Join">
    </form>
  </div>
</div>

<script>
  document.getElementById("waitlistForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var email = document.getElementById("email").value;
    
    // Regular expression for email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Check if the email is valid
    if (!emailRegex.test(email)) {
      alert("Please enter a valid email address.");
      return;
    }

    var formData = new FormData();
    formData.append("email", email);

    // Fetch API to send the email to process.php
    fetch("process.php", {
      method: "POST",
      // headers: {
      //   "Content-Type": "application/json",
      // },
      body: formData,
    })
    .then(response => {
      if (response.ok) {
        alert("Thank you for joining the waitlist!");
      } else {
        throw new Error("Failed to add email to the waitlist.");
      }
    })
    .catch(error => {
      alert(error.message);
    });

    document.getElementById("email").value = ""; // Clear the input field after submission
  });
</script>

</body>
</html>