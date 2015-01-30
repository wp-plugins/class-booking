<div id="manage-class-clients">
    <?php
    if(isset($_POST['Action'])) {
        //print_r($_POST);
        $Action = $_POST['Action'];
        //view n update client
        if($Action == "view-client" || $Action == "update-client") {
            $Id = $_POST['Id'];
            $Client = $wpdb->get_row("SELECT * FROM `$ClassClientsTable` WHERE `id` = '$Id'");
            $Name = $Client->name;
            $Email = $Client->email;
            $Phone = $Client->phone;
            $Note = $Client->sn;
        }

        //add client
        if($Action == 'add-client') {
            $Id = NULL;
            $Name = NULL;
            $Email = NULL;
            $Phone = NULL;
            $Note = NULL;
        }
    ?>
    <div class="borBox form-horizontal">
        <?php   if($Action == 'add-client') { ?>
            <h3 style="margin-left: 30px;"><i class="fa fa-plus"></i> Add New Client</h3>
        <?php } if($Action == 'view-instructor') { ?>
            <h3 style="margin-left: 30px;"><i class="fa fa-eye"></i> View Client</h3>
        <?php } if($Action == 'update-client') { ?>
            <h3 style="margin-left: 30px;"><i class="fa fa-edit"></i> Update Client</h3>
        <?php } ?>
        <input type="hidden" id="client-id" value="<?php echo $Id; ?>">

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;">Name</label>
            <div class="control pull-left">
                <input type="text" placeholder="Type Instructor Name Here" id="name" value="<?php echo ucwords($Name); ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;">Email</label>
            <div class="control pull-left">
                <input type="text" placeholder="Type Instructor Email Here" id="email" value="<?php echo strtolower($Email); ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;">Phone</label>
            <div class="control pull-left">
                <input type="text" placeholder="Type Phone Numbers" id="phone" value="<?php echo $Phone; ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;">Special Note</label>
            <div class="control pull-left">
                <textarea id="sn" name="sn" style="margin-left: 15px;" rows="5"><?php echo $Note; ?></textarea>
            </div>
        </div>

        <div class="control-group">
            <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
            <div class="control pull-left">
                <span style="margin-left: 15px;">
                    <?php if($Action == 'add-client') { ?>
                        <button class="btn btn-sharp btn-success" id="create-instructor-btn" onclick="return PerformClientAction('<?php echo $Id; ?>', 'save-client');"><strong><i class="fa fa-save"></i> Save Client</strong></button>
                    <?php }
                    if($Action == 'update-client') { ?>
                        <button class="btn btn-sharp btn-success" id="update-instructor-btn" onclick="return PerformClientAction('<?php echo $Id; ?>', 'update-client');"><strong><i class="fa fa-edit"></i> Update Client</strong></button>
                    <?php } ?>
                    <button class="btn btn-sharp btn-danger" id="cancel-btn" onclick="return CancelClient();"><strong><i class="fa fa-times"></i> Cancel</strong></button>
                </span>
            </div>
        </div>
    </div>
    <?php
        //delete client
        if($Action == "delete-client") {
            $Id = $_POST['Id'];
            $wpdb->query("DELETE FROM `$ClassClientsTable` WHERE `id` = '$Id'");
        }

        //delete all clients
        if($Action == "delete-all-clients") {
            if(isset($_POST['Id'])) {
                $Ids = $_POST['Id'];
                $Ids = explode(",", $_POST['Id']);
                for($i =0; $i < count($Ids); $i++) {
                    $DelId =  $Ids[$i];
                    $wpdb->query("DELETE FROM `$ClassClientsTable` WHERE `id` = '$DelId'");
                }
            }
        }

        //save or update client
        if($Action == "perform-save-client" || $Action == "perform-update-client") {
            print_r($_POST);
            $Id = $_POST['Id'];
            $Name = $_POST['Name'];
            $Email = $_POST['Email'];
            $Phone = $_POST['Phone'];
            $Note = $_POST['Note'];
            if($Action == "perform-save-client") {
                $ClientSaveSQL = "INSERT INTO `$ClassClientsTable` (`id`, `name`, `email`, `phone`, `sn` ) VALUES (NULL, '$Name', '$Email', '$Phone', '$Note')";
                $wpdb->query($ClientSaveSQL);
            }

            if($Action == "perform-update-client") {
                $ClientUpdateSQL = "UPDATE `$ClassClientsTable` SET `name` = '$Name', `email` = '$Email', `phone` = '$Phone', `sn` = '$Note' WHERE `id` = '$Id';";
                $wpdb->query($ClientUpdateSQL);
            }
        }
    }
    ?>
</div>