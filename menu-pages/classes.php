<?php
$DateFormat = get_option('apcal_date_format');
if($DateFormat == '') $DateFormat = "d-m-Y";
$TimeFormat = get_option('apcal_time_format');
if($TimeFormat == '') $TimeFormat = "h:i";
?>
<style>
    .table td {
        padding: 5px;
        vertical-align: middle;
    }
    .acberror {
        color: red;
    }
</style>

<div class="page-header" style="margin: 20px 0px 0px;">
    <h3><?php _e("Classes", "appointzilla"); ?> <small><?php _e("List of available classes", "appointzilla"); ?></small></h3>
</div>

<div id="add-new-class-btn-div">
    <button id="add-new-class-btn" onclick="return PostAction(-1, 'add-class');" class="btn btn-info btn-sharp"><i class="icon-white icon-plus"></i> <?php _e("Add New Class", "appointzilla"); ?></button>
    <!--<button id="add-new-category-btn" onclick="return AddNewCategoryBtnClick();" class="btn btn-info btn-sharp"><i class="icon-white icon-plus"></i> <?php /*_e("Add New Category", "appointzilla"); */?></button>-->
    <br><br>
</div>

<div id="current-status" style="display: none;"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
<?php
/**
 * Loading class from DB
 */
global $wpdb;
$ClassesTable = $wpdb->prefix . "apcal_pre_classes";
$ClassesCategorysTable = $wpdb->prefix . "apcal_pre_classes_categories";
$ClassesCategories = $wpdb->get_results("SELECT * FROM `$ClassesCategorysTable` ORDER BY `id` ASC");
?>

<div id="classs-list" class="row-fluid" style="margin-right: 10px;">
    <div class="span12">
        <table class="table table-bordered table-projects table-hover">
            <?php
            foreach($ClassesCategories as $Category) {
                $CategoryId = $Category->id;
                $CategoryName = $Category->name;
                ?>
                <thead>
                <tr>
                    <th colspan="12" class="alert alert-info">
                        <div id="category-<?php echo $CategoryId; ?>">
                            <strong><?php _e("Classes", "appointzilla"); //echo ucwords($CategoryName); ?></strong>
                            <div style="float: right; margin-right: 10px;">
                                <span onclick="return RenameCategory(<?php echo $CategoryId; ?>);" class="label label-warning"><?php _e("Rename", "appointzilla"); ?></span>
                                <?php if($CategoryId != 1) { ?>| <span onclick="return PerformCategoryAction(<?php echo $CategoryId; ?>, 'delete-category');" class="label label-important"><?php _e("Delete", "appointzilla"); ?></span><?php }?>
                            </div>
                        </div>
                        <!--rename category fields-->
                        <div id="rename-category-div-<?php echo $CategoryId; ?>" style="display: none;">
                            <input type="text" id="rename-category-name-<?php echo $CategoryId; ?>" value="<?php echo ucwords($CategoryName); ?>"><br>
                            <button id="rename-category" class="btn btn-small btn-success" onclick="return PerformCategoryAction(<?php echo $CategoryId; ?>, 'rename-category');"><?php _e("Rename", "appointzilla"); ?></button>
                            <button id="cancel-category" class="btn btn-small btn-danger" onclick="return CancelRenameCategory(<?php echo $CategoryId; ?>);"><?php _e("Cancel", "appointzilla"); ?></button>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center;">#</th>
                    <th><?php _e("Name", "appointzilla"); ?></th>
                    <th><?php _e("Description", "appointzilla"); ?></th>
                    <th><?php _e("Time", "appointzilla"); ?></th>
                    <th><?php _e("Start Date", "appointzilla"); ?></th>
                    <th><?php _e("Capacity", "appointzilla"); ?></th>
                    <th><?php _e("Cost", "appointzilla"); ?></th>
                    <th><?php _e("Repeat", "appointzilla"); ?></th>
                    <th><?php _e("Available", "appointzilla"); ?></th>
                    <th style="text-align: center;"><?php _e("Action", "appointzilla"); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $Classes = $wpdb->get_results("SELECT * FROM `$ClassesTable` WHERE `category_id` = '$CategoryId'");
                $SNo = 1;
                if(count($Classes)) {
                    foreach($Classes as $Class) {
                        $Id = $Class->id;
                        $Name = ucwords($Class->name);
                        $Desc = $Class->desc;
                        $Time = date($TimeFormat, strtotime($Class->start_time))."-".date($TimeFormat."a", strtotime($Class->end_time));
                        $PaddingTime = $Class->padding_time;
                        $StartDate = $Class->start_date;
                        $EndDate = $Class->end_date;
                        /*if(strtotime($StartDate) == strtotime($EndDate)) {
                            $Date = $StartDate;
                        } else {
                            $Date = date("d", strtotime($StartDate))."-".date("d-m-Y", strtotime($EndDate));
                        }*/
                        $Date = date("d-m-Y", strtotime($StartDate));
                        $Capacity = $Class->capacity;
                        $Cost = $Class->cost;
                        $Repeat = $Class->repeat;
                        $RepeatAmount = $Class->repeat_amount;
                        $Availability = $Class->availability;
                        $AcceptPayment = $Class->accept_payment;
                        $PaymentType = $Class->payment_type;
                        $PercentageAmount = $Class->percentage_amount;
                        $Color = $Class->color;
                        ?>
                        <tr>
                            <td style="text-align: center; background-color: <?php echo $Color; ?>;"><?php echo $SNo; ?></td>
                            <td><?php echo ucwords($Name); ?></td>
                            <td><?php echo ucfirst($Desc); ?></td>
                            <td><?php echo $Time; ?></td>
                            <td><?php echo ucwords($Date); ?></td>
                            <td><?php echo ucwords($Capacity); ?></td>
                            <td>
                                <?php $cal_admin_currency_id = get_option('cal_admin_currency');
                                if($cal_admin_currency_id) {
                                    $CurrencyTableName = $wpdb->prefix . "ap_currency";
                                    $cal_admin_currency = $wpdb->get_row("SELECT `symbol` FROM `$CurrencyTableName` WHERE `id` = '$cal_admin_currency_id'");
                                    $cal_admin_currency = $cal_admin_currency->symbol;
                                } else {
                                    $cal_admin_currency = "&#36;";
                                }
                                echo $cal_admin_currency.$Cost; ?>
                            </td>
                            <td><?php
                                if($Repeat != 'none') {
                                    echo ucfirst($Repeat)."(".$RepeatAmount.")";

                                } else {
                                    echo ucfirst($Repeat);
                                }
                                ?>
                            </td>
                            <td><?php echo ucwords($Availability); ?></td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <button rel="tooltip" title="<?php _e("Clone","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'clone-class');"><i class="fa fa-copy fa-lg icon-white"></i></button>
                                    <button title="<?php _e("View","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'view-class');"><i class="fa fa-eye fa-lg icon-white"></i></button>
                                    <button title="<?php _e("Update","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'update-class');"><i class="fa fa-edit fa-lg icon-white"></i></button>
                                    <?php if($Id != 1) { ?><button title="<?php _e("Delete","appointzilla"); ?>" class="btn btn-mini btn-success" onclick="return PostAction(<?php echo $Id; ?>, 'delete-class');"><i class="fa fa-times fa-lg icon-white"></i></button><?php } ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                        $SNo++;
                    } //end of class foreach
                } else { //end of if count ?>
                    <tr class="alert alert-block">
                        <td style="text-align: center;">&nbsp;</td>
                        <td colspan="11"><?php echo _e("No class in this category.", "appointzilla"); ?></td>
                    </tr>
                <?php } //end of else if count ?>
                <tr>
                    <td colspan="12">&nbsp;</td>
                </tr>
                </tbody>
            <?php } //end category foreach ?>
        </table>
    </div>
</div>

<!-- Manage classs Part Start -->
<?php include_once(sprintf("%s/classes-manage.php", dirname(__FILE__))); ?>
<!-- Manage  classs Part End  -->

<div id="manage-class-div" style="display: none;"></div>

<!-- JS Works Start -->
<script type="text/javascript">
//on page load
jQuery(document).ready(function() {
    jQuery('#add-new-class-btn-div').show();
    jQuery('#classs-list').show();
    jQuery('#manage-class').show();
    jQuery("#add-manage-category-div").hide();
});

/**
 * category action functions ---------------------------------
 */

//add new category
function AddNewCategoryBtnClick() {
    jQuery("#add-new-class-btn-div").hide();
    jQuery("#classs-list").hide();
    jQuery("#add-manage-category-div").show();
}

//cancel add new category
function CancelCategoryAddBtnClick() {
    jQuery("#add-manage-category-div").hide();
    jQuery('#add-new-class-btn-div').show();
    jQuery('#classs-list').show();
}

//rename category function
function RenameCategory(Id) {
    jQuery('#category-' + Id ).hide();
    jQuery('#rename-category-div-' + Id ).show();
}

//cancel rename category
function CancelRenameCategory(Id) {
    jQuery('#rename-category-div-' + Id ).hide();
    jQuery('#category-' + Id ).show();
}

//perform all category actions
function PerformCategoryAction(Id, Action) {

    //add new category
    if(Action == "add-category") {
        jQuery('.acberror').hide();
        var NewName = jQuery("#new-category-name").val();
        //validate
        if (NewName == "") {
            jQuery("#new-category-name").after("<span class='acberror'>&nbsp;<strong><?php _e('Category name required.', 'appointzilla'); ?></strong></span>");
            return false;
        } else {
            var NewNameRegx = /^[a-zA-Z0-9- ]*$/;
            if(NewNameRegx.test(NewName) == false) {
                jQuery("#new-category-name").after("<span class='acberror'>&nbsp;<strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                return false;
            }
        }
        var PostData = "PerformCategoryAction=add-category" + "&NewName=" + NewName;
        jQuery("#add-manage-category-div").hide();
        jQuery('#current-status').show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                jQuery('#current-status').hide();
                alert("<?php _e('New category added successfully.','appointzilla'); ?>");
                location.reload();
            }
        });
    }

    //rename category
    if(Action == "rename-category") {
        var NewName = jQuery("#rename-category-name-" + Id).val();
        var PostData = "PerformCategoryAction=rename-category" + "&Id=" + Id + "&NewName=" + NewName;
        jQuery("#add-new-class-btn-div").hide();
        jQuery("#classs-list").hide();
        jQuery('#current-status').show();
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                alert("<?php _e('Category renamed successfully.','appointzilla'); ?>");
                jQuery('#current-status').hide();
                location.reload();
            }
        });
    }

    //delete category
    if(Action == "delete-category") {
        if(confirm( "<?php _e('Are you sure to delete this category?','appointzilla'); ?>")) {
            var NewName = jQuery("#new-category-name-" + Id).val();
            var PostData = "PerformCategoryAction=delete-category" + "&Id=" + Id;
            jQuery('#classs-list').hide();
            jQuery('#add-new-class-btn-div').hide();
            jQuery('#current-status').show();
            jQuery.ajax({
                dataType : 'html',
                type: 'POST',
                url : location.href,
                cache: false,
                data : PostData,
                complete : function() {  },
                success: function(data) {
                    alert("<?php _e('Category deleted successfully.','appointzilla'); ?>");
                    jQuery('#current-status').hide();
                    location.reload();
                }
            });
        }
    }
}


/**
 * classs action function -------------------------------
 */
    //check posted action
function PostAction(Id, Action) {
    //alert(Action);
    jQuery('#classs-list').hide();
    jQuery('#add-new-class-btn-div').hide();
    jQuery('#current-status').show();

    if(Action == "clone-class" || Action == "view-class" || Action == "add-class" || Action == "update-class") {
        var PostData = "action=" + Action + "&Id=" + Id;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() { jQuery('#current-status').hide(); },
            success: function(data) {
                data = jQuery(data).find('div#manage-class');
                jQuery('#manage-class-div').show();
                jQuery('#manage-class-div').html(data);
            }
        });
    }

    if(Action == "delete-class") {
        if (confirm("<?php _e("Do you want to delete this class?","appointzilla"); ?>")) {
            var PostData =  "PerformClassAction=" + Action  + "&Id=" + Id ;
            jQuery.ajax({
                dataType : 'html',
                type: 'POST',
                url : location.href,
                cache: false,
                data : PostData,
                complete : function() {  },
                success: function(data) {
                    alert("<?php _e('Class deleted successfully.','appointzilla'); ?>");
                    jQuery('#current-status').hide();
                    location.reload();
                }
            });
        } else {
            jQuery('#classs-list').show();
            jQuery('#add-new-class-btn-div').show();
            jQuery('#current-status').hide();
        }
    }
}

//cancel class
function CancelClass() {
    jQuery('#manage-class-div').hide();
    jQuery('#add-new-class-btn-div').show();
    jQuery('#classs-list').show();
    jQuery('#manage-class').hide();
}

// ochange payment option
function OnRepeat() {
    var Repeat = jQuery('#repeat').val();
    if(Repeat == 'weekly'|| Repeat == 'biweekly' || Repeat == 'monthly' || Repeat == 'yearly') {
        jQuery('#repeat-amount-div').show();
    } else {
        jQuery('#repeat-amount-div').hide();
    }
}

// ochange payment option
function OnAcceptPayment() {
    if(jQuery('#accept-payment').val() == 'yes') {
        jQuery('#payment-type-div').show();
        jQuery('#cost-div').show();
        if(jQuery('#payment-type').val() == 'percentage') {
            jQuery('#payment-amount-div').show();
        } else {
            jQuery('#payment-amount-div').hide();
        }
    } else {
        jQuery('#payment-type-div').hide();
        jQuery('#payment-amount-div').hide();
        jQuery('#cost-div').hide();
    }
}

function OnPaymentType() {
    if(jQuery('#payment-type').val() == 'percentage') {
        jQuery('#payment-amount-div').show();
    } else {
        jQuery('#payment-amount-div').hide();
    }
}

//perform user action
function PerformClassAction(Id, Action) {

    //if class actions save or update
    if(Action == "clone-class" || Action == "save-class" || Action == "update-class") {
        jQuery('.acberror').hide();
        var ClassId = jQuery('#class-id').val();
        var Name = jQuery('#name').val();
        var Desc = jQuery('#desc').val();
        var StartTime = jQuery('#start-time').val();
        var EndTime = jQuery('#end-time').val();
        var PaddingTime = 0; //jQuery('#padding-time').val();
        var StartDate = jQuery('#start-date').val();
        var EndDate = jQuery('#end-date').val();
        var Cost = jQuery('#cost').val();
        var Capacity = jQuery('#capacity').val();
        var Repeat = jQuery('#repeat').val();
        var RepeatAmount = jQuery('#repeat-amount').val();
        var Availability = jQuery('#availability').val();
        var Category = jQuery('#category').val();
        var InstructorsIds = 0;
        var AcceptPayment = 0;
        var PaymentType = 0;
        var PercentageAmount = 0;
        var Color = jQuery('#class-color').val();

        //validate -- name
        jQuery('.acberror').hide();
        if (Name == "") {
            jQuery("#name").after("<span class='acberror'>&nbsp;<strong><?php _e('Class name required.', 'appointzilla'); ?></strong></span>");
            return false;
        } else {
            var Res = isNaN(Name);
            if(Res == false) {
                jQuery("#name").after("<span class='acberror'>&nbsp;<strong><?php _e('Invalid class name.', 'appointzilla'); ?></strong></span>");
                return false;
            }
            var NameRegx = /^[a-zA-Z0-9- ]*$/;
            if(NameRegx.test(Name) == false) {
                jQuery("#name").after("<span class='acberror'>&nbsp;<strong><?php _e('No special characters allowed.', 'appointzilla'); ?></strong></span>");
                return false;
            }
        }

        //Start Time
        if(StartTime == "") {
            jQuery("#start-time").after("<span class='acberror'>&nbsp;<strong><?php _e('Select start time.', 'appointzilla'); ?></strong></span>");
            return false;
        }

        //End Time
        if(EndTime == "") {
            jQuery("#end-time").after("<span class='acberror'>&nbsp;<strong><?php _e('Select end time.', 'appointzilla'); ?></strong></span>");
            return false;
        }

        //Validate Time
        if(StartTime && EndTime) {
            if(StartTime == EndTime) {
                jQuery("#end-time").after('<span class="acberror">&nbsp;<strong><?php _e("Both Times cannot be equal.",'appointzilla'); ?></strong></span>');
                return false;
            }

            //convert both time into timestamp
            var stt = new Date("November 03, 2013 " + StartTime);
            stt = stt.getTime();

            var endt = new Date("November 03, 2013 " + EndTime);
            endt = endt.getTime();
            //console.log("Time: "+ stt + " Time2: " + endt);

            if(stt > endt) {
                jQuery("#start-time").after('<span class="acberror"><br>&nbsp;&nbsp;&nbsp;<strong><?php _e("Start Time must be smaller then End Time.",'appointzilla'); ?></strong></span>');
                jQuery("#end-time").after('<span class="acberror"><br>&nbsp;&nbsp;&nbsp;<strong><?php _e("End Time must be bigger then Start Time.",'appointzilla'); ?></strong></span>');
                return false;
            }
        }

        //Start Date
        if(StartDate == "") {
            jQuery("#start-date").after("<span class='acberror'>&nbsp;<strong><?php _e('Select start date.', 'appointzilla'); ?></strong></span>");
            return false;
        }

        //End Date
        if(EndDate == "") {
            jQuery("#end-date").after("<span class='acberror'>&nbsp;<strong><?php _e('Select end date.', 'appointzilla'); ?></strong></span>");
            return false;
        }

        //Validate Date
        if(StartDate && EndDate) {

            var StartDate = jQuery("#start-date").datepicker({ dateFormat: 'yyyy-mm-dd' }).val();
            var EndDate = jQuery("#end-date").datepicker({ dateFormat: 'yyyy-mm-dd' }).val();

            //required format is YYYY-MM-DD
            var StartDate2 = new Date(StartDate);
            var EndDate2 = new Date(EndDate);
            var DIfferance = new Date(EndDate2 - StartDate2);
            var Days = ((DIfferance)/1000/60/60/24);
            //console.log("Date-Diffrance: "+ DIfferance );
            //console.log("Days: "+ Days );

            if(Days < 0) {
                jQuery("#start-date").after("<span class='acberror'><br>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php _e("Start Date must be smaller then End Date.",'appointzilla'); ?></strong></span>");
                jQuery("#end-date").after("<span class='acberror'><br>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php _e("End Time must be bigger then Start Date.",'appointzilla'); ?></strong></span>");
                return false;
            }
        }

        //Cost
        if(Cost == "") {
            jQuery("#cost").after("<span class='acberror'>&nbsp;<strong><?php _e('Cost required.', 'appointzilla'); ?></strong></span>");
            return false;
        } else {
            if(isNaN(Cost)) { //if not numbers
                jQuery("#cost").after("<span class='acberror'>&nbsp;<strong><?php _e('Invalid cost value.', 'appointzilla'); ?></strong></span>");
                return false;
            }
            if(Cost < 0) { //if negative value
                jQuery("#cost").after("<span class='acberror'>&nbsp;<strong><?php _e('Invalid cost value.', 'appointzilla'); ?></strong></span>");
                return false;
            }
        }

        //Capacity
        if(Capacity == "") {
            jQuery("#capacity").after("<span class='acberror'>&nbsp;<strong><?php _e('Capacity required.', 'appointzilla'); ?></strong></span>");
            return false;
        } else {
            if(isNaN(Capacity)) { //if not numbers
                jQuery("#capacity").after("<span class='acberror'>&nbsp;<strong><?php _e('Invalid capacity value.', 'appointzilla'); ?></strong></span>");
                return false;
            }
            if(Capacity < 1) { //if negative value and at least 1
                jQuery("#capacity").after("<span class='acberror'>&nbsp;<strong><?php _e('Capacity value at laest 1 or more required.', 'appointzilla'); ?></strong></span>");
                return false;
            }
        }

        //Repeat
        if(Repeat == "weekly" || Repeat == "biweekly" || Repeat == "monthly" || Repeat == "yearly") {
            if(RepeatAmount == "") {
                jQuery("#repeat-amount").after("<span class='acberror'>&nbsp;<strong><?php _e('Repeat amount required.', 'appointzilla'); ?></strong></span>");
                return false;
            } else {
                if(isNaN(RepeatAmount)) { //if not numbers
                    jQuery("#repeat-amount").after("<span class='acberror'>&nbsp;<strong><?php _e('Invalid repeat amount value.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
                if(RepeatAmount < 0) { //if negative value and at least 1
                    jQuery("#repeat-amount").after("<span class='acberror'>&nbsp;<strong><?php _e('Repeat amount value at laest 0 or more required.', 'appointzilla'); ?></strong></span>");
                    return false;
                }
            }
        }

        //Assign Color
        if(Color == "") {
            jQuery("#class-color").after("<span class='acberror'>&nbsp;<strong><?php _e('Select any color for class.', 'appointzilla'); ?></strong></span>");
            return false;
        }

        var PostData1 = "&ClassId=" + ClassId + "&Name=" + Name + "&Desc=" + Desc + "&StartTime=" +  StartTime + "&EndTime=" + EndTime + "&PaddingTime=" + PaddingTime;
        var PostData2 = "&StartDate=" + StartDate + "&EndDate=" + EndDate + "&Cost=" + Cost + "&Capacity=" + Capacity + "&Repeat="+ Repeat;
        var PostData3 = "&RepeatAmount=" + RepeatAmount +"&Availability=" + Availability + "&Category=" + Category + "&InstructorsIds=" + InstructorsIds;
        var PostData4 = "&AcceptPayment=" + AcceptPayment + "&PaymentType=" + PaymentType + "&PercentageAmount=" + PercentageAmount + "&Color=" + Color ;

        //show loading n hide manage class div
        jQuery('#manage-class-div').hide();
        jQuery('#current-status').show();
        var PostData =  "PerformClassAction=" + Action  + PostData1 + PostData2 + PostData3 + PostData4;
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : location.href,
            cache: false,
            data : PostData,
            complete : function() {  },
            success: function(data) {
                if(Action == "clone-class") {
                    alert("<?php _e('Class clone created successfully.','appointzilla'); ?>");
                    jQuery('#current-status').hide();
                    location.reload();
                }

                if(Action == "save-class") {
                    alert("<?php _e('Class added successfully.','appointzilla'); ?>");
                    jQuery('#current-status').hide();
                    location.reload();
                }

                if(Action == "update-class") {
                    alert("<?php _e('Class details updated successfully.','appointzilla'); ?>");
                    jQuery('#current-status').hide();
                    location.reload();
                }
            }
        });
    } // end of add n update class if
}

//apply selected color
function ChangeColor() {
    var Color = jQuery("#class-color").val();
    jQuery("#class-color").css('background-color', Color);
}
</script>
<!-- JS  Works  End -->


<!-- Data operations: Save / Update / Delete Start -->
<?php
/**
 * Category Actions --------------------------
 */
if(isset($_POST['PerformCategoryAction'])) {

    $Action = $_POST['PerformCategoryAction'];
    global $wpdb;
    $ClassesTable = $wpdb->prefix . "apcal_pre_classes";
    $ClassesCategoriesTable = $wpdb->prefix . "apcal_pre_classes_categories";

    //add category
    if($Action == 'add-category') {
        //print_r($_POST);
        $NewName = $_POST['NewName'];
        $AddSql = "INSERT INTO `$ClassesCategoriesTable` ( `id` , `name` ) VALUES ( NULL , '$NewName' );";
        if($wpdb->query($AddSql)) {
            return true;
        } else {
            return false;
        }
    }

    //rename category
    if($Action == 'rename-category') {
        $Id = $_POST['Id'];
        $NewName = $_POST['NewName'];
        $UpdateSql = "UPDATE `$ClassesCategoriesTable` SET `name` = '$NewName' WHERE `id` = '$Id';";
        if($wpdb->query($UpdateSql)) {
            return true;
        } else {
            return false;
        }
    }

    //delete category
    if($Action == 'delete-category') {
        $CategoryId = $_POST['Id'];

        //search all classes which has assigned deleting category id and update it bu default id '1'
        $AllClasses = $wpdb->get_results("SELECT `id`, `category_id` FROM `$ClassesTable` WHERE `category_id` = '$CategoryId'");
        if(count($AllClasses)) {
            foreach($AllClasses as $Class) {
                $wpdb->query("UPDATE `$ClassesTable` SET `category_id` = '1' WHERE `id` = '$Class->id';");
            }
        }

        $DeleteSql = "DELETE FROM `$ClassesCategoriesTable` WHERE `id` = '$CategoryId'";
        if($wpdb->query($DeleteSql)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Classes Actions -----------------------
 */
if(isset($_POST['PerformClassAction'])) {

    $PerformAction = $_POST['PerformClassAction'];
    global $wpdb;
    $ClassesTable = $wpdb->prefix . "apcal_pre_classes";
    $InstructorsTable = $wpdb->prefix . "apcal_pre_instructors";

    //view class - nothing will do

    //clone class
    if($PerformAction == "clone-class") {
        $PerformClassAction = $_POST['PerformClassAction'];
        $Name = $_POST['Name'];
        $Desc = $_POST['Desc'];
        $StartTime = $_POST['StartTime'];
        $EndTime = $_POST['EndTime'];
        $PaddingTime = 0; //$_POST['PaddingTime'];
        $StartDate = date("Y-m-d", strtotime($_POST['StartDate']));
        $EndDate = date("Y-m-d", strtotime($_POST['EndDate']));
        $Cost = $_POST['Cost'];
        $Capacity = $_POST['Capacity'];
        $Repeat = $_POST['Repeat'];
        $RepeatAmount = $_POST['RepeatAmount'];
        $Availability = $_POST['Availability'];
        $CategoryId = $_POST['Category'];
        $InstructorsIds = 0;
        $AcceptPayment = 0;
        $PaymentType = 0;
        $PercentageAmount = 0;
        $Color = $_POST['Color'];
        $InsertQuery = "INSERT INTO `$ClassesTable` (`id`, `name`, `desc`, `start_time`, `end_time`, `padding_time`, `start_date`, `end_date`, `cost`, `repeat`, `repeat_amount`, `capacity`, `availability`, `category_id`, `instructors_ids`, `accept_payment`, `payment_type`, `percentage_amount`, `color`) VALUES (NULL, '$Name', '$Desc', '$StartTime', '$EndTime', '$PaddingTime', '$StartDate', '$EndDate', '$Cost', '$Repeat', '$RepeatAmount', '$Capacity', '$Availability', '$CategoryId', '$InstructorsIds', '$AcceptPayment', '$PaymentType', '$PercentageAmount', '$Color');";
        if($wpdb->query($InsertQuery)) {
            return true;
        } else {
            return false;
        }
    }

    //save or add class
    if($PerformAction == "save-class") {
        $PerformClassAction = $_POST['PerformClassAction'];
        $Name = $_POST['Name'];
        $Desc = $_POST['Desc'];
        $StartTime = $_POST['StartTime'];
        $EndTime = $_POST['EndTime'];
        $PaddingTime = 0; //$_POST['PaddingTime'];
        $StartDate = date("Y-m-d", strtotime($_POST['StartDate']));
        $EndDate = date("Y-m-d", strtotime($_POST['EndDate']));
        $Cost = $_POST['Cost'];
        $Capacity = $_POST['Capacity'];
        $Repeat = $_POST['Repeat'];
        $RepeatAmount = $_POST['RepeatAmount'];
        $Availability = $_POST['Availability'];
        $CategoryId = $_POST['Category'];
        $InstructorsIds = 0;
        $AcceptPayment = 0;
        $PaymentType = 0;
        $PercentageAmount = 0;
        $Color = $_POST['Color'];
        $InsertQuery = "INSERT INTO `$ClassesTable` (`id`, `name`, `desc`, `start_time`, `end_time`, `padding_time`, `start_date`, `end_date`, `cost`, `repeat`, `repeat_amount`, `capacity`, `availability`, `category_id`, `instructors_ids`, `accept_payment`, `payment_type`, `percentage_amount`, `color`) VALUES (NULL, '$Name', '$Desc', '$StartTime', '$EndTime', '$PaddingTime', '$StartDate', '$EndDate', '$Cost', '$Repeat', '$RepeatAmount', '$Capacity', '$Availability', '$CategoryId', '$InstructorsIds', '$AcceptPayment', '$PaymentType', '$PercentageAmount', '$Color');";
        if($wpdb->query($InsertQuery)) {
            return true;
        } else {
            return false;
        }
    }

    //update class
    if($PerformAction == "update-class") {
        $PerformClassAction = $_POST['PerformClassAction'];
        $ClassId = $_POST['ClassId'];
        $Name = $_POST['Name'];
        $Desc = $_POST['Desc'];
        $StartTime = $_POST['StartTime'];
        $EndTime = $_POST['EndTime'];
        $PaddingTime = 0; //$_POST['PaddingTime'];
        $StartDate = date("Y-m-d", strtotime($_POST['StartDate']));
        $EndDate = date("Y-m-d", strtotime($_POST['EndDate']));
        $Cost = $_POST['Cost'];
        $Repeat = $_POST['Repeat'];
        $RepeatAmount = $_POST['RepeatAmount'];
        $Capacity = $_POST['Capacity'];
        $Availability = $_POST['Availability'];
        $CategoryId = $_POST['Category'];
        $InstructorsIds = 0;
        $AcceptPayment = 0;
        $PaymentType = 0;
        $PercentageAmount = 0;
        $Color = $_POST['Color'];
        if($ClassId) {
            $UpdateQuery = "UPDATE `$ClassesTable` SET `name` = '$Name', `desc` = '$Desc', `start_time` = '$StartTime', `end_time` = '$EndTime', `padding_time` = '$PaddingTime', `start_date` = '$StartDate', `end_date` = '$EndDate', `cost` = '$Cost', `repeat` = '$Repeat', `repeat_amount` = '$RepeatAmount', `capacity` = '$Capacity', `availability` = '$Availability', `category_id` = '$CategoryId', `instructors_ids` = '$InstructorsIds', `accept_payment` = '$AcceptPayment', `payment_type` = '$PaymentType', `percentage_amount` = '$PercentageAmount', `color` = '$Color' WHERE `id` = '$ClassId';";
            if($wpdb->query($UpdateQuery)) {
                return true;
            } else {
                return false;
            }
        }

    }

    //delete class
    if($PerformAction == "delete-class") {
        $ClassId = $_POST['Id'];
        //now delete the class
        $DeleteSql = "DELETE FROM `$ClassesTable` WHERE `id` = '$ClassId'";
        if($wpdb->query($DeleteSql)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
<!-- Data operations: Save / Update / Delete  End  -->