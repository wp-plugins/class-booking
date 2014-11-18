<?php
require_once('class.phpmailer.php');
if(!class_exists("SendEmail")) {
    class SendEmail
    {
        /**
         * Notification to admin when new appointment arrived
         * @param $HostName
         * @param $PortNo
         * @param $SmtpEmail
         * @param $Password
         * @param $AdminEmail
         * @param $SubjectToAdmin
         * @param $BodyForAdmin
         * @param $BlogName
         */
        public function NotifyAdmin($HostName, $PortNo, $SmtpEmail, $Password, $AdminEmail, $SubjectToAdmin, $BodyForAdmin, $BlogName ) {
            $Subject = $SubjectToAdmin;
            $To = $AdminEmail;
            $Body = $BodyForAdmin;

            $mail = new PHPMailer();
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = $HostName;      // SMTP server
            $mail->SMTPDebug  = 1;              // enables SMTP debug information (for testing)// 1 = errors and messages , // 2 = messages only
            $mail->SMTPAuth   = true;           // enable SMTP authentication
            $mail->SMTPSecure = "ssl";          // sets the prefix to the servier
            $mail->Host       = $HostName;      // sets G-MAIL as the SMTP server
            $mail->Port       = $PortNo;        // set the SMTP port for the G-MAIL server
            $mail->Username   = $SmtpEmail;     // G-MAIL username
            $mail->Password   = $Password;              // G-MAIL password
            $mail->SetFrom($AdminEmail, $BlogName);    // admin mail
            $mail->AddReplyTo($AdminEmail, $BlogName); // admin mail
            //$mail->Subject    = $Subject;
            $mail->CharSet = 'UTF-8';
            $mail->Subject = mb_convert_encoding($Subject, "UTF-8", "auto");
            $mail->MsgHTML($Body);
            $mail->AddAddress($AdminEmail);    // sending email to
            $mail->Send();
        }

        /**
         * Notify to client after booked an appointment
         * @param $HostName
         * @param $PortNo
         * @param $SmtpEmail
         * @param $Password
         * @param $AdminEmail
         * @param $RecipientEmail
         * @param $SubjectToRecipient
         * @param $BodyForRecipient
         * @param $BlogName
         */
        public function NotifyClient($HostName, $PortNo, $SmtpEmail, $Password, $AdminEmail, $RecipientEmail, $SubjectToRecipient, $BodyForRecipient, $BlogName) {
            $Subject = $SubjectToRecipient;
            $To = $RecipientEmail;
            $Body = $BodyForRecipient;

            $mail = new PHPMailer();
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host       = $HostName;          // SMTP server
            $mail->SMTPDebug  = 1;                  // enables SMTP debug information (for testing)// 1 = errors and messages , // 2 = messages only
            $mail->SMTPAuth   = true;               // enable SMTP authentication
            $mail->SMTPSecure = "ssl";              // sets the prefix to the server
            $mail->Host       = $HostName;          // sets G-MAIL as the SMTP server
            $mail->Port       = $PortNo;            // set the SMTP port for the G-MAIL server
            $mail->Username   = $SmtpEmail;         // G-MAIL username
            $mail->Password   = $Password;          // G-MAIL password
            $mail->SetFrom($AdminEmail, $BlogName);    //admin mail
            $mail->AddReplyTo($AdminEmail, $BlogName); // admin mail
            //$mail->Subject    = $Subject;
            $mail->CharSet = 'UTF-8';
            $mail->Subject = mb_convert_encoding($Subject, "UTF-8", "auto");
            $mail->MsgHTML($Body);
            $mail->AddAddress($To);                     //client email here
            $mail->Send();
        }
    }
}