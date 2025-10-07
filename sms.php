<?php

// Function to send OTP via SMS using Twilio API (No Composer required)
function sendOtpSms($to, $otp) {
    // Twilio credentials (replace these with your own)
    $sid = 'ACa84c881af393290eac6134f5e91008c4';        // Replace with your Twilio Account SID
    $token = 'a349e9c24947a9d7f1628348cc226412';       // Replace with your Twilio Auth Token
    $from = '+19787978724'; // Replace with your Twilio phone number

    // Twilio API endpoint for sending messages
    $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";

    // Data to send in the API request
    $data = array(
        'From' => $from, // Twilio phone number 
        'To' => $to, // Recipient phone number
        'Body' => "Your OTP code is: $otp" // OTP message
    );

    // cURL setup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_USERPWD, "$sid:$token"); // Authenticate using your Twilio SID and Auth Token

    // Execute the cURL request and get the response
    $response = curl_exec($ch);

    // Check if the request was successful
    if(curl_errno($ch)) {
        return 'Error: ' . curl_error($ch); // If there's an error, return it
    }

    // Close the cURL session
    curl_close($ch);

    // Return success message
    return "OTP sent successfully to $to.";
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumber = $_POST['phone'];
    $otp = rand(100000, 999999); // Generate a random 6-digit OTP

    // Validate phone number (basic validation)
    if (empty($phoneNumber)) {
        $message = "Please enter a phone number.";
    } elseif (!preg_match('/^\+?\d{10,15}$/', $phoneNumber)) {
        $message = "Invalid phone number format. It should include the country code.";
    } else {
        // Send OTP via SMS
        $message = sendOtpSms($phoneNumber, $otp);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP via SMS</title>
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
    <h2>Send OTP via SMS</h2>
    <form method="POST" action="">
        <label for="phone">Enter Phone Number (with country code):</label>
        <input type="text" name="phone" id="phone" placeholder="Phone no" required>
        <button type="submit">Send OTP</button>
    </form>

    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'Error') !== false ? 'error' : '' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
</body>
</html>
