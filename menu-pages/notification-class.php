<?php
 /**
  * Class: Notification
  * Version: 1.0
  * Author: FRANK FARAZ
  * Pakage: Appointment Calendar Premium 3.2
  * Description: This class send notification
  * massage to admin, client, instructor when new appointment
  * booked by any client.
  **/
if(!class_exists("Notification")) {
    class Notification
    {
         //notify admin
         function NotifyAdmin($BookingId, $ClassId, $ClientId, $BlogName, $DateFormat, $TimeFormat) {
            global $wpdb;
            //booking details
            $ClassBookingsTable = $wpdb->prefix."apcal_pre_class_bookings";
            $BookingData = $wpdb->get_row("SELECT * FROM `$ClassBookingsTable` WHERE `id` = '$BookingId'", OBJECT);

            //class details
            $ClassesTable = $wpdb->prefix . "apcal_pre_classes";
            $ClassData = $wpdb->get_row("SELECT * FROM `$ClassesTable` WHERE `id` = '$ClassId' ", OBJECT);

            //client details
            $ClassClientTable = $wpdb->prefix."apcal_pre_class_clients";
            $ClientData = $wpdb->get_row("SELECT * FROM `$ClassClientTable` WHERE `id` = '$ClientId'", OBJECT);

            if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";
            $BookingDate = date($DateFormat, strtotime($BookingData->joining_date));
            $BookingTime = date($TimeFormat, strtotime($ClassData->start_time))." - ".date($TimeFormat, strtotime($ClassData->end_time));

            update_option("status_in_your_language",__(ucwords($BookingData->status), 'appointzilla'));
            $StatusLang = get_option("status_in_your_language");
            if($StatusLang) $BookingData->status = $StatusLang;

            $BlogUrl = get_site_url();
            $BlogLoginUrl = wp_login_url();

            //admin subject
            $AdminSubject = get_option('on_booking_admin_subject');
            $AdminSubject = str_replace("[blog-name]", ucwords($BlogName), $AdminSubject);
            $AdminSubject = str_replace("[blog-url]", $BlogUrl, $AdminSubject);
            $AdminSubject = str_replace("[blog-name]", ucwords($BlogName), $AdminSubject);
            $AdminSubject = str_replace("[client-name]", ucwords($ClientData->name), $AdminSubject);
            $AdminSubject = str_replace("[client-email]", ucwords($ClientData->email), $AdminSubject);
            $AdminSubject = str_replace("[client-phone]", ucwords($ClientData->phone), $AdminSubject);
            $AdminSubject = str_replace("[client-sn]", ucfirst($ClientData->sn), $AdminSubject);
            $AdminSubject = str_replace("[class-name]", ucwords($ClassData->name), $AdminSubject);
            $AdminSubject = str_replace("[book-date]", $BookingDate, $AdminSubject);
            $AdminSubject = str_replace("[book-status]", ucwords($BookingData->status), $AdminSubject);
            $AdminSubject = str_replace("[book-time]", $BookingTime, $AdminSubject);
            $AdminSubject = str_replace("[book-key]", $BookingData->key, $AdminSubject);

            //admin body
            $AdminBody = get_option('on_booking_admin_body');
            $AdminBody = str_replace("[blog-url]", $BlogUrl, $AdminBody);
            $AdminBody = str_replace("[blog-login-url]", $BlogLoginUrl, $AdminBody);
            $AdminBody = str_replace("[blog-name]", ucwords($BlogName), $AdminBody);
            $AdminBody = str_replace("[client-name]", ucwords($ClientData->name), $AdminBody);
            $AdminBody = str_replace("[client-email]", ucwords($ClientData->email), $AdminBody);
            $AdminBody = str_replace("[client-phone]", ucwords($ClientData->phone), $AdminBody);
            $AdminBody = str_replace("[client-sn]", ucfirst($ClientData->sn), $AdminBody);
            $AdminBody = str_replace("[class-name]", ucwords($ClassData->name), $AdminBody);
            $AdminBody = str_replace("[book-date]", $BookingDate, $AdminBody);
            $AdminBody = str_replace("[book-status]", ucwords($BookingData->status), $AdminBody);
            $AdminBody = str_replace("[book-time]", $BookingTime, $AdminBody);
            $AdminBody = str_replace("[book-key]", $BookingData->key, $AdminBody);
            $this->SendNotification('admin', $AdminSubject, $AdminBody, $ClientData->email);
         }


         //notify client
         function NotifyClient($On, $BookingId, $ClassId, $ClientId, $BlogName, $DateFormat, $TimeFormat) {
            global $wpdb;
            //booking details
            $ClassBookingsTable = $wpdb->prefix."apcal_pre_class_bookings";
            $BookingData = $wpdb->get_row("SELECT * FROM `$ClassBookingsTable` WHERE `id` = '$BookingId'", OBJECT);
            $TimezoneDifference = $BookingData->client_timezone_difference;

            //class details
            $ClassesTable = $wpdb->prefix . "apcal_pre_classes";
            $ClassData = $wpdb->get_row("SELECT * FROM `$ClassesTable` WHERE `id` = '$ClassId' ", OBJECT);

            //client details
            $ClassClientTable = $wpdb->prefix."apcal_pre_class_clients";
            $ClientData = $wpdb->get_row("SELECT * FROM `$ClassClientTable` WHERE `id` = '$ClientId'", OBJECT);

            if($TimeFormat == 'h:i') $TimeFormat = "h:ia"; else $TimeFormat = "H:i";
            $BookingDate = date($DateFormat, strtotime($BookingData->joining_date));
             //calculate time according to timezone difference
             if($TimezoneDifference != "0 minutes") {
                 $StartTime = date($TimeFormat, strtotime("+".$TimezoneDifference, strtotime($ClassData->start_time)));
                 $EndTime = date($TimeFormat, strtotime("+".$TimezoneDifference, strtotime($ClassData->end_time)));
                 $BookingTime = date($TimeFormat, strtotime($StartTime))." - ".date($TimeFormat, strtotime($EndTime));
             } else {
                 $BookingTime = date($TimeFormat, strtotime($ClassData->start_time))." - ".date($TimeFormat, strtotime($ClassData->end_time));
             }


            update_option("status_in_your_language",__(ucwords($BookingData->status), 'appointzilla'));
            $StatusLang = get_option("status_in_your_language");
            if($StatusLang) $BookingData->status = $StatusLang;

            //client subject
            if($On == "pending") $ClientSubject = get_option('on_booking_client_subject');
            if($On == 'approved') $ClientSubject = get_option('on_approve_client_subject');
            if($On == 'cancelled') $ClientSubject = get_option('on_cancel_client_subject');

            $BlogUrl = get_site_url();
            $BlogLoginUrl = wp_login_url();

            $ClientSubject = str_replace("[blog-name]", ucwords($BlogName), $ClientSubject);
            $ClientSubject = str_replace("[blog-url]", $BlogUrl, $ClientSubject);
            $ClientSubject = str_replace("[blog-login-url]", $BlogLoginUrl, $ClientSubject);
            $ClientSubject = str_replace("[client-name]", ucwords($ClientData->name), $ClientSubject);
            $ClientSubject = str_replace("[client-email]", ucwords($ClientData->email), $ClientSubject);
            $ClientSubject = str_replace("[client-phone]", ucwords($ClientData->phone), $ClientSubject);
            $ClientSubject = str_replace("[client-sn]", ucfirst($ClientData->sn), $ClientSubject);
            $ClientSubject = str_replace("[class-name]", ucwords($ClassData->name), $ClientSubject);
            $ClientSubject = str_replace("[book-date]", $BookingDate, $ClientSubject);
            $ClientSubject = str_replace("[book-status]", ucwords($BookingData->status), $ClientSubject);
            $ClientSubject = str_replace("[book-time]", $BookingTime, $ClientSubject);
            $ClientSubject = str_replace("[book-key]", $BookingData->key, $ClientSubject);

            //client body
            if($On == 'pending') $ClientBody = get_option('on_booking_client_body');
            if($On == 'approved') $ClientBody = get_option('on_approve_client_body');
            if($On == 'cancelled') $ClientBody = get_option('on_cancel_client_body');

            $ClientBody = str_replace("[blog-name]", ucwords($BlogName), $ClientBody);
            $ClientBody = str_replace("[blog-url]", $BlogUrl, $ClientBody);
            $ClientBody = str_replace("[blog-login-url]", $BlogLoginUrl, $ClientBody);
            $ClientBody = str_replace("[client-name]", ucwords($ClientData->name), $ClientBody);
            $ClientBody = str_replace("[client-email]", ucwords($ClientData->email), $ClientBody);
            $ClientBody = str_replace("[client-phone]", ucwords($ClientData->phone), $ClientBody);
            $ClientBody = str_replace("[client-sn]", ucfirst($ClientData->sn), $ClientBody);
            $ClientBody = str_replace("[class-name]", ucwords($ClassData->name), $ClientBody);
            $ClientBody = str_replace("[book-date]", $BookingDate, $ClientBody);
            $ClientBody = str_replace("[book-status]", ucwords($BookingData->status), $ClientBody);
            $ClientBody = str_replace("[book-time]", $BookingTime, $ClientBody);
            $ClientBody = str_replace("[book-key]", $BookingData->key, $ClientBody);

            $this->SendNotification('client', $ClientSubject, $ClientBody, $ClientData->email);
         }


         //send notification
         function SendNotification($To, $Subject, $Body, $RecipientEmail) {
            $BlogName =  get_bloginfo('name');
             $AllNotificationSettings = unserialize(get_option('acb_notification_settings'));
             if(count($AllNotificationSettings)) {
                 $EnableNotification = $AllNotificationSettings['acb_enable_notification'];
                 $NotificationType = $AllNotificationSettings['acb_notification_type'];
                 $WpAdminEmail = $AllNotificationSettings['acb_wp_admin_email'];
                 $PhpAdminEmail = $AllNotificationSettings['acb_php_admin_email'];
                 $SMTPHost = $AllNotificationSettings['acb_smtp_host_name'];
                 $SMTPPort = $AllNotificationSettings['acb_smtp_port'];
                 $SMTPEmail = $AllNotificationSettings['acb_smtp_admin_email'];
                 $SMTPPassword = $AllNotificationSettings['acb_smtp_admin_password'];
             }

            //check email notification ON/OFF
            if($EnableNotification == "yes") {
                //wp-mail
                if($NotificationType == 'wp-mail') {
                    $AdminEmail = $WpAdminEmail;
                    $Headers[] = "From: $BlogName <$AdminEmail>";
                    //send admin mail
                    if($To == 'admin') {
                        wp_mail( $AdminEmail, $Subject, $Body, $Headers, $Attachments = '' );
                    }
                    //send client mail
                    if($To == 'client') {
                        wp_mail( $RecipientEmail, $Subject, $Body, $Headers, $Attachments = '' );
                    }
                }// end of wp mail

                //php mail
                if($NotificationType == 'php-mail') {
                    $AdminEmail = $PhpAdminEmail;
                    $Headers = "From: $BlogName <$AdminEmail>";
                    //send admin mail
                    if($To == 'admin') {
                        mail($AdminEmail, $Subject, $Body, $Headers);
                    }
                    //send client mail
                    if($To == 'client') {
                        mail($RecipientEmail, $Subject, $Body, $Headers);
                    }
                }// end of php mail

                //smtp mail
                if($NotificationType == 'smtp-mail') {
                    $AdminEmail    = $SMTPEmail;
                    $HostName      = $SMTPHost;
                    $PortNo        = $SMTPPort;
                    $SmtpEmail     = $SMTPEmail;
                    $Password      = $SMTPPassword;
                    require_once('notification-class/SendEmail.php');
                    $SendEmail = new SendEmail();
                    //send mail to admin
                    if($To == 'admin') {
                        $Body = "<pre>$Body</pre>";
                        $SendEmail->NotifyAdmin($HostName, $PortNo, $SmtpEmail, $Password, $AdminEmail, $Subject, $Body, $BlogName);
                    }
                    //send mail to client
                    if($To == 'client') {
                        $Body = "<pre>$Body</pre>";
                        $SendEmail->NotifyClient($HostName, $PortNo, $SmtpEmail, $Password, $AdminEmail, $RecipientEmail, $Subject, $Body, $BlogName);
                    }
                }// end of smtp mail
            }// end of email enable check
         }// end of send notification
    }// end of class
}