<h3 style="margin-left: 30px;"><?php _e("Notification Message", "appointzilla"); ?></h3>

<script src="<?php echo plugins_url('/js/jquery-1.8.0.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/jquery.min.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/bootstrap.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/bootstrap.min.js', __FILE__); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('/js/bootstrap-tab.js', __FILE__); ?>" type="text/javascript"></script>

<div class="tabbable" id="myTabs">
    <ul class="nav nav-tabs tabs-left">
        <li class="active"><a href="#tab1" data-toggle="tab"><strong><?php _e("Client Notifications","appointzilla"); ?></a></strong></li>
        <li><a href="#tab2" data-toggle="tab"><strong><?php _e("Admin Notifications","appointzilla"); ?></strong></a></li>
    </ul>

    <div class="tab-content" style="border-radius: 4px 0 4px 0;">
        <!--notification message for client-->
        <div class="tab-pane active" id="tab1">
            <!--vertical tabs for client-->
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs nav-pills nav-stacked">
                    <li class="active"><a data-toggle="tab" href="#lA"><i class="icon-chevron-right"></i> <?php _e("New Booking","appointzilla"); ?></a></li>
                    <li><a data-toggle="tab" href="#lB"><i class="icon-chevron-right"></i> <?php _e("Approve Booking","appointzilla"); ?></a></li>
                    <li><a data-toggle="tab" href="#lC"><i class="icon-chevron-right"></i> <?php _e("Cancel Booking","appointzilla"); ?></a></li>
                    <li>
                        <br>
                        <div style="padding: 5px;">
                            <button class="btn btn-small btn-inverse" type="button"><?php _e('Use Below Tags in Message','appointzilla'); ?></button><br>
                            <?php _e('Client Name','appointzilla'); ?> - [client-name]<br>
                            <?php _e('Client Email','appointzilla'); ?> - [client-email]<br>
                            <?php _e('Client Phone','appointzilla'); ?> - [client-phone]<br>
                            <?php _e('Client Special Note','appointzilla'); ?> - [client-sn]<br>
                            <?php _e('Class Name','appointzilla'); ?> - [class-name]<br>
                            <?php _e('Booking Date','appointzilla'); ?> - [book-date]<br>
                            <?php _e('Booking Status','appointzilla'); ?> - [book-status]<br>
                            <?php _e('Booking Time','appointzilla'); ?> - [book-time]<br>
                            <?php _e('Booking Key','appointzilla'); ?> - [book-key]<br>
                            <?php _e('Booking Note','appointzilla'); ?> - [book-note]<br>
                            <?php _e('Blog Name','appointzilla'); ?> - [blog-name]<br>
                            <?php _e('Blog Url','appointzilla'); ?> - [blog-url]<br>
                            <?php _e('Blog Login Url','appointzilla'); ?> - [blog-login-url]
                        </div>
                    </li>
                </ul>
                <div class="tab-content">

                    <!--notify client on booking booking-->
                    <div id="lA" class="tab-pane active">
                        <h4><?php _e('Notify Client On New Booking','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="booking_client_subject" type="text" id="booking_client_subject" style="width: 600px;" value="<?php echo get_option('on_booking_client_subject'); ?>"></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td>
                                    <textarea name="booking_client_body" id="booking_client_body" style="height: 320px; width: 600px;"><?php echo get_option('on_booking_client_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" id="booking_client_message" name="booking_client_message" onclick="return ClickOnSave('booking_client');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-booking_client" style="display: none;"><?php _e("Saving...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!--notify client on approve booking-->
                    <div id="lB" class="tab-pane">
                        <h4><?php _e('Notify Client On Approve Booking','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="approve_client_subject" type="text" id="approve_client_subject" value="<?php echo get_option('on_approve_client_subject'); ?>" style="width: 600px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="approve_client_body" id="approve_client_body" style="height: 320px; width: 600px;"><?php echo get_option('on_approve_client_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td><button type="button" class="btn btn-primary" id="approve_client_message" name="approve_client_message" onclick="return ClickOnSave('approve_client');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-approve_client" style="display: none;"><?php _e("Saving...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!--notify client on cancel booking-->
                    <div id="lC" class="tab-pane">
                        <h4><?php _e('Notify Client On Cancel Booking','appointzilla'); ?></h4>
                        <table width="100%" class="table">
                            <tr>
                                <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                                <td width="1%"><strong>:</strong></td>
                                <td width="94%"><input name="cancel_client_subject" type="text" id="cancel_client_subject" value="<?php echo get_option('on_cancel_client_subject'); ?>" style="width: 600px;" /></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                                <td><strong>:</strong></td>
                                <td><textarea name="cancel_client_body" id="cancel_client_body" style="height: 320px; width: 600px;"><?php echo get_option('on_cancel_client_body'); ?></textarea></td>
                            </tr>
                            <tr>
                                <th scope="row">&nbsp;</th>
                                <td>&nbsp;</td>
                                <td>
                                    <button type="button" class="btn btn-primary" id="cancel_client_message" name="cancel_client_message" onclick="return ClickOnSave('cancel_client');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                                    <div id="loading-cancel_client" style="display: none;"><?php _e("Saving...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--End of client notification tab-->


        <!--notification message for admin-->
        <div class="tab-pane" id="tab2">
            <table width="100%" class="table">
                <tr>
                    <th colspan="3" scope="row"><h4><?php _e('Notify Admin On New Booking','appointzilla'); ?></h4></th>
                </tr>
                <tr>
                    <th width="5%" scope="row"><?php _e('Subject','appointzilla'); ?></th>
                    <td width="1%"><strong>:</strong></td>
                    <td width="94%"><input name="booking_admin_subject" type="text" id="booking_admin_subject" style="width: 600px;" value="<?php echo get_option('on_booking_admin_subject'); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Body','appointzilla'); ?></th>
                    <td><strong>:</strong></td>
                    <td>
                        <textarea name="booking_admin_body" id="booking_admin_body" style="height: 320px; width: 600px;"><?php echo get_option('on_booking_admin_body'); ?></textarea>
                        <div style="float:right; border:0px solid #000000; width:290px; height:auto; margin-right:80px;" >
                            <button class="btn btn-small btn-inverse" type="button"><?php _e('Use Below Tags in Message','appointzilla'); ?></button><br>
                            <?php _e('Client Name','appointzilla'); ?> - [client-name]<br>
                            <?php _e('Client Email','appointzilla'); ?> - [client-email]<br>
                            <?php _e('Client Phone','appointzilla'); ?> - [client-phone]<br>
                            <?php _e('Client Special Note','appointzilla'); ?> - [client-sn]<br>
                            <?php _e('Class Name','appointzilla'); ?> - [class-name]<br>
                            <?php _e('Booking Date','appointzilla'); ?> - [book-date]<br>
                            <?php _e('Booking Status','appointzilla'); ?> - [book-status]<br>
                            <?php _e('Booking Time','appointzilla'); ?> - [book-time]<br>
                            <?php _e('Booking Key','appointzilla'); ?> - [book-key]<br>
                            <?php _e('Booking Note','appointzilla'); ?> - [book-note]<br>
                            <?php _e('Blog Name','appointzilla'); ?> - [blog-name]<br>
                            <?php _e('Blog Url','appointzilla'); ?> - [blog-url]<br>
                            <?php _e('Blog Login Url','appointzilla'); ?> - [blog-login-url]
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">&nbsp;</th>
                    <td>&nbsp;</td>
                    <td>
                        <button type="button" class="btn btn-primary" id="booking_admin_message" name="booking_admin_message" onclick="return ClickOnSave('booking_admin');"><i class="icon-ok icon-white"></i> <?php _e('Save','appointzilla'); ?></button>
                        <div id="loading-booking_admin" style="display: none;"><?php _e("Saving...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                    </td>
                </tr>
            </table>
        </div>
        <!--End of admin notification tab-->
    </div>
</div>
<!--End of tabs-->

<script>
    //tabs js code
    jQuery('#tabAll').click(function(){
        jQuery('#tabAll').addClass('active');
        jQuery('.tab-pane').each(function(){
            jQuery('#myTabs li').removeClass('active');
            jQuery(this).addClass('active');
        });
    });

    //save notification messages
    function ClickOnSave(MessageToSave) {
        var Subject = jQuery("#" + MessageToSave + "_subject" ).val();
        var Body = jQuery("#" + MessageToSave + "_body" ).val();
        var DataString = "action=" + MessageToSave + "&Subject=" + Subject + "&Body=" + Body;
        jQuery('#loading-' + MessageToSave).show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : DataString,
            complete : function() { },
            success: function(data) {
                jQuery('#loading-' + MessageToSave).hide();
                alert("<?php _e('Notification message successfully saved.','appointzilla'); ?>");
            }
        });
    }
</script>

<?php
// save notification message
if(isset($_POST['action'])) {
    $Action = $_POST['action'];
    $Subject = $_POST['Subject'];
    $Body = $_POST['Body'];
    if( $Action == "booking_client") {
        update_option('on_booking_client_subject', $Subject);
        update_option('on_booking_client_body', $Body);
    }

    // client on approve
    if( $Action == "approve_client" ) {
        update_option('on_approve_client_subject', $Subject);
        update_option('on_approve_client_body', $Body);
    }

    // client on cancel
    if( $Action == "cancel_client" ) {
        update_option('on_cancel_client_subject', $Subject);
        update_option('on_cancel_client_body', $Body);

    }

    // admin on booking
    if( $Action == "booking_admin" ) {
        update_option('on_booking_admin_subject', $Subject);
        update_option('on_booking_admin_body', $Body);
    }

    // instructor on booking
    if( $Action == "booking_instructor" ) {
        update_option('on_booking_instructor_subject', $Subject);
        update_option('on_booking_instructor_body', $Body);
    }

    // instructor on approve
    if( $Action ==  "approve_instructor" ) {
        update_option('on_approve_instructor_subject', $Subject);
        update_option('on_approve_instructor_body', $Body);
    }

    // instructor on cancel
    if( $Action ==  "cancel_instructor" ) {
        update_option('on_cancel_instructor_subject', $Subject);
        update_option('on_cancel_instructor_body', $Body);
    }
}
?>