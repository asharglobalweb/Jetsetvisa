<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* ------------------------------------------
       VERIFY reCAPTCHA v3
    ------------------------------------------- */
    $secretKey = "jbvsdbhsdvjhvjadvhabdvja-jhdbadshb";
    $recaptcha = $_POST['g-recaptcha-response'];

    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha"
    );
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"] || $responseKeys["score"] < 0.4) {
        echo "<div class='text-danger'>reCAPTCHA failed. Please try again.</div>";
        exit;
    }

    /* ------------------------------------------
       FORM PROCESSING
    ------------------------------------------- */
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $country_code = trim($_POST["country_code"]);
    $phone = trim($_POST["phone"]);
    $visa = trim($_POST["visa"]);
    $message = trim($_POST["message"]);

    $to = "info@jetsetvisas.ae";
    $subject = "Consultation Request - Visa Agency Website";

    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $country_code $phone\n";
    $email_content .= "Visa Type: $visa\n";
    $email_content .= "Message:\n$message\n";
    $email_content .= "reCAPTCHA Score: " . $responseKeys["score"] . "\n";

    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($to, $subject, $email_content, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Error sending message.";
    }
} else {
    echo "Invalid request.";
}
?>
