<div class="bs-docs-example tooltip-demo">
    <div style="background:#C3D9FF; width:99%; padding-left:10px; border-radius: 4px;">
        <h3><i class="fa fa-cogs"></i> <?php _e('Setting Panel','appointzilla'); ?></h3>
        <ul class="nav nav-pills">
            <?php
            if(isset($_GET['show-setting']))
                $ShowSetting = $_GET['show-setting'];
            else
                $ShowSetting = '';
            ?>
            <li <?php if($ShowSetting == 'class-booking-settings') echo "Class='active'"; ?>><a href="?page=class-booking-settings&show-setting=class-booking-settings"><?php _e('Class Booking Settings' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowSetting == 'calendar-settings') echo "Class='active'"; ?>><a href="?page=class-booking-settings&show-setting=calendar-settings"><?php _e('Calendar Settings' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowSetting == 'notification-settings') echo "Class='active'"; ?>><a href="?page=class-booking-settings&show-setting=notification-settings"><?php _e('Notification Settings' ,'appointzilla'); ?></a></li>

            <li <?php if($ShowSetting == 'notification-message') echo "Class='active'"; ?>><a href="?page=class-booking-settings&show-setting=notification-message"><?php _e('Notification Message' ,'appointzilla'); ?></a></li>
        </ul>
    </div>
    <?php if(isset($_GET['page']) == 'page' && !isset($_GET['show-setting']) ) { ?>
        <div class="alert alert-info">
            <h4><?php _e('Settings Panel Allows Admin To Manage Following Settings' ,'appointzilla'); ?></h4>
            <h5><span class="badge badge-info">1</span> <?php _e("Manage Class Booking Settings" ,'appointzilla' ,'appointzilla'); ?></h5>
            <h5><span class="badge badge-info">2</span> <?php _e("Manage Calendar Settings" ,'appointzilla' ,'appointzilla'); ?></h5>
            <h5><span class="badge badge-info">3</span> <?php _e("Manage Notification Settings" ,'appointzilla'); ?></h5>
            <h5><span class="badge badge-info">4</span> <?php _e("Manage Notification Messages" ,'appointzilla'); ?></h5>
        </div>
    <?php
    }

    if(isset($_GET['show-setting'])) {

        if($_GET['show-setting'] == 'class-booking-settings') {
            include('settings/class-booking-settings.php');
        }

        if($_GET['show-setting'] == 'calendar-settings') {
            include('settings/calendar-settings.php');
        }

        if($_GET['show-setting'] == 'notification-settings') {
            include('settings/notification-settings.php');
        }

        if($_GET['show-setting'] == 'notification-message') {
            include('settings/notification-message.php');
        }
    }
    ?>
</div>
<!--tooltip div-->