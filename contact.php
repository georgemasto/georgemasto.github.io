<?php
$date = date("m-d-Y");
// Define variables and initialize with empty values
$nameErr = $emailErr = $messageErr = "";
$name = $email = $subject = $message = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Validate user name
	if(empty($_POST["form_name"])){
		$nameErr = "Please enter your name.";
	} else{
		$name = filterName($_POST["form_name"]);
		if($name == FALSE){
			$nameErr = "Please enter a valid name.";
		}
	}

	// Validate email address
	if(empty($_POST["form_email"])){
		$emailErr = "Please enter your email address.";
	} else{
		$email = filterEmail($_POST["form_email"]);
		if($email == FALSE){
			$emailErr = "Please enter a valid email address.";
		}
	}

	// Validate message subject
	if(empty($_POST["form_subject"])){
		$form_subject = "";
	} else{
		$form_subject = filterString($_POST["form_subject"]);
	}

	// Validate user comment
	if(empty($_POST["form_message"])){
		$messageErr = "Please enter your comment.";
	} else{
		$comment = filterString($_POST["form_message"]);
		if($comment == FALSE){
			$messageErr = "Please enter a valid comment.";
		}
	}

	// Check input errors before sending email
	if(empty($nameErr) && empty($emailErr) && empty($messageErr)){
		$to = 'georgemasto@gmail.com'; // Change to where you want the form data to be sent
		$subject = 'New Contact Form Submission';
		$from = 'noreply@georgemasto.github.io';  // Must be from your domain

// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
		$headers .= 'From: '.$from."\r\n".
			'Reply-To: '.$from."\r\n" .
			'X-Mailer: PHP/' . phpversion();

// Compose a simple HTML email message
		$message .= '<h1>New Contact Form Submission</h1><br><br><br>';
		$message .= "<h3>Date:</h3> $date<br><br>";
		$message .= "<h3>Name:</h3> $name<br><br>";
		$message .= "<h3>Email:</h3> $email<br><br>";
		$message .= "<h3>Subject:</h3> $form_subject<br><br>";
		$message .= "<h3>Message:</h3> $comment";

// Sending email
		if(mail($to, $subject, $message, $headers)){
			header("Location:thank-you.php");
		} else{
			echo '<p class="error">Unable to send email. Please try again.</p>';
		}
	}
}

// Functions to filter user inputs
function filterName($field){
	// Sanitize user name
	$field = filter_var(trim($field), FILTER_SANITIZE_STRING);

	// Validate user name
	if(filter_var($field, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
		return $field;
	} else{
		return FALSE;
	}
}
function filterEmail($field){
	// Sanitize e-mail address
	$field = filter_var(trim($field), FILTER_SANITIZE_EMAIL);

	// Validate e-mail address
	if(filter_var($field, FILTER_VALIDATE_EMAIL)){
		return $field;
	} else{
		return FALSE;
	}
}
function filterString($field){
	// Sanitize string
	$field = filter_var(trim($field), FILTER_SANITIZE_STRING);
	if(!empty($field)){
		return $field;
	} else{
		return FALSE;
	}
}
?>
