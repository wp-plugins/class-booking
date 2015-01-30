<?php
add_shortcode( 'APCLASS', 'class_booking_short_code' );
function class_booking_short_code() {
    global $wpdb;
    if(get_locale()) {
        $language = get_locale();
        if($language) { define('L_LANG',$language); }
    }

    //Load Calendar Settings
    $AllCalendarSettings = unserialize(get_option('acb_calendar_settings'));
    if(count($AllCalendarSettings)) {
        $StartDay = $AllCalendarSettings['acb_calendar_start_day'];
        $View = $AllCalendarSettings['acb_calendar_view'];
        $DayStartTime = $AllCalendarSettings['acb_day_start_time'];
        $DayEndTime = $AllCalendarSettings['acb_day_end_time'];
        $BookingInstructions = $AllCalendarSettings['acb_booking_instructions'];
        $ThankYouMessage = $AllCalendarSettings['acb_thank_you_message'];
    } else {
        $StartDay = 1;
        $View = "month";
        $DayStartTime = "10:00 AM";
        $DayEndTime = "5:00 PM";
        $BookingInstructions = "Put your booking instructions here.<br>Or you can save It blank in case of nothing want to display.";
        $ThankYouMessage = __("Your booking has been completed. Thanks you for booking with us.", "appointzilla");
    }

    //Load Class Booking Settings
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


     //Get Currency Details
    $PaymentCurrency = 1;
    $CurrencyTable = $wpdb->prefix . 'apcal_pre_currency';
    $Currency_sql = $wpdb->prepare("SELECT `symbol`, `code` FROM `$CurrencyTable` WHERE `id` = %d", $PaymentCurrency);
    $Currency = $wpdb->get_row($Currency_sql);
    if(count($Currency)) {
        $CurrencySymbol = $Currency->symbol;
        $CurrencyCode = $Currency->code;
    } else {
        $CurrencySymbol = "&#36;";
        $CurrencyCode = "USD";
    }

    //Load all classes schedules
    $ClassesTable = $wpdb->prefix . "apcal_pre_classes";
    $ClassesCategoriesTable = $wpdb->prefix . "apcal_pre_classes_categories";
    $InstructorsTable = $wpdb->prefix . "apcal_pre_instructors";
    $InstructorsGroupsTable = $wpdb->prefix . "apcal_pre_instructors_groups";
    $ClassBookingsTable = $wpdb->prefix . "apcal_pre_class_bookings";
    $PaymentsTable = $wpdb->prefix . "apcal_pre_payments";
    $AllInstructorsDetails = array();

    // all classes
    $AllClassesSQL = "SELECT * FROM `$ClassesTable`";
    $AllClasses = $wpdb->get_results($AllClassesSQL);
    //print_r($AllClasses); echo "<br>";
    //print_r(count($AllClasses)); echo "<br>";

    // all class categories
    $AllClassesCategoriesSQL = "SELECT * FROM `$ClassesCategoriesTable`";
    $AllClassesCategories = $wpdb->get_results($AllClassesCategoriesSQL);


     //load big calendar (full calendar)
    ?>
    <style>
        .acberror {
            color: red;
        }
    </style>
    <script type='text/javascript'>
    jQuery(document).ready(function() {
        jQuery('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month, agendaWeek, agendaDay'
            },
            allDayText: '<?php _e("All Day","appointzilla"); ?>',
            columnFormat: {
                month: 'ddd',
                week: 'ddd d/M',
                day: 'dddd d/M'
            },
            editable: false,
            weekends: true,
            timeFormat: <?php if($TimeFormat == 'h:i') echo "'h:mm-h:mma '"; else echo "'H:mm-H:mm '"; ?>,
            axisFormat: <?php if($TimeFormat == 'h:i') echo "'hh:mma'"; else echo "'HH:mm'"; ?>,
            firstDay: <?php echo $StartDay; ?>,
            slotMinutes: 15<?php //echo $SlotTime; ?>,
            defaultView: '<?php echo $View; ?>',
            minTime: '<?php echo date("G:i", strtotime($DayStartTime)); ?>',
            maxTime: '<?php echo  date("G:i", strtotime($DayEndTime)); ?>',
            monthNames: ["<?php _e("January", "appointzilla"); ?>","<?php _e("February", "appointzilla"); ?>","<?php _e("March", "appointzilla"); ?>","<?php _e("April", "appointzilla"); ?>","<?php _e("May", "appointzilla"); ?>","<?php _e("June", "appointzilla"); ?>","<?php _e("July", "appointzilla"); ?>", "<?php _e("August", "appointzilla"); ?>", "<?php _e("September", "appointzilla"); ?>", "<?php _e("October", "appointzilla"); ?>", "<?php _e("November", "appointzilla"); ?>", "<?php _e("December", "appointzilla"); ?>" ],
            monthNamesShort: ["<?php _e("Jan", "appointzilla"); ?>","<?php _e("Feb", "appointzilla"); ?>","<?php _e("Mar", "appointzilla"); ?>","<?php _e("Apr", "appointzilla"); ?>","<?php _e("May", "appointzilla"); ?>","<?php _e("Jun", "appointzilla"); ?>","<?php _e("Jul", "appointzilla"); ?>","<?php _e("Aug", "appointzilla"); ?>","<?php _e("Sept", "appointzilla"); ?>","<?php _e("Oct", "appointzilla"); ?>","<?php _e("nov", "appointzilla"); ?>","<?php _e("Dec", "appointzilla"); ?>"],
            dayNames: ["<?php _e("Sunday", "appointzilla"); ?>","<?php _e("Monday", "appointzilla"); ?>","<?php _e("Tuesday", "appointzilla"); ?>","<?php _e("Wednesday", "appointzilla"); ?>","<?php _e("Thursday", "appointzilla"); ?>","<?php _e("Friday", "appointzilla"); ?>","<?php _e("Saturday", "appointzilla"); ?>"],
            dayNamesShort: ["<?php _e("Sun", "appointzilla"); ?>","<?php _e("Mon", "appointzilla"); ?>", "<?php _e("Tue", "appointzilla"); ?>", "<?php _e("Wed", "appointzilla"); ?>", "<?php _e("Thus", "appointzilla"); ?>", "<?php _e("Fri", "appointzilla"); ?>", "<?php _e("Sat", "appointzilla"); ?>"],
            buttonText: {
                today: "<?php _e("Today", "appointzilla"); ?>",
                day: "<?php _e("Day", "appointzilla"); ?>",
                week:"<?php _e("Week", "appointzilla"); ?>",
                month:"<?php _e("Month", "appointzilla"); ?>"
            },
            selectable: true,
            selectHelper: false,
            select: function(start, end, allDay) {

            },

            events: [
                <?php

                 //Loading recurring classes
                if(count($AllClasses)) {
                    foreach($AllClasses as $Class) {
                        $ClassId = $Class->id;
                        $Name = $Class->name;
                        $StartDate = $Class->start_date;
                        $EndDate = $Class->end_date;

                        $StartTime = date("H, i", strtotime($Class->start_time));
                        $EndTime = date("H, i", strtotime($Class->end_time));
                        $StartDateTs = strtotime($StartDate);  //time stamp value
                        $EndDateTs =  strtotime($EndDate);  //time stamp value

                        $Capacity = $Class->capacity;
                        $Repeat = $Class->repeat;
                        $RepeatAmount = $Class->repeat_amount;
                        $Availability = $Class->availability;
                        $Color = $Class->color;

                        //check service available
                        if($Availability == "yes") {
                            $AllBetweenDates = array();
                            $AllWeeksDates = array();
                            $AllBiWeeksDates = array();
                            $AllMonthsDates = array();
                            $AllYearsDates = array();
                            $AllNoRepeatDates = array();

                            //calculate start-end date's between dates
                            $DayDifference = 1;
                            for($i = $StartDateTs; $i <= $EndDateTs; $i += (60 * 60 * 24 * $DayDifference)) {
                                $NextDate = date("Y-m-d", $i);
                                $AllBetweenDates[] = $NextDate;
                            }

                            //none repeat
                            if($Repeat == "none") {
                                //do nothing $AllBetweenDates are none repeat dates in this case
                            } //end of weekly if


                            //checking repeat
                            if($Repeat == "weekly") {
                                $Weeks = $RepeatAmount;
                                for($i = 1;  $i < $Weeks; $i++) {
                                    for($j = 0; $j < count($AllBetweenDates); $j++) {
                                        $AllWeeksDates[] = date("Y-m-d", strtotime("+$i weeks", strtotime($AllBetweenDates[$j]) ) );
                                    }
                                }
                            } //end of weekly if

                            if($Repeat == "biweekly") {
                                $BiWeeks = $RepeatAmount;
                                for($i = 1;  $i < $BiWeeks; $i++) {
                                    for($j = 0; $j < count($AllBetweenDates); $j++) {
                                        $bi = $i * 2;
                                        $AllBiWeeksDates[] = date("Y-m-d", strtotime("+$bi weeks", strtotime($AllBetweenDates[$j]) ) );
                                    }
                                }
                            } //end of biweekly if

                            if($Repeat == "monthly") {
                                $Months = $RepeatAmount;
                                for($i = 1;  $i < $Months; $i++) {
                                    for($j = 0; $j < count($AllBetweenDates); $j++) {
                                        $AllMonthsDates[] = date("Y-m-d", strtotime("+$i month", strtotime($AllBetweenDates[$j]) ) );
                                    }
                                }
                            } //end of monthly if

                            if($Repeat == "yearly") {
                                $Years = $RepeatAmount;
                                for($i = 1;  $i < $Years; $i++) {
                                    for($j = 0; $j < count($AllBetweenDates); $j++) {
                                        $AllYearsDates[] = date("Y-m-d", strtotime("+$i year", strtotime($AllBetweenDates[$j]) ) );
                                    }
                                }
                            } //end of yearly if

                            //calculates booked classes
                            $BookedClasses = $wpdb->get_results("SELECT * FROM `$ClassBookingsTable` WHERE `class_id` = '$ClassId' AND `status` != 'cancelled'");
                            if(count($BookedClasses)){
                                $Booked = count($BookedClasses);
                            } else {
                                $Booked = 0;
                            }
                            $Available = $Capacity - $Booked;
                            if( $Capacity != $Booked) {
                                //merge all dates
                                $AllDates = array_merge($AllBetweenDates, $AllNoRepeatDates, $AllWeeksDates, $AllBiWeeksDates, $AllMonthsDates, $AllYearsDates);
                                //rendering class event on full-calendar
                                foreach($AllDates as $Date) {
                                    // subtract 1 from month digit coz calendar work on month 0-11
                                    $Y = date ( 'Y' , strtotime( $Date ) );
                                    $M = date ( 'n' , strtotime( $Date ) ) - 1;
                                    $D = date ( 'd' , strtotime( $Date ) );
                                    $Date = "$Y-$M-$D";
                                    $Date = str_replace("-",", ", $Date); ?>
                                    {
                                        id: "<?php echo $ClassId; ?>",
                                        title: "<?php echo $Name; if($BookingStatus == "yes") { echo " ".__("A-", "appointzilla").$Available." ". __("B-", "appointzilla").$Booked;  }?>",
                                        start: new Date(<?php echo "$Date, $StartTime"; ?>),
                                        end: new Date(<?php echo "$Date, $EndTime"; ?>),
                                        allDay: false,
                                        backgroundColor : "<?php echo $Color; ?>",
                                        textColor: "white"
                                    },
                                    <?php
                                }
                            } // end of capacity occupied
                        } //end of availability if
                    } //end of foreach
                } //end of count if
                    ?>
                {
                }
            ],
            eventClick: function(event, calEvent, jsEvent, view) {
                if (event.id) {
                    var ClassId = event.id;
                    var JoiningDate = event.start;
                    //var End = event.end;
                    JoiningDate = JoiningDate.toString();
                    JoiningDate = JoiningDate.substring(0, 16);
                    StartBooking(ClassId, JoiningDate);
                }
            }
        });
    });

    //show first booking form
    function StartBooking(ClassId, JoiningDate){
        jQuery("#booking-instructions").hide();
        jQuery("#calendar").hide();
        jQuery("#loading-img").show();
        jQuery('html, body').animate({
            scrollTop: jQuery("#class-booking-instructions").offset().top
        }, 1200);
        var PostData = "Action=class-details" + "&ClassId=" + ClassId + "&JoiningDate=" + JoiningDate;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                jQuery("#loading-img").hide();
                data = jQuery(data).find("div#class-details-div-1");
                jQuery("#class-details-div-2").html(data);
            }
        });
    }

    //stop booking
    function StopBooking() {
        jQuery("#class-details-div-2").hide();
        jQuery("#join-class-div-2").hide();
        jQuery("#loading-img").show();
        location.href = location.href;
    }

    //join class
    function JoinClass(ClassId, JoiningDate) {
        jQuery("#loading-img").show();

        jQuery('html, body').animate({
            scrollTop: jQuery("#class-booking-instructions").offset().top
        }, 1500);

        jQuery("#class-details-div-2").hide();
        var PostData = "Action=join-class" + "&ClassId=" + ClassId + "&JoiningDate=" + JoiningDate;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                jQuery("#loading-img").hide();
                data = jQuery(data).find("div#join-class-div-1");
                jQuery("#join-class-div-2").html(data);
            }
        });
    }

    //book class
    function BookClass(ClassId, JoiningDate) {
        jQuery('.acberror').hide();
        var Name = jQuery("#name").val();
        var Email = jQuery("#email").val();
        var Phone = jQuery("#phone").val();
        var Sn = jQuery("#sn").val();

        //first name
        if (Name == "") {
            jQuery("#name").after("<span class='acberror'><br><strong><?php _e('Name name required.', 'appointzilla'); ?></strong></span>");
            return false;
        } else {
            var Res = isNaN(Name);
            if(Res == false) {
                jQuery("#name").after("<span class='acberror'><br><strong><?php _e('Invalid name.', 'appointzilla'); ?></strong></span>");
                return false;
            }
            var NameRegx = /^[a-zA-Z0-9- ]*$/;
            if(NameRegx.test(Name) == false) {
                jQuery("#name").after("<span class='acberror'><br><strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                return false;
            }
        }

        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (Email == "") {
            jQuery("#email").after("<span class='acberror'><br><strong><?php _e('Email required.', 'appointzilla'); ?></strong></span>");
            return false;
        } else {
            if(regex.test(Email) == false ) {
                jQuery("#email").after("<span class='acberror'><br><strong><?php _e('Invalid email.', 'appointzilla'); ?></strong></span>");
                return false;
            }
        }

        if (Phone == "") {
            jQuery("#phone").after("<span class='acberror'><br><strong><?php _e("Only Numbers: 1234567890.", "appointzilla"); ?></strong></span>");
            jQuery("#phone").after("<span class='acberror'><br><strong><?php _e("Phone required.", "appointzilla"); ?></strong></span>");

            return false;
        } /*else {
         var PhoneRes = isNaN(Phone);
         if(PhoneRes == true) {
         jQuery("#phone").after("<span class='acberror'><br><strong><?php //_e("Only Numbers: 1234567890.", "appointzilla"); ?></strong></span>");
         jQuery("#phone").after("<span class='acberror'><br><strong><?php //_e("Invalid phone.", "appointzilla"); ?></strong></span>");
         return false;
         }
         }*/

        jQuery("#join-class-div-1").hide();
        jQuery("#join-class-div-2").hide();
        jQuery("#loading-img").show();
        var PostData1 = "Action=book-class" + "&ClassId=" + ClassId + "&JoiningDate=" + JoiningDate;
        var PostData2 = "&Name=" + Name + "&Email=" + Email + "&Phone=" + Phone + "&Sn=" + Sn;
        var PostData = PostData1 + PostData2;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                jQuery("#loading-img").hide();
                data = jQuery(data).find("div#booking-result-div");
                jQuery("#show-class-booking-result").html(data);
            }
        });
    }

    //on completed anything reload page
    function Done(){
        jQuery("#show-class-booking-result").hide();
        jQuery("#loading-img").show();
        location.href = location.href;
    }

    //cancel appointment
    function CancelBooking() {
        var BookingId = jQuery('#booking-id').val();
        var DataString = "Action=CancelBooking" + "&BookingId="+ BookingId;
        jQuery("#show-class-booking-result").hide();
        jQuery("#loading-img").show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : DataString,
            complete : function() {  },
            success: function() {
                location.href = location.href;
            }
        });
    }
    </script>
    <!---display booking instruction--->
    <div id="class-booking-instructions" align="center">
        <?php echo $BookingInstructions."<br><br>"; ?>
    </div>

    <!---show appointment calendar--->
    <div id="big-calendar-div">
        <div id="calendar">
            <!---show appointment legends--->
            <div id="calendar-legends" style="margin-top: 4px; font-size: small;">
                <?php if($BookingStatus == "yes") { ?>
                    <strong><?php _e("A", "appointzilla"); ?></strong> = <?php _e("Available", "appointzilla"); ?> &nbsp; <strong><?php _e("B", "appointzilla"); ?></strong> = <?php _e("Booked", "appointzilla"); ?>
                <?php } ?>
                <div style="float: right;">Appointment Calendar Premium Powered By: <a href="http://appointzilla.com/" target="_blank">Appointzilla</a></div>
            </div>
        </div>
    </div>

    <!--loading image-->
    <div id="loading-img" style="display: none; text-align: center;">
        <i class="fa fa-spinner fa-spin fa-4x"></i>
    </div>

    <style>
        .entry-content td, .comment-content td {
            vertical-align: top;
            border-top: 0px;
            padding: 0px;
        }
    </style>

    <div class="row-fluid" id="class-details-div-2"></div>

    <div class="row-fluid" id="join-class-div-2"></div>

    <div id="show-class-booking-result"></div>
    <?php require_once("class-booking-process.php");
}