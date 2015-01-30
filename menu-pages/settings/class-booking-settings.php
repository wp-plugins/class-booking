<?php
$AllClassBookingSettings = unserialize(get_option('acb_class_booking_settings'));
if(count($AllClassBookingSettings)) {
    $BookingStatus = $AllClassBookingSettings['acb_booking_status'];
    $AdminTimezoneId = $AllClassBookingSettings['acb_admin_timezone'];
    $DateFormat = $AllClassBookingSettings['acb_date_format'];
    $TimeFormat = $AllClassBookingSettings['acb_time_format'];
} else {
    $BookingStatus = "yes";
    $AdminTimezoneId = 10;
    $DateFormat = "d-m-Y";
    $TimeFormat = "h:i";
}
?>
<div class="borBox form-horizontal">
    <h3 style="margin-left: 30px;"><?php _e("Class Booking Settings", "appointzilla"); ?></h3>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Show Booking Status", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="booking-status" name="booking-status" style="margin-left: 15px;">
                <option <?php if($BookingStatus == "yes") echo "selected='selected'"; ?>  value="yes"><?php _e("Yes", "appointzilla"); ?></option>
                <option <?php if($BookingStatus == "no") echo "selected='selected'"; ?>  value="no"><?php _e("No", "appointzilla"); ?></option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Date Format", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="date-format" name="date-format" style="margin-left: 15px;">
                <option value="d-m-Y" <?php if($DateFormat == 'd-m-Y') echo "selected"; ?>><?php echo  _e('DD-MM-YYYY', 'appointzilla'); ?></option>
                <option value="m-d-Y" <?php if($DateFormat == 'm-d-Y') echo "selected"; ?>><?php echo _e('MM-DD-YYYY', 'appointzilla'); ?></option>
                <option value="Y-m-d"<?php if($DateFormat == 'Y-m-d') echo "selected"; ?>><?php echo  _e('YYYY-MM-DD', 'appointzilla'); ?></option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Time Format", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="time-format" name="time-format" style="margin-left: 15px;">
                <option value="h:i" <?php if($TimeFormat == 'h:i') echo "selected"; ?>><?php _e('12 Hour Time', 'appointzilla'); ?></option>
                <option value="H:i" <?php if($TimeFormat == 'H:i') echo "selected"; ?>><?php _e('24 Hour Time', 'appointzilla'); ?></option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
        <div class="control pull-left">
            <span style="margin-left: 15px;">
                <button class="btn btn-sharp btn-success" id="save-class-booking-settings" onclick="return SaveSettings('save-class-booking-settings');"><strong><i class="fa fa-save"></i> <?php _e("Save", "appointzilla"); ?></strong></button>
                <div id="loading-img" style="display: none; margin-left: 15px;"><?php _e("Saving...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
            </span>
        </div>
    </div>
</div>

<script>
    function SaveSettings(Action){
        //alert(Action)
        var BookingStatus = jQuery("#booking-status").val();
        var DateFormat = jQuery("#date-format").val();
        var TimeFormat = jQuery("#time-format").val();
        var PostData1 = "Action=" + Action + "&BookingStatus=" + BookingStatus;
        var PostData2 = "&DateFormat=" + DateFormat + "&TimeFormat=" + TimeFormat;
        var PostData = PostData1 + PostData2;

        jQuery("#save-class-booking-settings").hide();
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
                jQuery("#save-class-booking-settings").show();
                alert('<?php _e("Class booking settings successfully saved.", "appointzilla"); ?>');
            }
        });
    }
</script>

<?php
if(isset($_POST['Action'])) {
    $Action = $_POST['Action'];
    if($Action == "save-class-booking-settings") {
        $BookingStatus = $_POST['BookingStatus'];
        $AdminTimezone = $_POST['AdminTimezone'];
        $DateFormat = $_POST['DateFormat'];
        $TimeFormat = $_POST['TimeFormat'];

        $AllClassBookingSettingsArray = array(
            'acb_booking_status' => $BookingStatus,
            'acb_admin_timezone' => $AdminTimezone,
            'acb_date_format' => $DateFormat,
            'acb_time_format' => $TimeFormat
        );
        update_option('acb_class_booking_settings', serialize($AllClassBookingSettingsArray));
    }
}
?>