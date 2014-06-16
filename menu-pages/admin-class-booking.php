<?php
/**
 * Load Class Booking Settings
 */
$AllClassBookingSettings = unserialize(get_option('acb_class_booking_settings'));
if(count($AllClassBookingSettings)) {
    $ClientRegistration = $AllClassBookingSettings['acb_client_registration'];
    $BookingStatus = $AllClassBookingSettings['acb_booking_status'];
    $DateFormat = $AllClassBookingSettings['acb_date_format'];
    $TimeFormat = $AllClassBookingSettings['acb_time_format'];
} else {
    $ClientRegistration = "yes";
    $BookingStatus = "yes";
    $DateFormat = "d-m-Y";
    $TimeFormat = "h:i";
}

/**
 * Load Calendar Settings
 */
$AllCalendarSettings = unserialize(get_option('acb_calendar_settings'));
if(count($AllCalendarSettings)) {
    $StartDay = $AllCalendarSettings['acb_calendar_start_day'];
    $View = $AllCalendarSettings['acb_calendar_view'];
    $SlotTime = $AllCalendarSettings['acb_calendar_slot_time'];
    $DayStartTime = $AllCalendarSettings['acb_day_start_time'];
    $DayEndTime = $AllCalendarSettings['acb_day_end_time'];
    $BookingInstructions = $AllCalendarSettings['acb_booking_instructions'];
    $ThankYouMessage = $AllCalendarSettings['acb_thank_you_message'];
} else {
    $StartDay = 1;
    $View = "month";
    $SlotTime = 10;
    $DayStartTime = "10:00 AM";
    $DayEndTime = "5:00 PM";
    $BookingInstructions = "Put your booking instructions here.<br>Or you can save It blank in case of nothing want to display.";
    $ThankYouMessage = __("Your booking has been completed. Thanks you for booking with us.", "appointzilla");
}

global $wpdb;
$ClassesTable = $wpdb->prefix . "apcal_pre_classes";
$ClassesCategoriesTable = $wpdb->prefix . "apcal_pre_classes_categories";
$InstructorsTable = $wpdb->prefix . "apcal_pre_instructors";
$InstructorsGroupsTable = $wpdb->prefix . "apcal_pre_instructors_groups";
$ClassBookingsTable = $wpdb->prefix . "apcal_pre_class_bookings";
$AllInstructorsDetails = array();

// all classes
$AllClassesSQL = "SELECT * FROM `$ClassesTable`";
$AllClasses = $wpdb->get_results($AllClassesSQL);

// all class categories
$AllClassesCategoriesSQL = "SELECT * FROM `$ClassesCategoriesTable`";
$AllClassesCategories = $wpdb->get_results($AllClassesCategoriesSQL);
?>
    <!---render full-calendar--->
    <script type='text/javascript'>
    jQuery(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
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
            minTime: '00:00:00',
            maxTime: '24:00:00',
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
                /**
                 * Loading recurring classes
                 */
                if(count($AllClasses)) {
                    foreach($AllClasses as $Class) {

                        $ClassId = $Class->id;
                        $Name = $Class->name;
                        $StartTime = date("H, i", strtotime($Class->start_time));
                        $EndTime = date("H, i", strtotime($Class->end_time));
                        $StartDate = $Class->start_date;
                        $EndDate = $Class->end_date;
                        $StartDateTs = strtotime($StartDate);  //time stamp value
                        $EndDateTs =  strtotime($EndDate);  //time stamp value
                        $Capacity = $Class->capacity;
                        $Repeat = $Class->repeat;
                        $RepeatAmount = $Class->repeat_amount;
                        $Availability = $Class->availability;
                        $Color = $Class->color;

                        //check service available
                        if($Availability == "yes") {
                             $AllDates = array();
                             $AllBetweenDates = array();
                             $AllWeeksDates = array();
                             $AllBiWeeksDates = array();
                             $AllMonthsDates = array();
                             $AllYearsDates = array();
                             $AllNoRepeatDates = array();

                            //calculate start-end date's between dates
                            $DayDifferance = 1;
                            for($i = $StartDateTs; $i <= $EndDateTs; $i += (60 * 60 * 24 * $DayDifferance)) {
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
                            $BookedClasses = $wpdb->get_results("SELECT * FROM `wp_apcal_pre_class_bookings` WHERE `class_id` = '$ClassId'");
                            if(count($BookedClasses)){
                               $Booked = count($BookedClasses);
                            } else {
                               $Booked = 0;
                            }
                            $Available = $Capacity - $Booked;

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
                                    title: "<?php echo $Name.": ("; ?><?php _e("A", "appointzilla"); echo "-$Available / ";?> <?php _e("B", "appointzilla"); echo "-$Booked)"; ?>",
                                    start: new Date(<?php echo "$Date, $StartTime"; ?>),
                                    end: new Date(<?php echo "$Date, $EndTime"; ?>),
                                    allDay: false,
                                    backgroundColor : "<?php echo $Color; ?>",
                                    textColor: "white",
                                },
                                <?php
                            }
                        } //end of availability if

                    } //end of foreach

                }//end of count if
                    ?>
                {
                }
            ],
            eventClick: function(event) {
                if (event.id) {
                    //do any thing
                }
            }
        }); // end of full-calendar js code
    });
    </script>

    <style type='text/css'>
        .error{
            color:#FF0000;
        }

        #calendar {
            width: auto;
            margin: 4px 4px;;
        }
        #bkbtndiv{
            margin: 5px;
        }
        tr th
        {
            text-align:left;
        }
        .inputwidth
        {
            width:300px;
        }
    </style>

    <!---show full-calendar--->
    <div id='calendar' style="margin:10px;"></div>
