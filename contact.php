<?php
//ini_set('display_errors', 0);
//ini_set('display_startup_errors', 0);
//error_reporting(0);

require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

$mail = new \PHPMailer\PHPMailer\PHPMailer();

// Load credentials from secure config file
$config = require dirname(__DIR__) . '/config/mail.php';

echo "The changes are made in the public repository, there is no permission to make changes.";

echo "who reads gay";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete the form correctly.";
        exit;
    }

    try {
        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtp_user'];
        $mail->Password   = $config['smtp_pass'];
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $config['smtp_port'];

        $mail->setFrom($config['smtp_user'], 'AASI Contact Form');
        $mail->addAddress($config['smtp_user'], 'Andrew Anderson');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission from $name";
        $mail->Body    = "<strong>Name:</strong> $name<br>
                          <strong>Email:</strong> $email<br>
                          <strong>Message:</strong><br>$message";
        $mail->AltBody = "Name: $name\nEmail: $email\nMessage:\n$message";

        if($mail->send()) {
            echo "Thank you! Your message has been sent.";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }

    } catch (\PHPMailer\PHPMailer\Exception $e) {
        http_response_code(500);
        echo "Oops! Something went wrong: " . $e->getMessage();
    }

} else {
    http_response_code(403);
    echo "There was a problem with your submission.";
}

?>

<html>
    <body>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3159.695215518289!2d-3.0960702468591283!3d55.94517891336126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4887b9a7365fccff%3A0x52629bc613f4b94b!2sEdinburgh%20College!5e0!3m2!1sen!2suk!4v1776862522590!5m2!1sen!2suk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</body>
</html>