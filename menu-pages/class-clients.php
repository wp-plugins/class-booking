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
</style>
<div class="tooltip-demo">
    <div class="page-header" style="margin: 20px 0px 0px;">
        <h3><?php _e("Class Clients", "appointzilla"); ?> <small><?php _e("List of clients", "appointzilla"); ?></small></h3>
    </div>

    <div id="add-new-client-btn-div">
        <button id="add-new-class-btn" onclick="return PostAction(-1, 'add-class-client');" class="btn btn-info btn-sharp"><i class="icon-white icon-plus"></i> <?php _e("Add New Client", "appointzilla"); ?></button>
        <br><br>
    </div>

    <div id="loading-img" style="display: none;"><i class="fa fa-spinner fa-spin fa-4x"></i></div>

    <?php
    /**
     * Loading Clients from DB
     */
    global $wpdb;
    $ClassClientsTable = $wpdb->prefix . "apcal_pre_class_clients";
    $AllClients = $wpdb->get_results("SELECT * FROM `$ClassClientsTable`");
    ?>
    <div id="class-clients-list" class="row-fluid" style="margin-right: 10px;">
        <div class="span12">
            <table class="table table-bordered table-projects table-hover">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th><i class="fa fa-male icon-white"></i><?php _e("Name", "appointzilla"); ?></th>
                        <th><i class="fa fa-envelope-o icon-white"></i> <?php _e("Email", "appointzilla"); ?></th>
                        <th><i class="fa fa-phone icon-white"></i><?php _e("Phone", "appointzilla"); ?></th>
                        <th><i class="fa fa-file-o icon-white"></i><?php _e("Special Note", "appointzilla"); ?></th>
                        <th style="text-align: center;"><?php _e("Action", "appointzilla"); ?></th>
                        <th style="text-align: center;"><a href="#" rel="tooltip" data-placement="left" title="<?php _e('Select All','appointzilla'); ?>"><input type="checkbox" id="checkbox" name="checkbox[]" value="0" /></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($AllClients)) {
                        $Sn = 1;
                        foreach($AllClients as $Client) {
                            $Id = $Client->id;
                            $Name = $Client->name;
                            $Email = $Client->email;
                            $Phone = $Client->phone;
                            $Note = $Client->sn;
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $Sn; ?></td>
                        <td><?php echo ucwords($Name); ?></td>
                        <td><?php echo strtolower($Email); ?></td>
                        <td><?php echo $Phone; ?></td>
                        <td><?php echo ucfirst($Note); ?></td>
                        <td style="text-align: center;">
                            <div class="btn-group">
                                <button title="<?php _e("View","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'view-class-client');"><i class="icon-eye-open fa-lg icon-white"></i></button>
                                <button title="<?php _e("Update","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'update-class-client');"><i class="icon-edit fa-lg icon-white"></i></button>
                                <button title="<?php _e("Delete","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'delete-class-client');"><i class="icon-remove fa-lg icon-white"></i></button>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center;"><a href="#" rel="tooltip" data-placement="left" title="<?php _e('Delete All','appointzilla'); ?>"><button class="btn btn-mini btn-danger" onclick="return PostAction(-1, 'delete-all-class-clients');"><i class="icon-white icon-trash"></i></button></a></td>
                    </tr>
                    <?php
                    } else {
                    ?>
                    <tr class="alert alert-block">
                        <td style="text-align: center;">&nbsp;</td>
                        <td colspan="6"><?php echo _e("No client record found.", "appointzilla"); ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <!--<thead>
                    <tr>
                        <th colspan="7">
                            <div class="pagination" style="text-align: center">
                                <ul>
                                    <li><a href="#"><i class="fa fa-backward"></i> Prev</a></li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li><a href="#">Next <i class="fa fa-forward"></i></a></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                </thead>-->
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
        function CancelClient(){
            jQuery('#manage-class-clients-div').hide();
            jQuery('#add-new-client-btn-div').show();
            jQuery('#class-clients-list').show();
        }

        //post action
        function PostAction(Id, Action) {
            //add client
            if(Action == "add-class-client") {
                //alert(Action);
                var PostData = "Action=add-client" + "&Id=" + Id;
            }

            //view client
            if(Action == "view-class-client") {
                //alert(Action);
                var PostData = "Action=view-client" + "&Id=" + Id;
            }

            //update client
            if(Action == "update-class-client") {
                //alert(Action);
                var PostData = "Action=update-client" + "&Id=" + Id;
            }

            //delete client
            if(Action == "delete-class-client") {
                //alert(Action);
                if(confirm( "<?php _e('Are you sure to delete this client?','appointzilla'); ?>")) {
                    var PostData = "Action=delete-client" + "&Id=" + Id;
                } else {
                    return false;
                }
            }

            //delete all
            if(Action == "delete-all-class-clients") {
                //alert(Action);
                if(jQuery('input[name="checkbox[]"]:checked').length <= 0 ) {
                    alert("<?php _e('Please select any client.','appointzilla'); ?>");
                    return false;
                }

                var Ids = jQuery('input:checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if(confirm( "<?php _e('Are you sure to delete selected client?','appointzilla'); ?>")) {
                    var PostData = "Action=delete-all-clients" + "&Id=" + Ids;
                } else {
                    return false;
                }
            }

            jQuery('#add-new-client-btn-div').hide();
            jQuery('#class-clients-list').hide();
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
                    //add - view - update
                    if(Action == "add-class-client" || Action == "view-class-client" || Action == "update-class-client") {
                        data = jQuery(data).find('div#manage-class-clients');
                        jQuery('#manage-class-clients-div').show();
                        jQuery('#manage-class-clients-div').html(data);
                    }

                    //delete - delete-all
                    if(Action == "delete-class-client" || Action == "delete-all-class-clients") {
                        if(Action == "delete-class-client") {
                            alert("<?php _e('Client deleted successfully.','appointzilla'); ?>");
                        }
                        if(Action == "delete-all-class-clients") {
                            alert("<?php _e('Selected clients deleted successfully.','appointzilla'); ?>");
                        }
                        location.reload();
                    }
                }
            });
        }

        function PerformClientAction(Id, Action) {
            jQuery('.acberror').hide();
            var Name = jQuery('#name').val();
            var Email = jQuery('#email').val();
            var Phone = jQuery('#phone').val();
            var Note = jQuery('#sn').val();
            //save client
            if(Action == "save-client" || Action == "update-client") {

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

                Action = "perform-" + Action;
                var PostData =  "Action=" + Action  + "&Id=" + Id + "&Name=" + Name + "&Email=" + Email + "&Phone=" + Phone + "&Note=" + Note;
                jQuery('#manage-class-clients-div').hide();
                jQuery('#loading-img').show();
                jQuery.ajax({
                    dataType : 'html',
                    type: 'POST',
                    url : location.href,
                    cache: false,
                    data : PostData,
                    complete : function() {  },
                    success: function(data) {
                        if(Action == "perform-save-client") {
                            alert("<?php _e('Client added successfully.','appointzilla'); ?>");
                            jQuery('#loading-img').hide();
                            location.reload();
                        }

                        if(Action == "perform-update-client") {
                            alert("<?php _e('Client updated successfully.','appointzilla'); ?>");
                            jQuery('#loading-img').hide();
                            location.reload();
                        }
                    }
                });
            }
        }
    </script>

    <?php require_once("class-clients-manage.php"); ?>
    <div id="manage-class-clients-div" style="display: none;"></div>
</div>