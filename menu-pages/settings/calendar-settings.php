<?php
$AllCalendarSettings = unserialize(get_option('acb_calendar_settings'));
if(count($AllCalendarSettings)) {
    $StartDay = $AllCalendarSettings['acb_calendar_start_day'];
    $View = $AllCalendarSettings['acb_calendar_view'];
    $SlotTime = $AllCalendarSettings['acb_calendar_slot_time'];
    $DayStartTime = $AllCalendarSettings['acb_day_start_time'];
    $DayEndTime = $AllCalendarSettings['acb_day_end_time'];
    $BookingInstructions = $AllCalendarSettings['acb_booking_instructions'];
    $BookingButtonText = $AllCalendarSettings['acb_booking_button_text'];
    $ThankYouMessage = $AllCalendarSettings['acb_thank_you_message'];
} else {
    $StartDay = 1;
    $View = "month";
    $SlotTime = 10;
    $DayStartTime = "10:00 AM";
    $DayEndTime = "5:00 PM";
    $BookingInstructions = "Put your booking instructions here.<br>Or you can save It blank in case of nothing want to display.";
    $BookingButtonText = "Schedule A Class";
    $ThankYouMessage = __("Your booking has been completed. Thanks you for booking with us.", "appointzilla");
}
?>
<?php _e("", "appointzilla"); ?>
<div class="borBox form-horizontal">
    <h3 style="margin-left: 30px;"><?php _e("Calendar Settings", "appointzilla"); ?></h3>
    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Calendar Start Day", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="start-day" name="start-day" style="margin-left: 15px;">
                <option <?php if($StartDay == 1) echo "selected='selected'"; ?>  value="1"><?php _e("Monday", "appointzilla"); ?></option>
                <option <?php if($StartDay == 2) echo "selected='selected'"; ?> value="2"><?php _e("Tuesday", "appointzilla"); ?></option>
                <option <?php if($StartDay == 3) echo "selected='selected'"; ?> value="3"><?php _e("Wednesday", "appointzilla"); ?></option>
                <option <?php if($StartDay == 4) echo "selected='selected'"; ?> value="4"><?php _e("Thursday", "appointzilla"); ?></option>
                <option <?php if($StartDay == 5) echo "selected='selected'"; ?> value="5"><?php _e("Friday", "appointzilla"); ?></option>
                <option <?php if($StartDay == 6) echo "selected='selected'"; ?> value="6"><?php _e("Saturday", "appointzilla"); ?></option>
                <option <?php if($StartDay == 0) echo "selected='selected'"; ?> value="0"><?php _e("Sunday", "appointzilla"); ?></option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Calendar View", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="view" name="view" style="margin-left: 15px;">
                <option <?php if($View == "agendaDay") echo "selected='selected'"; ?> value="agendaDay"><?php _e("Day", "appointzilla"); ?></option>
                <option <?php if($View == "agendaWeek") echo "selected='selected'"; ?> value="agendaWeek"><?php _e("Week", "appointzilla"); ?></option>
                <option <?php if($View == "month") echo "selected='selected'"; ?> value="month"><?php _e("Month", "appointzilla"); ?></option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Calendar Slot Time", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="slot-time" name="slot-time" style="margin-left: 15px;">
                <option <?php if($SlotTime == 5) echo "selected='selected'"; ?> value="5"><?php _e("5 Minute", "appointzilla"); ?></option>
                <option <?php if($SlotTime == 10) echo "selected='selected'"; ?> value="10"><?php _e("10 Minute", "appointzilla"); ?></option>
                <option <?php if($SlotTime == 15) echo "selected='selected'"; ?> value="15"><?php _e("15 Minute", "appointzilla"); ?></option>
                <option <?php if($SlotTime == 30) echo "selected='selected'"; ?> value="30"><?php _e("30 Minute", "appointzilla"); ?></option>
                <option <?php if($SlotTime == 60) echo "selected='selected'"; ?> value="60"><?php _e("60 Minute", "appointzilla"); ?></option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Calendar Day Start Time", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="day-start-time" name="day-start-time" style="margin-left: 15px;">
                <?php
                $BizStartTime = strtotime("01:00 AM");
                $BizEndTime = strtotime("11:00 PM");
                //making 60min slots
                for( $i = $BizStartTime; $i <= $BizEndTime; $i += (60*(60))) {
                    if( $DayStartTime && $DayStartTime == date('g:i A', $i) ) $Selected = 'selected'; else $Selected='';
                    echo "<option $Selected value='". date('g:i A', $i)."'>". date('g:i A', $i) ."</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Calendar Day End Time", "appointzilla"); ?></label>
        <div class="control pull-left">
            <select id="day-end-time" name="day-end-time" style="margin-left: 15px;">
                <?php
                $BizStartTime = strtotime("01:00 AM");
                $BizEndTime = strtotime("11:00 PM");
                //making 60min slots
                for( $i = $BizStartTime; $i <= $BizEndTime; $i += (60*(60))) {
                    if( $DayEndTime && $DayEndTime == date('g:i A', $i) ) $Selected = 'selected'; else $Selected='';
                    echo "<option $Selected value='". date('g:i A', $i)."'>". date('g:i A', $i) ."</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Booking Button Text", "appointzilla"); ?></label>
        <div class="control pull-left">
            <input type="text" style="margin-left: 15px;" name="booking-button-text" id="booking-button-text" value="<?php echo $BookingButtonText; ?>">
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Booking Instructions", "appointzilla"); ?></label>
        <div class="control pull-left">
            <div style="margin-left: 15px;">
                <b><?php _e("You can use only these HTML tags", "appointzilla"); ?>:</b><br>&lt;p&gt;&lt;/p&gt;, &lt;h1&gt;&lt;/h1&gt;, &lt;h2&gt;&lt;/h2&gt;, &lt;h3&gt;&lt;/h3&gt;, &lt;h4&gt;&lt;/h4&gt;, <br>&lt;h5&gt;&lt;/h5&gt;, &lt;h6&gt;&lt;/h6&gt;, &lt;strong&gt;&lt;/strong&gt;, &lt;em&gt;&lt;/em&gt; &lt;br&gt;,&lt;/br&gt;
            </div>
            <textarea style="width: 500px; height: 80px; margin-left: 15px;" name="booking-instructions" id="booking-instructions"><?php echo $BookingInstructions; ?></textarea>
        </div>
    </div>

    <div class="control-group">
        <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Thank You Message", "appointzilla"); ?></label>
        <div class="control pull-left">
            <textarea style="width: 500px; height: 80px; margin-left: 15px;" name="thank-you-message" id="thank-you-message"><?php echo $ThankYouMessage; ?></textarea>
        </div>
    </div>

    <div class="control-group">
        <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
        <div class="control pull-left">
            <span style="margin-left: 15px;">
                <button class="btn btn-sharp btn-success" id="create-instructor-btn" onclick="return SaveSettings('save-calendar-settings');"><strong><i class="fa fa-save"></i> <?php _e("Save", "appointzilla"); ?></strong></button>
                <div id="loading-img" style="display: none; margin-left: 15px;"><?php _e("Saving...", "appointzilla"); ?><i class="fa fa-spinner fa-spin fa-2x"></i></div>
            </span>
        </div>
    </div>
</div>

<script>
    function SaveSettings(Action){
        //alert(Action)
        var StartDay = jQuery("#start-day").val();
        var View = jQuery("#view").val();
        var SlotTime = jQuery("#slot-time").val();
        var DayStartTime = jQuery("#day-start-time").val();
        var DayEndTime = jQuery("#day-end-time").val();
        var BookingInstructions = jQuery("#booking-instructions").val();
        var BookingButtonText = jQuery("#booking-button-text").val();
        var ThankYouMessage = jQuery("#thank-you-message").val();

        var PostData1 = "Action=" + Action + "&StartDay=" + StartDay + "&View=" + View + "&SlotTime=" + SlotTime;
        var PostData2 = "&DayStartTime=" + DayStartTime + "&DayEndTime=" + DayEndTime + "&BookingInstructions=" + BookingInstructions + "&BookingButtonText=" + BookingButtonText;
        var PostData3 = "&ThankYouMessage=" + ThankYouMessage;
        var PostData = PostData1 + PostData2 + PostData3;
        //alert(StartDay)

        jQuery("#create-instructor-btn").hide();
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
                jQuery("#create-instructor-btn").show();
                alert('<?php _e("Calendar settings successfully saved.", "appointzilla"); ?>');
            }
        });
    }
</script>

<?php
if(isset($_POST['Action'])) {
    $Action = $_POST['Action'];
    if($Action == "save-calendar-settings") {
        $StartDay = $_POST['StartDay'];
        $View = $_POST['View'];
        $SlotTime = $_POST['SlotTime'];
        $DayStartTime = $_POST['DayStartTime'];
        $DayEndTime = $_POST['DayEndTime'];
        $BookingInstructions = $_POST['BookingInstructions'];
        $BookingButtonText = $_POST['BookingButtonText'];
        $ThankYouMessage = $_POST['ThankYouMessage'];

        $CalendarSettingsArray = array(
            'acb_calendar_start_day' => $StartDay,
            'acb_calendar_view' => $View,
            'acb_calendar_slot_time' => $SlotTime,
            'acb_day_start_time' => $DayStartTime,
            'acb_day_end_time' => $DayEndTime,
            'acb_booking_instructions' => $BookingInstructions,
            'acb_booking_button_text' => $BookingButtonText,
            'acb_thank_you_message' => $ThankYouMessage,
        );
        update_option('acb_calendar_settings', serialize($CalendarSettingsArray));
    }
}
?>