<?php
    //Class Booking Details
    if(isset($_POST['Action'])) {
        $Action = $_POST['Action'];
        if($Action  == "class-details") {
            $ClassId = $_POST['ClassId'];
            $JoiningDate = date("Y-m-d", strtotime($_POST['JoiningDate']));
            $Class = $wpdb->get_row("SELECT * FROM `$ClassesTable` WHERE `id` = '$ClassId'");
            $ClassName = $Class->name;
            $Desc = $Class->desc;
            $TimeFormat = "h:i";
            if($TimeFormat == "h:i") { $BTimeFormat = "h:ia"; } {
                $Time = date($BTimeFormat, strtotime($Class->start_time))." <strong>-</strong> ".date($BTimeFormat, strtotime($Class->end_time));
            }
            $DateFormat = "d-m-Y";
            $Date = date($DateFormat, strtotime($Class->start_date))." <strong>-</strong> ".date($DateFormat, strtotime($Class->end_date));
            $PaddingTime = $Class->padding_time;
            $Repeat = $Class->repeat;
            $RepeatAmount = $Class->repeat_amount;
            $Capacity = $Class->capacity;
            $Cost = $Class->cost;
            $Availability = $Class->availability;
            $Color = $Class->color;

            //calculates booked/available classes
            $BookedClasses_sql = $wpdb->prepare("SELECT * FROM `$ClassBookingsTable` WHERE `class_id` = %d", $ClassId);
            $BookedClasses = $wpdb->get_results($BookedClasses_sql);
            if(count($BookedClasses)) {
                $Booked = count($BookedClasses);
            } else {
                $Booked = 0;
            }
            $Available = $Capacity - $Booked;
            ?>
            <!--booking process-->
            <div class="row-fluid" id="class-details-div-1">
                <div class="span10">
                    <div class="apcal_alert apcal_alert-info">
                        <p style="font-size: large;"><i class="fa fa-book"></i> Class Details</p>
                        <table>
                            <tbody>
                                <tr>
                                    <td><?php _e("Name", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td><?php echo ucwords($ClassName); ?></td>
                                </tr>
                                <tr>
                                    <td><?php _e("Description", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td><?php echo ucfirst($Desc); ?></td>
                                </tr>
                                <tr>
                                    <td><?php _e("Timing", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td><?php echo $Time; ?></td>
                                </tr>
                                <tr>
                                    <td><?php _e("Date", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td>
                                        <?php
                                        if($Repeat == "none") {
                                            echo date($DateFormat, strtotime($Class->start_date));
                                        } else {
                                            echo date($DateFormat, strtotime($Class->start_date))." <strong>-</strong> ".date($DateFormat, strtotime($Class->end_date));
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php if($Repeat != "none") { ?>
                                <tr>
                                    <td><?php _e("Repeat", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td>
                                        <?php
                                            if($Repeat == "weekly") {
                                                echo ucwords($Repeat);
                                            }
                                            if($Repeat == "biweekly") {
                                                echo ucwords($Repeat);
                                            }
                                            if($Repeat == "monthly") {
                                                echo ucwords($Repeat);
                                            }
                                            if($Repeat == "yearly") {
                                                echo ucwords($Repeat);
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php } if(0) { ?>
                                <tr>
                                    <td><?php _e("Capacity", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td><?php echo $Capacity; ?></td>
                                </tr>
                                <tr>
                                    <td><?php _e("Booked", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td><?php echo $Booked; ?></td>
                                </tr>
                                <tr>
                                    <td><?php _e("Available", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td><?php echo $Available; ?></td>
                                </tr>
                                <?php }  ?>
                                <tr>
                                    <td><?php _e("Cost", "appointzilla"); ?></td>
                                    <td style="width:auto;"></td>
                                    <td>
                                        <?php
                                            if($Cost > 0) {
                                                echo $CurrencySymbol.$Cost;
                                            } else {
                                                echo _e("Not Available", "appointzilla");
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <button type="button" onclick="return JoinClass(<?php echo $ClassId; ?>, '<?php echo $JoiningDate; ?>');"class="apcal_btn apcal_btn-success"><i class="icon-ok icon-plus icon-white"></i> Join Class</button>
                                        <button type="button" onclick="return StopBooking();" class="apcal_btn apcal_btn-danger"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        } //end of action check if
    } //end of 'class-details' action isset if


     //Join Class Booking Process
    if(isset($_POST['Action'])) {
        $Action = $_POST['Action'];
        if($Action  == "join-class") {
            $ClassId = $_POST['ClassId'];
            $JoiningDate = $_POST['JoiningDate'];
            ?>
            <!--show booking form-->
            <div class="row-fluid" id="join-class-div-1">
                <div class="span10">
                    <div class="apcal_alert apcal_alert-info">
                        <p style="font-size: large;"><i class="fa fa-book"></i> <?php _e("Join Class", "appointzilla"); ?></p>
                        <table id="new-user-table">
                            <tbody>
                                <tr>
                                    <td><?php _e("Name", "appointzilla"); ?></td>
                                    <td>
                                        <input type="text" id="name" name="name" placeholder="Type Name Here" style="height: 30px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e("Email", "appointzilla"); ?></td>
                                    <td><input type="text" id="email" name="email" placeholder="Type Email Here" style="height: 30px;"></td>
                                </tr>
                                <tr>
                                    <td><?php _e("Phone", "appointzilla"); ?></td>
                                    <td><input type="text" id="phone" name="phone" placeholder="Type Phone Number Here" style="height: 30px;"></td>
                                </tr>
                                <tr>
                                    <td><?php _e("Booking Note", "appointzilla"); ?></td>
                                    <td><textarea id="sn" name="sn" placeholder="Type Booking Note Here"></textarea></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        <button type="button" onclick="return BookClass(<?php echo $ClassId; ?>, '<?php echo $JoiningDate; ?>');"class="apcal_btn apcal_btn-success"><i class="fa fa-check-square icon-white"></i> Book Class</button>
                                        <button type="button" onclick="return StopBooking();" class="apcal_btn apcal_btn-danger"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php } //end of not logged in else ?>
                    </div>
                </div>
            </div>
            <?php
    }//end of join class booking if

    //Book Class & Save Booking Details
    if(isset($_POST['Action'])) {
        $Action = $_POST['Action'];
        if($Action == "book-class") {
            $ManageClassBookingsTable = $wpdb->prefix . "apcal_pre_class_bookings";
            $ClassId = $_POST['ClassId'];
            $Name = $_POST['Name'];
            $Email = $_POST['Email'];
            $Phone = $_POST['Phone'];
            $Sn = $_POST['Sn'];
            $JoiningDate = $_POST['JoiningDate'];
            $Key = md5(date("F j, Y, g:i a"));
            $Status = "pending";
            $BookedBy = "user";

            $NewBooking_sql = $wpdb->prepare("INSERT INTO `$ManageClassBookingsTable` (`id`, `class_id`, `name`, `email`, `phone`, `sn`, `joining_date`, `key`, `status`, `booked_by`, `book_date_time`) VALUES (NULL, %d, %s, %s, %s, %s, %s, %s, %s, %s, CURRENT_TIMESTAMP)", $ClassId, $Name, $Email, $Phone, $Sn, $JoiningDate, $Key, $Status, $BookedBy);
            //$NewBookingSQL = "INSERT INTO `$ManageClassBookingsTable` (`id`, `class_id`, `name`, `email`, `phone`, `sn`, `joining_date`, `key`, `status`, `booked_by`, `book_date_time`) VALUES (NULL, '$ClassId', '$Name', '$Email', '$Phone', '$Sn', '$JoiningDate', '$Key', '$Status', '$BookedBy', CURRENT_TIMESTAMP);";
            ?><div id="booking-result-div" class="apcal_alert apcal_alert-info"><?php
            if($wpdb->query($NewBooking_sql)) {
                $BookingId = mysql_insert_id();
                //add class client - first check already exist
                $ClassClientsTable = $wpdb->prefix . "apcal_pre_class_clients";
                $SearchClient = $wpdb->get_row("SELECT `id` FROM `$ClassClientsTable` WHERE `email` = '$Email'");
                if(!count($SearchClient)) {
                    //insert new client
                    $client_insert_sql = $wpdb->prepare("INSERT INTO `$ClassClientsTable` (`id`, `name`, `email`, `phone`, `sn`) VALUES (NULL, %s, %s, %s, %s)", $Name, $Email, $Phone, $Sn);
                    $wpdb->query($client_insert_sql);
                    //$wpdb->query("INSERT INTO `$ClassClientsTable` (`id`, `name`, `email`, `phone`, `sn`) VALUES (NULL, '$Name', '$Email', '$Phone', '$Sn')");
                    $ClientId = mysql_insert_id();
                } else {
                    $ClientId = $SearchClient->id;
                    //update existing client details
                    $client_update_sql = $wpdb->prepare("UPDATE `$ClassClientsTable` SET `name` = %s, `phone` = %s, `sn` = %s WHERE `id` = %d", $Name, $Phone, $Sn, $ClientId);
                    $wpdb->query($client_update_sql);
                    //$wpdb->query("UPDATE `$ClassClientsTable` SET `name` = '$Name', `phone` = '$Phone', `sn` = '$Sn' WHERE `id` = '$ClientId';");
                }

                // Booking Details & Thank you Message
                //Get Class Details
                $ClassData = $wpdb->get_row("SELECT * FROM `$ClassesTable` WHERE `id` = '$ClassId'");
                if(count($ClassData)) {
                    $ClassName = ucwords($ClassData->name);
                    if($TimeFormat == "h:i") { $BTimeFormat = "h:ia"; }
                    $Date = date($DateFormat, strtotime($Class->start_date))." <strong>-</strong> ".date($DateFormat, strtotime($Class->end_date));
                    $Time = date($BTimeFormat, strtotime($Class->start_time))." <strong>-</strong> ".date($BTimeFormat, strtotime($Class->end_time));
                }

                /**
                 * Send Notification
                 * --Check notification enable ON/OFF
                 * --Check admin/client/notification is ON/OFF
                 */
                $AllNotificationSettings = unserialize(get_option('acb_notification_settings'));
                if(count($AllNotificationSettings)) {
                    $EnableNotification = $AllNotificationSettings['acb_enable_notification'];
                    $NotifyAdmin = $AllNotificationSettings['acb_notify_admin'];
                    $NotifyClient = $AllNotificationSettings['acb_notify_client'];
                    $NotificationType = $AllNotificationSettings['acb_notification_type'];

                    if($EnableNotification == "yes") {
                        //include notification class
                        require_once('menu-pages/notification-class.php');
                        $Notification = new Notification();
                        $On = "pending";
                        $BlogName =  get_bloginfo('name');

                        //notify client
                        if($NotifyClient == "yes") {
                            $Notification->NotifyClient($On, $BookingId, $ClassId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                        }

                        //notify admin
                        if($NotifyAdmin == "yes") {
                            $Notification->NotifyAdmin($BookingId, $ClassId, $ClientId, $BlogName, $DateFormat, $TimeFormat);
                        }
                    }
                } //edn of notification settings
                ?>
                <!--display class booking details-->
                <span style="text-align: center'"><?php echo $ThankYouMessage ?></span>
                <hr><br>
                <strong><?php _e("Booking Details are", "appointzilla"); ?>:</strong>
                <input type="hidden" id="booking-id" name="booking-id" value="<?php echo $BookingId; ?>" />
                <table>
                    <tr>
                        <td><?php _e("Name", "appointzilla"); ?></td>
                        <td>:</td>
                        <td><?php echo ucwords($Name); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e("Email", "appointzilla"); ?></td>
                        <td>:</td>
                        <td><?php echo $Email; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e("Phone", "appointzilla"); ?></td>
                        <td>:</td>
                        <td><?php echo $Phone; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e("Booked Class", "appointzilla"); ?></td>
                        <td>:</td>
                        <td><?php echo ucwords($ClassName); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e("Joining Date", "appointzilla"); ?></td>
                        <td>:</td>
                        <td><?php echo date("d-m-Y", strtotime($_POST['JoiningDate'])); ?></td>
                    </tr>

                    <tr>
                        <td><?php _e("Class Time", "appointzilla"); ?></td>
                        <td>:</td>
                        <td><?php echo $Time; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e("Booking Status", "appointzilla"); ?></td>
                        <td>:</td>
                        <td><?php echo ucfirst($Status); ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <button id="done" class="apcal_btn" onclick="return Done();"><i class="icon-ok"></i> <?php _e("Done", "appointzilla"); ?></button>
                        </td>
                    </tr>
                </table>
                <?php
            } else {
                echo _e("Sorry! your booking request not completed.", "appointzilla");
            }
            ?></div><?php
        }
    }
?>