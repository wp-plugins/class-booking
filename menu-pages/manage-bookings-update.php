<div id="manage-class-booking">
    <?php
    if(isset($_POST['Action'])) {
        //print_r($_POST);
        $Action = $_POST['Action'];
        //view n update booking
        if($Action == "view-class-booking" || $Action == "update-class-booking") {
            $Id = $_POST['Id'];
            $ClassId = $_POST['ClassId'];
            $Booking = $wpdb->get_row("SELECT * FROM `$ClassBookingsTable` WHERE `id` = '$Id'");
            $Name = $Booking->name;
            $Email = $Booking->email;
            $Phone = $Booking->phone;
            $Note = $Booking->sn;
            $BookingOn = $Booking->book_date_time;
        ?>
        <div class="borBox form-horizontal">

            <?php if($Action == 'view-class-booking') { ?>
                <h3 style="margin-left: 30px;"><i class="fa fa-eye"></i> <?php _e('View Booking', 'appointzilla'); ?></h3>
            <?php } if($Action == 'update-class-booking') { ?>
                <h3 style="margin-left: 30px;"><i class="fa fa-edit"></i> <?php _e('Update Booking', 'appointzilla'); ?></h3>
            <?php } ?>
            <input type="hidden" id="client-id" value="<?php echo $Id; ?>">

            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e('Booking On', 'appointzilla'); ?></label>
                <div class="control pull-left">
                    <input type="text" readonly="" value="<?php echo ucwords($BookingOn); ?>" style="height: 32px; margin-left: 15px;">
                </div>
            </div>

            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e('Name', 'appointzilla'); ?></label>
                <div class="control pull-left">
                    <input type="text" placeholder="Type Instructor Name Here" id="name" value="<?php echo ucwords($Name); ?>" style="height: 32px; margin-left: 15px;">
                </div>
            </div>

            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e('Email', 'appointzilla'); ?></label>
                <div class="control pull-left">
                    <input type="text" placeholder="Type Instructor Email Here" id="email" value="<?php echo strtolower($Email); ?>" style="height: 32px; margin-left: 15px;">
                </div>
            </div>

            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e('Phone', 'appointzilla'); ?></label>
                <div class="control pull-left">
                    <input type="text" placeholder="Type Phone Numbers" id="phone" value="<?php echo $Phone; ?>" style="height: 32px; margin-left: 15px;">
                </div>
            </div>

            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e('Special Note', 'appointzilla'); ?></label>
                <div class="control pull-left">
                    <textarea id="sn" name="sn" style="margin-left: 15px;" rows="5"><?php echo $Note; ?></textarea>
                </div>
            </div>

            <div class="control-group">
                <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
                <div class="control pull-left">
                <span style="margin-left: 15px;">
                    <?php if($Action == 'update-class-booking') { ?>
                        <button class="btn btn-sharp btn-success" id="update-instructor-btn" onclick="return PerformClassBookingAction('<?php echo $Id; ?>', 'perform-update-class-booking');"><strong><i class="fa fa-edit"></i> <?php _e('Update', 'appointzilla'); ?></strong></button>
                    <?php } ?>
                    <button class="btn btn-sharp btn-danger" id="cancel-btn" onclick="return CancelBooking();"><strong><i class="fa fa-times"></i> <?php _e('Cancel', 'appointzilla'); ?></strong></button>
                </span>
                </div>
            </div>
        </div>
        <?php

        }// end of view / update booking action

        //delete booking
        if($Action == "delete-class-booking") {
            $Id = $_POST['Id'];
            $wpdb->query("DELETE FROM `$ClassBookingsTable` WHERE `id` = '$Id'");
        }

        //delete all bookings
        if($Action == "delete-all-class-bookings") {
            if(isset($_POST['Id'])) {
                $Ids = $_POST['Id'];
                $Ids = explode(",", $_POST['Id']);
                for($i =0; $i < count($Ids); $i++) {
                    $DelId =  $Ids[$i];
                    $wpdb->query("DELETE FROM `$ClassBookingsTable` WHERE `id` = '$DelId'");
                }
            }
        }

        //update booking
        if($Action == "perform-update-class-booking") {
            $Id = $_POST['Id'];
            $Name = $_POST['Name'];
            $Email = $_POST['Email'];
            $Phone = $_POST['Phone'];
            $Note = $_POST['Note'];
            
                $ClassBookingUpdateSQL = "UPDATE `$ClassBookingsTable` SET `name` = '$Name', `email` = '$Email', `phone` = '$Phone', `sn` = '$Note' WHERE `id` = '$Id';";
               $wpdb->query($ClientUpdateSQL);
            
        }
    }
    ?>
</div>