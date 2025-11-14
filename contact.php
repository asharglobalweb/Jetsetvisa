<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* ------------------------------------------
       VERIFY reCAPTCHA v3
    ------------------------------------------- */
    $secretKey = "jbvsdbhsdvjhvjadvhabdvja-jhdbadshb"; // IMPORTANT
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
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    $to = "info@jetsetvisas.ae";
    $mail_subject = "Contact Form - Jetset Visa: $subject";

    $mail_body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message\n";
    $mail_body .= "reCAPTCHA Score: " . $responseKeys["score"] . "\n";

    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($to, $mail_subject, $mail_body, $headers)) {
        echo "<div class='text-success'>Thank you! Your message has been sent.</div>";
    } else {
        echo "<div class='text-danger'>Error sending message. Please try again.</div>";
    }
} else {
    echo "<div class='text-danger'>Invalid request.</div>";
}
?>
