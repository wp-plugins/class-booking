<style>
    .table td {
        padding: 5px;
        vertical-align: middle;
    }
    .acberror {
        color: red;
    }
    .pagination a, .pagination span {
        line-height: 25px;
    }
    .pagination {
        margin: 0px 0;
    }
    .status{
        color: #0044cc;
        border-bottom: 1px dashed #0044cc;
        text-decoration: none;
    }
    .status:hover{
        color: #F38313;
        cursor: pointer;
    }
</style>
<div class="tooltip-demo">
<div class="page-header" style="margin: 20px 0px 0px;">
    <h3><?php _e("Manage Class Bookings", "appointzilla"); ?> <small><?php _e("List of bookings", "appointzilla"); ?></small></h3>
</div>

<!--<div id="add-new-client-btn-div">
    <button id="add-new-class-btn" onclick="return PostAction(-1, 'add-class-client');" class="btn btn-info btn-sharp"><i class="icon-white icon-plus"></i> <?php /*_e("Add New Client", "appointzilla"); */?></button>
    <br><br>
</div>-->

<div id="loading-img" style="display: none;"><i class="fa fa-spinner fa-spin fa-4x"></i></div>

<?php
/**
 * Loading Clients from DB
 */

global $wpdb;
$AllClassBookingSettings = unserialize(get_option('acb_class_booking_settings'));
if(count($AllClassBookingSettings)) {
    $DateFormat = $AllClassBookingSettings['acb_date_format'];
    $TimeFormat = $AllClassBookingSettings['acb_time_format'];
} else {
    $DateFormat = "d-m-Y";
    $TimeFormat = "h:i";
}

/**
 * Pagination Code
 */
$Start = 1;
$NoOfRows = 10;
$OffSet = 0;
$SortBy = "";
$FilterData = NULL;

//get all booking details
$ClassBookingsTable = $wpdb->prefix . "apcal_pre_class_bookings";
$AllClassBookings = $wpdb->get_results("SELECT * FROM `$ClassBookingsTable`");

//client table
$ClassClientsTable = $wpdb->prefix . "apcal_pre_class_clients";

// class table
$ClassesTable = $wpdb->prefix . "apcal_pre_classes";
?>
<div id="class-booking-list" class="row-fluid" style="margin-right: 10px;">
    <div class="span12">
        <table class="table table-bordered table-projects table-hover" style="background-color: #FFFFFF;">
            <thead>
                <tr>
                    <th colspan="8">&nbsp;</th>
                </tr>
                <tr>
                    <th style="text-align: center;">#</th>
                    <th><i class="fa fa-male icon-white"></i><?php _e("Booked By", "appointzilla"); ?></th>
                    <th><i class="fa fa-book icon-white"></i> <?php _e("Class", "appointzilla"); ?></th>
                    <th><i class="fa fa-calendar icon-white"></i> <?php _e("Joining Date", "appointzilla"); ?></th>
                    <th><i class="fa fa-clock-o icon-white"></i><?php _e("Time", "appointzilla"); ?></th>
                    <th><?php _e("Status", "appointzilla"); ?></th>
                    <th style="text-align: center;"><?php _e("Action", "appointzilla"); ?></th>
                    <th style="text-align: center;"><a href="#" rel="tooltip" data-placement="left" title="<?php _e('Select All','appointzilla'); ?>"><input type="checkbox" id="checkbox" name="checkbox[]" value="0" /></a></th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($AllClassBookings)) {
                    $Sn = 1;
                    foreach($AllClassBookings as $Booking) {
                        $Id = $Booking->id;
                        $ClassId = $Booking->class_id;
                        $InstructorId = $Booking->instructor_id;
                        $Name = $Booking->name;
                        $Email = $Booking->email;
                        $JoiningDate = date($DateFormat, strtotime($Booking->joining_date));
                        $Status = $Booking->status;
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $Sn; ?></td>
                            <td>
                                <?php
                                    echo ucwords($Name)."<br>";
                                    echo "<i class='fa fa-envelope-o icon-white''></i> ".strtolower($Email);
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($ClassId) {
                                        $ClassDetails = $wpdb->get_row("SELECT * FROM `$ClassesTable` WHERE `id` = '$ClassId'");
                                        if(count($ClassDetails)) {
                                            echo ucwords($ClassDetails->name);
                                            if($TimeFormat == 'h:i') $ATimeFormat = "g:ia"; else $ATimeFormat = "G:i";
                                            $Time = date($ATimeFormat, strtotime($ClassDetails->start_time)) ."-". date($ATimeFormat, strtotime($ClassDetails->end_time));
                                        }
                                    } else {
                                        _e('No class assigned.','appointzilla');
                                    }
                                ?>
                            </td>
                            <td><?php echo $JoiningDate; ?></td>
                            <td><?php echo $Time; ?></td>
                            <td>
                                <span id="status-span-<?php echo $Id; ?>" class="status" onclick="return ShowStatusList('<?php echo $Id; ?>');" title="<?php _e("Change Status", "appointzilla"); ?>"><?php echo ucfirst($Status); ?></span>
                                <div id="change-status-div-<?php echo $Id; ?>" style="display: none">
                                    <select id="change-status-<?php echo $Id; ?>" name="change-status" onchange="return ChangeStatus('<?php echo $Id; ?>', '<?php echo $Status; ?>');" style="width: 114px;">
                                        <option value="-1"><?php _e("Select Status","appointzilla"); ?></option>
                                        <option value="pending"><?php _e("Pending","appointzilla"); ?></option>
                                        <option value="approved"><?php _e("Approved","appointzilla"); ?></option>
                                        <option value="cancelled"><?php _e("Cancelled","appointzilla"); ?></option>
                                        <option value="completed"><?php _e("Completed","appointzilla"); ?></option>
                                    </select>
                                </div>
                                <div id="updating-img-<?php echo $Id; ?>" style="display: none;"><?php _e("Updating...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <button title="<?php _e("View","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, <?php echo $ClassId; ?>, <?php echo $InstructorId; ?>, 'view-class-booking');"><i class="icon-eye-open fa-lg icon-white"></i></button>
                                    <button title="<?php _e("Update","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, <?php echo $ClassId; ?>, <?php echo $InstructorId; ?>, 'update-class-booking');"><i class="icon-edit fa-lg icon-white"></i></button>
                                    <button title="<?php _e("Delete","appointzilla"); ?>"class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, '', '', 'delete-class-booking');"><i class="icon-remove fa-lg icon-white"></i></button>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a rel="tooltip" title="<?php _e("Select","appointzilla"); ?>"><input type="checkbox" id="checkbox" name="checkbox[]" value="<?php echo $Id; ?>" /></a>
                            </td>
                        </tr>
                        <?php
                        $Sn++;
                    }
                    ?>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="6">&nbsp;</td>
                        <td style="text-align: center;"><a href="#" rel="tooltip" data-placement="left" title="<?php _e('Delete All','appointzilla'); ?>"><button class="btn btn-mini btn-danger" onclick="return PostAction('-1', '', '', 'delete-all-class-bookings');"><i class="icon-white icon-trash"></i></button></a></td>
                    </tr>
                <?php
                } else {
                    ?>
                    <tr class="alert alert-block">
                        <td colspan="8"><?php echo _e("No booking record found.", "appointzilla"); ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <thead>
                <tr>
                    <th colspan="8">
                        <!--<div class="pagination" style="text-align: center">
                            <ul>
                                <li><a href="#"><i class="fa fa-backward"></i> Prev</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">Next <i class="fa fa-forward"></i></a></li>
                            </ul>
                        </div>-->
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    //select all
    jQuery(document).ready(function () {
        jQuery('#checkbox').click(function() {
            if(jQuery('#checkbox').is(':checked')) {
                jQuery(":checkbox").prop("checked", true);
            } else {
                jQuery(":checkbox").prop("checked", false);
            }
        });
    });

    //cancel
    function CancelBooking(){
        jQuery('#manage-class-booking-div').hide();
        jQuery('#class-booking-list').show();
    }

    //post action
    function PostAction(Id, ClassId, InstructorId, Action) {
        //alert(Action);
        //view booking
        if(Action == "view-class-booking") {
            //alert(Action);
            var PostData = "Action=" + Action + "&Id=" + Id + "&ClassId=" + ClassId + "&InstructorId=" + InstructorId;
        }

        //update booking
        if(Action == "update-class-booking") {
            //alert(Action);
            var PostData = "Action=" + Action + "&Id=" + Id + "&ClassId=" + ClassId + "&InstructorId=" + InstructorId;
        }

        //delete booking
        if(Action == "delete-class-booking") {
            //alert(Action);
            if(confirm( "<?php _e('Are you sure to delete this booking?','appointzilla'); ?>")) {
                var PostData = "Action=" + Action + "&Id=" + Id;
            } else {
                return false;
            }
        }

        //delete all
        if(Action == "delete-all-class-bookings") {
            //alert(Action);
            //var PostData = "Action=" + Action + "&Id=" + Id;
            //alert(Action);
            if(jQuery('input[name="checkbox[]"]:checked').length <= 0 ) {
                alert("<?php _e('Please select any booking.','appointzilla'); ?>");
                return false;
            }

            var Ids = jQuery('input:checkbox:checked').map(function() {
                return this.value;
            }).get();

            if(confirm( "<?php _e('Are you sure to delete selected bookings?','appointzilla'); ?>")) {
                var PostData = "Action=" + Action + "&Id=" + Ids;
            } else {
                return false;
            }
        }

        jQuery('#class-booking-list').hide();
        jQuery('#loading-img').show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                jQuery('#loading-img').hide();
                //view - update
                if( Action == "view-class-booking" || Action == "update-class-booking") {
                    data = jQuery(data).find('div#manage-class-booking');
                    jQuery('#manage-class-booking-div').show();
                    jQuery('#manage-class-booking-div').html(data);
                }

                //delete - delete-all
                if(Action == "delete-class-booking" || Action == "delete-all-class-bookings") {
                    if(Action == "delete-class-booking") {
                        alert("<?php _e('Booking deleted successfully.','appointzilla'); ?>");
                    }
                    if(Action == "delete-all-class-bookings") {
                        alert("<?php _e('Selected booking deleted successfully.','appointzilla'); ?>");
                    }
                    location.reload();
                }
            }
        });
    }

    function PerformClassBookingAction(Id, Action) {
        jQuery('.acberror').hide();
        var Name = jQuery('#name').val();
        var Email = jQuery('#email').val();
        var Phone = jQuery('#phone').val();
        var Note = jQuery('#sn').val();
        //save client
        if(Action == "perform-update-class-booking") {

            if (Name == "") {
                jQuery("#name").after("<span class='acberror'>&nbsp;<strong><?php _e('Client name required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                var Res = isNaN(Name);
                if(Res == false) {
                    jQuery("#name").after("<span class='acberror'>&nbsp;<strong><?php _e('Invalid client name.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
                var NameRegx = /^[a-zA-Z0-9- ]*$/;
                if(NameRegx.test(Name) == false) {
                    jQuery("#name").after("<span class='acberror'>&nbsp;<strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            if(Email == "") {
                jQuery("#email").after("<span class='acberror'>&nbsp;<strong><?php _e('Email required', 'AppointzillaClassBooking'); ?></strong></span>");
                return false;
            } else {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                var EmailRes = regex.test(Email);
                if(EmailRes == false ) {
                    jQuery("#email").after("<span class='acberror'>&nbsp;<strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }

            if(Phone == "") {
                jQuery("#phone").after("<span class='acberror'>&nbsp;<strong><?php _e('Phone Number required', 'AppointzillaClassBooking'); ?></strong></span>");
                return false;
            }

            var PostData =  "Action=" + Action  + "&Id=" + Id + "&Name=" + Name + "&Email=" + Email + "&Phone=" + Phone + "&Note=" + Note;
            jQuery('#manage-class-booking-div').hide();
            jQuery('#loading-img').show();
            jQuery.ajax({
                dataType : 'html',
                type: 'POST',
                url : location.href,
                cache: false,
                data : PostData,
                complete : function() {  },
                success: function(data) {
                    if(Action == "perform-update-class-booking") {
                        alert("<?php _e('Booking updated successfully.','appointzilla'); ?>");
                        jQuery('#loading-img').hide();
                        location.reload();
                    }
                }
            });
        }
    }

    //show status list
    function ShowStatusList(BookingId) {
        //jQuery( this ).hide();
        jQuery("#status-span-" + BookingId).hide();
        jQuery("#change-status-div-" + BookingId).show();
    }

    //change status
    function ChangeStatus(BookingId, PreviousStatus) {
        var Status = jQuery("#change-status-" + BookingId).val();
        var PostData = "Action=update-booking-status" + "&BookingId=" + BookingId +"&Status=" + Status + "&PreviousStatus=" + PreviousStatus;
        if(Status != "-1") {
            jQuery("#change-status-div-" + BookingId).hide();
            jQuery("#updating-img-" + BookingId).show();
            jQuery.ajax({
                dataType : 'html',
                type: 'POST',
                url : location.href,
                cache: false,
                data : PostData,
                complete : function() {  },
                success: function() {
                    alert("<?php _e('Booking status updated successfully.','appointzilla'); ?>");
                    jQuery("#updating-img-" + BookingId).hide();
                    jQuery("#status-span-" + BookingId).show();
                    Status = Status.substr(0, 1).toUpperCase() + Status.substr(1);
                    jQuery("#status-span-" + BookingId).html(Status);
                }
            });
        }
    }
</script>

<?php require_once("manage-bookings-update.php"); ?>
<div id="manage-class-booking-div" style="display: none;"></div>
</div>

<?php
//update booking status
if(isset($_POST['Action'])) {
    $Action = $_POST['Action'];
    if($Action == "update-booking-status") {
        print_r($_POST);
        $BookingId = $_POST['BookingId'];
        $Status = $_POST['Status'];
        $PreviousStatus = $_POST['PreviousStatus'];

        //update status
        if($wpdb->query("UPDATE `$ClassBookingsTable` SET `status` = '$Status' WHERE `id` = '$BookingId';")) {

            //get booking details required for notification
            $BookingDetail = $wpdb->get_row("SELECT * FROM `$ClassBookingsTable` WHERE `id` = '$BookingId'");
            if(count($BookingDetail)) {
                //print_r($BookingDetail); echo "<br>";
                $ClassId = $BookingDetail->class_id;
                $InstructorId = $BookingDetail->instructor_id;
                $ClientEmail = $BookingDetail->email;
                $ClientData = $wpdb->get_row("SELECT `id` FROM `$ClassClientsTable` WHERE `email` = '$ClientEmail'");
                $ClientId = $ClientData->id;

                //include notification class
                require_once('notification-class.php');
                $Notification = new Notification();
                $On = $Status;
                $BlogName =  get_bloginfo('name');

                //get notification settings
                $AllNotificationSettings = unserialize(get_option('acb_notification_settings'));
                $NotifyAdmin = $AllNotificationSettings['acb_notify_admin'];
                $NotifyInstructor = $AllNotificationSettings['acb_notify_instructor'];
                $NotifyClient = $AllNotificationSettings['acb_notify_client'];

                //send notification - but first check if appointment status is already same
                if( $Status == "pending" && $PreviousStatus != $Status) {
                    //notify client
                    if($NotifyClient == "yes") {
                        $Notification->NotifyClient($On, $BookingId, $ClassId, $InstructorId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }

                    //notify instructor
                    if($NotifyInstructor == "yes") {
                        $Notification->NotifyInstructor($On, $BookingId, $ClassId, $InstructorId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }
                }

                if( $Status == "approved" && $PreviousStatus != $Status) {
                    //notify client
                    if($NotifyClient == "yes") {
                        $Notification->NotifyClient($On, $BookingId, $ClassId, $InstructorId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }

                    //notify instructor
                    if($NotifyInstructor == "yes") {
                        $Notification->NotifyInstructor($On, $BookingId, $ClassId, $InstructorId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }
                }

                if( $Status == "cancelled" && $PreviousStatus != $Status) {
                    //notify client
                    if($NotifyClient == "yes") {
                        $Notification->NotifyClient($On, $BookingId, $ClassId, $InstructorId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }

                    //notify instructor
                    if($NotifyInstructor == "yes") {
                        $Notification->NotifyInstructor($On, $BookingId, $ClassId, $InstructorId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                    }
                }
            }
        }
    }
}
?>