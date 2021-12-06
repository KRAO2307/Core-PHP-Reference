<?php
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
require 'PHPMailer/src/Exception.php'; 
require 'PHPMailer/src/PHPMailer.php'; 
require 'PHPMailer/src/SMTP.php';

// ********************Form Validation START****************** 

$nameErr = $emailErr = $titleErr = $messageErr = $invalidErr ="";
$name = $email = $title = $message = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
        $nameErr = "name is required";
    } else {
        $name = test_input($_POST['name']);
    }
    if (empty($_POST["email"])) {
        $emailErr = "User email is required";
    } else {
        $email = test_input($_POST["email"]);
    }
    if (empty($_POST["title"])) {
        $titleErr = "title is required";
    } else {
        $title = test_input($_POST['title']);
    }
    if (empty($_POST["message"])) {
        $messageErr = "message is required";
    } else {
        $message = test_input($_POST['message']);
    }


    if (($nameErr == '') && ($emailErr == '') && ($titleErr == '') && ($messageErr == '')) {
        
        if(isset($_POST['submit'])){
        include "config.php";
        $name = mysqli_real_escape_string($conn,$_POST['name']);
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $title = mysqli_real_escape_string($conn,$_POST['title']);
        $message = mysqli_real_escape_string($conn,$_POST['message']);
        
        $sql = "INSERT INTO contact_form(cname,cemail,ctitle,cmessage) VALUES ('{$name}','{$email}','{$title}','{$message}')";
        if (mysqli_query($conn,$sql)) {
        
//   *************************** phpMailer **************************
            
            $mail = new PHPMailer; 
 
            $mail->isSMTP();                      // Set mailer to use SMTP 
            $mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
            $mail->SMTPAuth = true;               // Enable SMTP authentication 
            $mail->Username = 'kamlesh.k.rao@gmail.com';   // SMTP username 
            $mail->Password = 'your mail password';   // SMTP password 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // Enable TLS encryption, `ssl` also accepted 
            $mail->Port = 587;                    // TCP port to connect to
            
                    // Sender info 
            $mail->setFrom('kamlesh.k.rao@gmail.com', 'Kamlesh'); 
            $mail->addReplyTo('kamlesh.k.rao@gmail.com', 'Kamlesh'); 
            
            // Add a recipient 
            $mail->addAddress('kamlesh.k.rao@gmail.com');          
            // $mail->addCC('addCCemailid@gmail.com'); 
            // $mail->addBCC('addBCCemailid@gmail.com');          
            // Set email format to HTML 
            $mail->isHTML(true);          
            // Mail subject 
            $mail->Subject = $title; 
             
            // Mail body content 
            $bodyContent = '<html><body><p>Create you mailer</p></body></html>'; 
            
            $mail->Body    = $bodyContent;
            
            }
            // Send email 
            if(!$mail->send()) { 
                echo "<script>alert('Message could not be sent. Mailer Error: ')".$mail->ErrorInfo; 
                }else{
                    echo "<script>alert('Message has been sent.');</script>";
                    $name = $email = $title = $message = "";
                }
        }
    }else{
            echo "<script>alert('Please fillup all the fields.');</script>";
        }
}
?>