<div id="manage-class">
    <?php
    $DateFormat = get_option('apcal_date_format');
    if($DateFormat == '') $DateFormat = "d-m-Y";
    $TimeFormat = get_option('apcal_time_format');
    if($TimeFormat == '') $TimeFormat = "h:i";
    if($TimeFormat == 'h:i') { $TOTimePickerFormat = "hh:mm TT"; $Tflag = 'true'; }
    if($TimeFormat == 'H:i') { $TOTimePickerFormat = "hh:mm"; $Tflag = 'false'; } ?>
    <script>
        jQuery(function() {
           //load date picker js option
            jQuery('#start-date').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            jQuery('#end-date').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });

        //time picker js
        jQuery(function(){
            jQuery('#start-time').clockface({
                format: 'hh:mm A'
            });
            jQuery('#end-time').clockface({
                format: 'hh:mm A'
            });
        });
    </script>
    <?php if(isset($_POST['action'])) {
        $Action = $_POST['action'];
        $Id = $_POST['Id'];

        global $wpdb;
        $ClassesTable = $wpdb->prefix . "apcal_pre_classes";
        $ClassesCategoriesTable = $wpdb->prefix . "apcal_pre_classes_categories";
        $InstructorsTable = $wpdb->prefix . "apcal_pre_instructors";
        $AllCategories = $wpdb->get_results("SELECT * FROM `$ClassesCategoriesTable`");
        $AllInstructors = $wpdb->get_results("SELECT `id`, `name` FROM `$InstructorsTable`");

        //add class
        if($Action == 'add-class') {
            $Id = -1;
            $PreCategoryId = 1;
            $Name = NULL;
            $Desc = NULL;
            $StartTime = NULL;
            $EndTime = NULL;
            $PaddingTime = 0;
            $StartDate = NULL;
            $EndDate = NULL;
            $Cost = 0;
            $Capacity = 1;
            $Repeat = "none";
            $RepeatAmount = 0;
            $Availability = 'yes';
            $CategoryId = 1;
            $Color = "";
        }

        //view or update class action
        if($Action == 'view-class' || $Action == 'update-class' || $Action == 'clone-class') {
            // get all details
            $GetDataSql = "SELECT * FROM `$ClassesTable` WHERE `id` = '$Id'";
            $ClassesData = $wpdb->get_row($GetDataSql);
            $Id = $ClassesData->id;
            $PreCategoryId = $ClassesData->category_id;
            $Name = $ClassesData->name;
            $Desc = $ClassesData->desc;
            $StartTime = $ClassesData->start_time;
            $EndTime = $ClassesData->end_time;
            $PaddingTime = $ClassesData->padding_time;
            $StartDate = date("Y-m-d", strtotime($ClassesData->start_date));
            $EndDate = date("Y-m-d", strtotime($ClassesData->end_date));
            $Cost = $ClassesData->cost;
            $Capacity = $ClassesData->capacity;
            $Repeat = $ClassesData->repeat;
            $RepeatAmount = $ClassesData->repeat_amount;
            $Availability = $ClassesData->availability;
            $CategoryId = $ClassesData->category_id;
            $Color = $ClassesData->color;
        }
        ?>
        <div class="borBox form-horizontal">
        <?php   if($Action == 'add-class') { ?>
            <h3 style="margin-left: 30px;"><i class="fa fa-plus"></i> <?php _e("Add New Class", "appointzilla"); ?></h3>
        <?php } if($Action == 'view-class') { ?>
            <h3 style="margin-left: 30px;"><i class="fa fa-eye"></i> <?php _e("View Class", "appointzilla"); ?></h3>
        <?php } if($Action == 'update-class') { ?>
            <h3 style="margin-left: 30px;"><i class="fa fa-edit"></i> <?php _e("Update Class", "appointzilla"); ?></h3>
        <?php } ?>
        <input type="hidden" id="class-id" name="class-id" value="<?php echo $Id; ?>">

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Name", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Type class Name Here" id="name" name="name" value="<?php echo ucwords($Name); ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Description", "appointzilla"); ?></label>
            <div class="control pull-left">
                <textarea placeholder="Type Class Description Here" id="desc" name="desc" style="margin-left: 15px;" rows="5"><?php echo ucfirst($Desc); ?></textarea>
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Start Time", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Select Class Start Time" id="start-time" name="start-time" value="<?php echo $StartTime; ?>" style="height: 32px; margin-left: 15px;">
            </div>

            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("End Time", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Select Class End Time" id="end-time" name="end-time" value="<?php echo $EndTime; ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Start Date", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Select Class Start Date" id="start-date" name="start-date" value="<?php echo $StartDate; ?>" style="height: 32px; margin-left: 15px;">
            </div>

            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("End Date", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Select Class End Date" id="end-date" name="end-date" value="<?php echo $EndDate; ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div id="cost-div"class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Cost", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Type Class Cost Here" id="cost" name="cost" value="<?php if($Cost) { echo $Cost; } else { echo "0"; }  ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Capacity", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Type class Name Here" id="capacity" name="capacity" value="<?php echo $Capacity; ?>" style="height: 32px; margin-left: 15px;">
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Repeat Type", "appointzilla"); ?></label>
            <div class="control pull-left">
                <select id="repeat" name="repeat" onchange="return OnRepeat();" style="height: 32px; margin-left: 15px;">
                    <option value="none" <?php if($Repeat == "none") echo "selected='selected'"; ?>><?php _e("None", "appointzilla"); ?></option>
                    <option value="weekly" <?php if($Repeat == "weekly") echo "selected='selected'"; ?>><?php _e("Weekly", "appointzilla"); ?></option>
                    <option value="biweekly" <?php if($Repeat == "biweekly") echo "selected='selected'"; ?>><?php _e("Bi Weekly", "appointzilla"); ?></option>
                    <option value="monthly" <?php if($Repeat == "monthly") echo "selected='selected'"; ?>><?php _e("Monthly", "appointzilla"); ?></option>
                    <option value="yearly" <?php if($Repeat == "yearly") echo "selected='selected'"; ?>><?php _e("Yearly", "appointzilla"); ?></option>
                </select>
            </div>
        </div>

        <!--hide repeat value option-->
        <div id="repeat-amount-div" style="display: <?php if($Repeat == "weekly" || $Repeat == "monthly") echo ""; else echo "none"; ?>;">
            <div class="control-group">
                <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Repeat Amount", "appointzilla"); ?></label>
                <div class="control pull-left">
                    <input type="text" placeholder="Enter Repeat Amount Here" id="repeat-amount" name="repeat-amount" value="<?php echo $RepeatAmount; ?>" style="height: 32px; margin-left: 15px;">
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Availability", "appointzilla"); ?></label>
            <div class="control pull-left">
                <select id="availability" name="availability" style="height: 32px; margin-left: 15px;">
                    <option value="no" <?php if($Availability == "no") echo "selected='selected'"; ?>><?php _e("No", "appointzilla"); ?></option>
                    <option value="yes" <?php if($Availability == "yes") echo "selected='selected'"; ?>><?php _e("Yes", "appointzilla"); ?></option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Category", "appointzilla"); ?></label>
            <div class="control pull-left">
                <select id="category" name="category" style="margin-left: 15px;">
                    <?php
                    if(count($AllCategories)) {
                        foreach($AllCategories as $Category) {
                            ?>
                            <option value="<?php echo $Category->id; ?>" <?php if($Category->id == $PreCategoryId) echo "selected='selected'"; ?>><?php echo ucwords($Category->name); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Color", "appointzilla"); ?></label>
            <div class="control pull-left">
                <select id="class-color" name="class-color" onchange="return ChangeColor();" style="height: 32px; margin-left: 15px; background-color: <?php echo $Color; ?>">
                    <option value="" <?php if($Color == "") echo "selected='selected'"; ?>><?php _e("Select any color", "appointzilla")?></option>
                    <option value="#A0A0A0" <?php if($Color == "#A0A0A0") echo "selected='selected'"; ?> style="background-color: #A0A0A0;">Silver Chalice</option>
                    <option value="#82AF6F" <?php if($Color == "#82AF6F") echo "selected='selected'"; ?> style="background-color: #82AF6F;">Chelsea Cucumber</option>
                    <option value="#D15B47" <?php if($Color == "#D15B47") echo "selected='selected'"; ?> style="background-color: #D15B47;">Red Damask</option>
                    <option value="#9585BF" <?php if($Color == "#9585BF") echo "selected='selected'"; ?> style="background-color: #9585BF;">Lavender Purple</option>
                    <option value="#3A87AD" <?php if($Color == "#3A87AD") echo "selected='selected'"; ?> style="background-color: #3A87AD;">Boston Blue</option>
                    <option value="#D6487E" <?php if($Color == "#D6487E") echo "selected='selected'"; ?> style="background-color: #D6487E;">Cabaret</option>
                    <option value="#7FCAFF" <?php if($Color == "#7FCAFF") echo "selected='selected'"; ?> style="background-color: #7FCAFF;">Malibu Lite</option>
                    <option value="#7F97FF" <?php if($Color == "#7F97FF") echo "selected='selected'"; ?> style="background-color: #7F97FF;">Malibu Dark</option>
                    <option value="#E887FF" <?php if($Color == "#E887FF") echo "selected='selected'"; ?> style="background-color: #E887FF;">Heliotrope Lite</option>
                    <option value="#A77FFF" <?php if($Color == "#A77FFF") echo "selected='selected'"; ?> style="background-color: #A77FFF;">Heliotrope Dark</option>
                    <option value="#FF7FB0" <?php if($Color == "#FF7FB0") echo "selected='selected'"; ?> style="background-color: #FF7FB0;">Tickle Me Pink</option>
                    <option value="#FF9C7E" <?php if($Color == "#FF9C7E") echo "selected='selected'"; ?> style="background-color: #FF9C7E;">Vivid Tangerine</option>
                    <option value="#6CF6CC" <?php if($Color == "#6CF6CC") echo "selected='selected'"; ?> style="background-color: #6CF6CC;">Aquamarine</option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
            <div class="control pull-left">
                <span style="margin-left: 15px;">
                    <?php if($Action == 'add-class') { ?>
                        <button class="btn btn-sharp btn-success" id="create-class-btn" onclick="return PerformClassAction(<?php echo $Id; ?>, 'save-class');"><strong><i class="fa fa-save"></i> <?php _e("Create Class", "appointzilla"); ?></strong></button>
                    <?php }
                    if($Action == 'update-class') { ?>
                        <button class="btn btn-sharp btn-success" id="update-class-btn" onclick="return PerformClassAction( <?php echo $Id; ?>, 'update-class');"><strong><i class="fa fa-edit"></i> <?php _e("Update Class", "appointzilla"); ?></strong></button>
                    <?php } if($Action == 'clone-class') { ?>
                        <button class="btn btn-sharp btn-success" id="update-class-btn" onclick="return PerformClassAction( <?php echo $Id; ?>, 'clone-class');"><strong><i class="fa fa-edit"></i> <?php _e("Create Clone", "appointzilla"); ?></strong></button>
                    <?php } ?>
                    <button class="btn btn-sharp btn-danger" id="cancel-btn" onclick="return CancelClass();"><strong><i class="fa fa-times"></i> <?php _e("Cancel", "appointzilla"); ?></strong></button>
                </span>
            </div>
        </div>
        </div><?php
    } ?>
</div>


<div id="add-manage-category-div" style="display: none;">
    <div class="borBox form-horizontal">
        <h3 style="margin-left: 30px;"><i class="fa fa-plus"></i> <?php _e("Add New Category", "appointzilla"); ?></h3>
        <div class="control-group">
            <label class="label label-info span2" style="padding: 8px 10px;"><?php _e("Category Name", "appointzilla"); ?></label>
            <div class="control pull-left">
                <input type="text" placeholder="Type Category Name Here" id="new-category-name" name="new-category-name" style="height: 32px; margin-left: 15px;">
            </div>
        </div>
        <div class="control-group">
            <label class=" span2" style="padding: 8px 10px;">&nbsp;</label>
            <div class="control pull-left">
                <span style="margin-left: 15px;">
                    <button class="btn btn-sharp btn-success" id="create-category-btn" onclick="return PerformCategoryAction(-1, 'add-category');"><i class="fa fa-save"></i> <?php _e("Create Category", "appointzilla"); ?></button>
                    <button class="btn btn-sharp btn-danger" id="cancel-btn" onclick="return CancelCategoryAddBtnClick();"><i class="fa fa-times"></i> <?php _e("Cancel", "appointzilla"); ?></button>
                </span>
            </div>
        </div>
    </div>
</div>