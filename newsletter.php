<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* ------------------------------------------
       VERIFY reCAPTCHA v3
    ------------------------------------------- */
    $secretKey = "secret_key";
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
       PROCESS SUBSCRIPTION
    ------------------------------------------- */
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

    $to = "info@jetsetvisas.ae";
    $subject = "Newsletter Subscription - Jetset Visa";

    $message = "New subscriber: $email\n";
    $message .= "reCAPTCHA Score: " . $responseKeys["score"] . "\n";

    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "<div class='text-success'>Thank you for subscribing!</div>";
    } else {
        echo "<div class='text-danger'>Error subscribing. Please try again.</div>";
    }

} else {
    echo "<div class='text-danger'>Invalid request.</div>";
}
?>
