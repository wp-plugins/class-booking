<?php
$AllNotificationSettings = unserialize(get_option('acb_notification_settings'));
if(count($AllNotificationSettings)) {
    $EnableNotification = $AllNotificationSettings['acb_enable_notification'];
    $NotifyAdmin = $AllNotificationSettings['acb_notify_admin'];
    $NotifyClient = $AllNotificationSettings['acb_notify_client'];
    $NotificationType = $AllNotificationSettings['acb_notification_type'];
    $WpAdminEmail = $AllNotificationSettings['acb_wp_admin_email'];
    $PhpAdminEmail = $AllNotificationSettings['acb_php_admin_email'];
    $SMTPHost = $AllNotificationSettings['acb_smtp_host_name'];
    $SMTPPort = $AllNotificationSettings['acb_smtp_port'];
    $SMTPEmail = $AllNotificationSettings['acb_smtp_admin_email'];
    $SMTPPassword = $AllNotificationSettings['acb_smtp_admin_password'];
} else {
    //get blog admin email
    $AdminEmail = get_bloginfo('admin_email');

    $EnableNotification = "yes";
    $NotifyAdmin = "yes";
    $NotifyClient = "yes";
    $NotificationType = "wp-mail";
    $WpAdminEmail = $AdminEmail;
    $PhpAdminEmail = $AdminEmail;
    $SMTPHost = "";
    $SMTPPort = "";
    $SMTPEmail = "";
    $SMTPPassword = "";
}
?>
<?php _e("", "appointzilla"); ?>
    <div class="borBox form-horizontal">
        <h3 style="margin-left: 30px;"><?php _e("Notification Settings", "appointzilla"); ?></h3>
        <!--enable notification-->
        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Notification", "appointzilla"); ?></label>
            <div class="control pull-left">
                <select id="enable-notification" name="enable-notification" style="margin-left: 15px;">
                    <option <?php if($EnableNotification == "yes") echo "selected='selected'"; ?>  value="yes"><?php _e("ON", "appointzilla"); ?></option>
                    <option <?php if($EnableNotification == "no") echo "selected='selected'"; ?>  value="no"><?php _e("OFF", "appointzilla"); ?></option>
                </select>
            </div>
        </div>

        <!--settings div-->
        <div id="show-settings" style="display: <?php if($EnableNotification == "yes") { echo ""; } else { echo "none"; } ?>;">
            <!--notify admin-->
            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Notify Admin", "appointzilla"); ?></label>
                <div class="control pull-left">
                    <select id="notify-admin" name="notify-admin" style="margin-left: 15px;">
                        <option <?php if($NotifyAdmin == "yes") echo "selected='selected'"; ?>  value="yes"><?php _e("Yes", "appointzilla"); ?></option>
                        <option <?php if($NotifyAdmin == "no") echo "selected='selected'"; ?>  value="no"><?php _e("No", "appointzilla"); ?></option>
                    </select>
                </div>
            </div>

            <!--notify client-->
            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Notify Client", "appointzilla"); ?></label>
                <div class="control pull-left">
                    <select id="notify-client" name="notify-client" style="margin-left: 15px;">
                        <option <?php if($NotifyClient == "yes") echo "selected='selected'"; ?>  value="yes"><?php _e("Yes", "appointzilla"); ?></option>
                        <option <?php if($NotifyClient == "no") echo "selected='selected'"; ?>  value="no"><?php _e("No", "appointzilla"); ?></option>
                    </select>
                </div>
            </div>

            <!--notification type-->
            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Notification Type", "appointzilla"); ?></label>
                <div class="control pull-left">
                    <select id="notification-type" name="notification-type" style="margin-left: 15px;">
                        <option value="wp-mail" <?php if($NotificationType == 'wp-mail') echo "selected"; ?>><?php echo  _e('WP Mail', 'appointzilla'); ?></option>
                        <option value="php-mail" <?php if($NotificationType == 'php-mail') echo "selected"; ?>><?php echo _e('PHP Mail', 'appointzilla'); ?></option>
                        <option value="smtp-mail"<?php if($NotificationType == 'smtp-mail') echo "selected"; ?>><?php echo  _e('SMTP Mail', 'appointzilla'); ?></option>
                    </select>
                </div>
            </div>

            <!--wp email div-->
            <div id="wp-mail-div" style="display: <?php if($NotificationType == "wp-mail") { echo ""; } else { echo "none"; } ?>;">
                <div class="control-group">
                    <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("WP Admin Email", "appointzilla"); ?></label>
                    <div class="control pull-left">
                        <input type="text" id="wp-admin-email" name="wp-admin-email" value="<?php echo $WpAdminEmail; ?>" style="margin-left: 15px;">
                    </div>
                </div>
            </div>

            <!--php email div-->
            <div id="php-mail-div" style="display: <?php if($NotificationType == "php-mail") { echo ""; } else { echo "none"; } ?>;">
                <div class="control-group">
                    <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("PHP Admin Email", "appointzilla"); ?></label>
                    <div class="control pull-left">
                        <input type="text" id="php-admin-email" name="php-admin-email" value="<?php echo $PhpAdminEmail; ?>" style="margin-left: 15px;">
                    </div>
                </div>
            </div>

            <!--smtp settings div-->
            <div id="smtp-mail-div" style="display: <?php if($NotificationType == "smtp-mail") { echo ""; } else { echo "none"; } ?>;">
                <!--SMTP host-->
                <div class="control-group">
                    <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("SMTP Host", "appointzilla"); ?></label>
                    <div class="control pull-left">
                        <input type="text" id="smtp-host" name="smtp-host" value="<?php echo $SMTPHost; ?>" style="margin-left: 15px;">
                    </div>
                </div>

                <!--SMTP port-->
                <div class="control-group">
                    <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("SMTP Post", "appointzilla"); ?></label>
                    <div class="control pull-left">
                        <input type="text" id="smtp-port" name="smtp-port" value="<?php echo $SMTPPort; ?>" style="margin-left: 15px;">
                    </div>
                </div>

                <!--SMTP email-->
                <div class="control-group">
                    <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("SMTP Email", "appointzilla"); ?></label>
                    <div class="control pull-left">
                        <input type="text" id="smtp-email" name="smtp-email" value="<?php echo $SMTPEmail; ?>" style="margin-left: 15px;">
                    </div>
                </div>

                <!--SMTP password-->
                <div class="control-group">
                    <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("SMTP Password", "appointzilla"); ?></label>
                    <div class="control pull-left">
                        <input type="password" id="smtp-password" name="smtp-password" value="<?php echo $SMTPPassword; ?>" style="margin-left: 15px;">
                    </div>
                </div>
            </div>

            <!--save button div-->
            <div class="control-group">
                <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
                <div class="control pull-left">
                <span style="margin-left: 15px;">
                    <button class="btn btn-sharp btn-success" id="save-notification-settings-btn" onclick="return SaveSettings('save-notification-settings');"><strong><i class="fa fa-save"></i> <?php _e("Save", "appointzilla"); ?></strong></button>
                    <div id="loading-img" style="display: none; margin-left: 15px;"><?php _e("Saving...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                </span>
                </div>
            </div>
        </div>
        <!--end show settings div-->


    </div>

    <script>
        jQuery(document).ready(function() {
            //on change enable notification
            jQuery("#enable-notification").change(function() {
                if(jQuery("#enable-notification").val() == "yes") {
                    jQuery("#show-settings").show();
                } else {
                    jQuery("#show-settings").hide();
                }
            });

            //on change notification type
            jQuery("#notification-type").change(function() {
                //if wp mail selected
                if(jQuery("#notification-type").val() == "wp-mail") {
                    jQuery("#wp-mail-div").show();
                } else {
                    jQuery("#wp-mail-div").hide();
                }

                //if php mail selected
                if(jQuery("#notification-type").val() == "php-mail") {
                    jQuery("#php-mail-div").show();
                } else {
                    jQuery("#php-mail-div").hide();
                }

                //if smtp mail selected
                if(jQuery("#notification-type").val() == "smtp-mail") {
                    jQuery("#smtp-mail-div").show();
                } else {
                    jQuery("#smtp-mail-div").hide();
                }
            });
        });

        //validating & saving notification settings
        function SaveSettings(Action){
            jQuery(".acb-error").hide();
            //alert(Action)
            var EnableNotification = jQuery("#enable-notification").val();
            var NotifyAdmin = jQuery("#notify-admin").val();
            var NotifyClient = jQuery("#notify-client").val();
            var NotificationType = jQuery("#notification-type").val();

            //if notification enabled
            if(EnableNotification == "yes") {

                //wp mail selected
                if(NotificationType == "wp-mail") {
                    var WpAdminEmail = jQuery("#wp-admin-email").val();
                    if(WpAdminEmail == '') {
                        jQuery("#wp-admin-email").after('<span class="acb-error">&nbsp;<strong><?php _e('Enter WP email' ,'appointzilla'); ?></strong></span>');
                        return false;
                    } else {
                        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if(regex.test(WpAdminEmail) == false ) {
                            jQuery("#wp-admin-email").after('<span class="acb-error">&nbsp;<strong><?php _e('Invalid email.' ,'appointzilla'); ?></strong></span>');
                            return false;
                        }
                    }
                    var PostData2 = "&WpAdminEmail=" + WpAdminEmail;
                }

                //php mail selected
                if(NotificationType == "php-mail") {
                    var PhpAdminEmail = jQuery("#php-admin-email").val();
                    if(PhpAdminEmail == '') {
                        jQuery("#php-admin-email").after('<span class="acb-error">&nbsp;<strong><?php _e('Enter PHP email' ,'appointzilla'); ?></strong></span>');
                        return false;
                    } else {
                        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if(regex.test(PhpAdminEmail) == false ) {
                            jQuery("#php-admin-email").after('<span class="acb-error">&nbsp;<strong><?php _e('Invalid email.' ,'appointzilla'); ?></strong></span>');
                            return false;
                        }
                    }
                    var PostData2 = "&PhpAdminEmail=" + PhpAdminEmail;
                }

                //SMTP mail selected
                if(NotificationType == "smtp-mail") {
                    var SMTPHost = jQuery("#smtp-host").val();
                    var SMTPPort = jQuery("#smtp-port").val();
                    var SMTPEmail = jQuery("#smtp-email").val();
                    var SMTPPassword = jQuery("#smtp-password").val();

                    if(SMTPHost == '') {
                        jQuery("#smtp-host").after('<span class="acb-error">&nbsp;<strong><?php _e('Enter SMTP host name.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    }

                    if(SMTPPort == '') {
                        jQuery("#smtp-port").after('<span class="acb-error">&nbsp;<strong><?php _e('Enter SMTP port number.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    } else {
                        if(isNaN(SMTPPort) == true) {
                            jQuery("#smtp-port").after('<span class="acb-error">&nbsp;<strong><?php _e('Invalid SMTP port number.' ,'appointzilla'); ?></strong></span>');
                            return false;
                        }
                    }

                    if(SMTPEmail == '') {
                        jQuery("#smtp-email").after('<span class="acb-error">&nbsp;<strong><?php _e('Enter SMTP email.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    } else {
                        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if(regex.test(SMTPEmail) == false ) {
                            jQuery("#smtp-email").after('<span class="acb-error">&nbsp;<strong><?php _e('Invalid SMTP email.' ,'appointzilla'); ?></strong></span>');
                            return false;
                        }
                    }

                    if(SMTPPassword == '') {
                        jQuery("#smtp-password").after('<span class="acb-error">&nbsp;<strong><?php _e('Enter SMTP password.' ,'appointzilla'); ?></strong></span>');
                        return false;
                    }

                    var PostData2 = "&SMTPHost=" + SMTPHost + "&SMTPPort=" + SMTPPort + "&SMTPEmail=" + SMTPEmail + "&SMTPPassword=" + SMTPPassword;
                }

                var PostData1 = "Action=" + Action + "&EnableNotification=" + EnableNotification + "&NotificationType=" + NotificationType;
                var PostData3 = "&NotifyAdmin=" + NotifyAdmin +"&NotifyClient=" + NotifyClient;
                var PostData = PostData1 + PostData2 + PostData3;

                jQuery("#save-notification-settings-btn").hide();
                jQuery("#loading-img").show();
                jQuery.ajax({
                    dataType : 'html',
                    type: 'POST',
                    url : location.href,
                    cache: false,
                    data : PostData,
                    complete : function() {  },
                    success: function() {
                        jQuery("#loading-img").hide();
                        jQuery("#save-notification-settings-btn").show();
                        alert('<?php _e("Notification settings successfully saved.", "appointzilla"); ?>');
                    }
                });
            }
        }
    </script>

<?php
if(isset($_POST['Action'])) {
    $Action = $_POST['Action'];
    if($Action == "save-notification-settings") {

        $EnableNotification = $_POST['EnableNotification'];
        if($EnableNotification == "yes") {
            // notification enabled
            $NotifyAdmin = $_POST['NotifyAdmin'];
            $NotifyClient = $_POST['NotifyClient'];
            $NotificationType = $_POST['NotificationType'];

            //wp-mail selected
            if($NotificationType == "wp-mail") {
                $WpAdminEmail = $_POST['WpAdminEmail'];
            } else {
                $WpAdminEmail = "";
            }

            //php-mail selected
            if($NotificationType == "php-mail") {
                $PhpAdminEmail = $_POST['PhpAdminEmail'];
            } else {
                $PhpAdminEmail = "";
            }

            //smtp-mail selected
            if($NotificationType == "smtp-mail") {
                $SMTPHost = $_POST['SMTPHost'];
                $SMTPPort = $_POST['SMTPPort'];
                $SMTPEmail = $_POST['SMTPEmail'];
                $SMTPPassword = $_POST['SMTPPassword'];
            } else {
                $SMTPHost = "";
                $SMTPPort = "";
                $SMTPEmail = "";
                $SMTPPassword = "";
            }
        } else {
            // notification disabled
            $NotifyAdmin = "no";
            $NotifyClient = "no";
            $NotificationType = "wp-mail";
            $WpAdminEmail =  "";
            $PhpAdminEmail = "";
            $SMTPHost = "";
            $SMTPPort = "";
            $SMTPEmail = "";
            $SMTPPassword = "";
        }
        $NotificationSettingsArray = array(
            'acb_enable_notification' => $EnableNotification,
            'acb_notify_admin' => $NotifyAdmin,
            'acb_notify_client' => $NotifyClient,
            'acb_notification_type' => $NotificationType,
            'acb_wp_admin_email' => $WpAdminEmail,
            'acb_php_admin_email' => $PhpAdminEmail,
            'acb_smtp_host_name' => $SMTPHost,
            'acb_smtp_port' => $SMTPPort,
            'acb_smtp_admin_email' => $SMTPEmail,
            'acb_smtp_admin_password' => $SMTPPassword
        );
        update_option('acb_notification_settings', serialize($NotificationSettingsArray));
    }
}
?>
<style>
    .acb-error {
        color: red;
    }
</style>