<?php
// Function to send OTP via email
function sendOtpEmail($email, $otp) {
    $subject = "Your OTP Code";
    $message = "Your OTP code is: $otp";
    $headers = "From: noreply@example.com";

    // Attempt to send the email
    if (mail($email, $subject, $message, $headers)) {
        return "OTP sent successfully to $email.";
    } else {
        return "Failed to send OTP. Please try again.";
    }
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = rand(100000, 999999); // Generate a random 6-digit OTP

    // Validate email
    if (empty($email)) {
        $message = "Please enter an email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Send OTP
        $message = sendOtpEmail($email, $otp);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP via Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            margin-top: 20px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Send OTP via Email</h2>
    <form method="POST" action="">
        <label for="email">Enter Email Address:</label>
        <input type="email" name="email" id="email" placeholder="Email-ID" required>
        <button type="submit">Send OTP</button>
    </form>

    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'Failed') !== false ? 'error' : '' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
</body>
</html>
