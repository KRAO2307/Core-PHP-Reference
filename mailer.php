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
            $mail->Host = 'mail.gbusinesslisting.com';       // Specify main and backup SMTP servers 
            $mail->SMTPAuth = true;               // Enable SMTP authentication 
            $mail->Username = 'admin@gbusinesslisting.com';   // SMTP username 
            $mail->Password = 'Support@123';   // SMTP password 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            // Enable TLS encryption, `ssl` also accepted 
            $mail->Port = 587;                    // TCP port to connect to
            
                    // Sender info 
            $mail->setFrom('admin@gbusinesslisting.com', 'Gbusinesslisting'); 
            $mail->addReplyTo('admin@gbusinesslisting.com', 'Gbusinesslisting'); 
            
            // Add a recipient 
            $mail->addAddress('admin@gbusinesslisting.com');          
            // $mail->addCC('priyanshu@milkywayservices.com'); 
            // $mail->addBCC('kamlesh.milkywayservices@gmail.com');          
            // Set email format to HTML 
            $mail->isHTML(true);          
            // Mail subject 
            $mail->Subject = $title; 
             
            // Mail body content 
            $bodyContent = '<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><title></title></head><body style=""><section style="max-width: 445px;box-shadow: 1px 1px 8px 1px grey; margin: 0 auto; margin-top: 40px;margin-bottom: 40px;background-color: #211d5a;padding: 40px 0px 40px 0px;"><table width="95%" style="background-color: white;margin: 0 auto;padding: 8px;"><tr><td><div style="text-align: center;"><img src="https://gbusinesslisting.com/images/gbusinesslogo.png" width="250px" style="margin-top: 10px;"></div><div ><div style="font-size: 22px; margin-top: 40px;"><span style="font-weight: bolder;">Name</span>:- '.$name.'</div><div style="font-size: 22px; margin-top: 40px;"><span style="font-weight: bolder;">Email</span>:- '.$email.'</div><div style="font-size: 22px; margin-top: 40px;"><span style="font-weight: bolder;">Title</span>:- '.$title.'</div><div ><div style="font-size: 22px;margin-top: 40px; text-align: justify;"><span style="font-weight: bolder;display: inline-block;">Message</span>:- '.$message.'</div></div><hr style="margin-top: 70px;"></div></td></tr></table></section></body></html>'; 
            
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