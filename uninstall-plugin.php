<?php
// uninstall class booking plugin
if(isset($_POST['uninstallapcal'])) {
    global $wpdb;
    //18. drop classes table
    $ClassesTable = $wpdb->prefix . "apcal_pre_classes";
    $wpdb->query("DROP TABLE `$ClassesTable`");

    //1. drop classes categories table
    $ClassesCategoriesTable = $wpdb->prefix . "apcal_pre_classes_categories";
    $wpdb->query("DROP TABLE `$ClassesCategoriesTable`");

    //2. drop instructors table
    $InstructorsTable = $wpdb->prefix . "apcal_pre_instructors";
    $wpdb->query("DROP TABLE `$InstructorsTable`");

    //3. drop instructors groups table
    $InstructorsGroupsTable = $wpdb->prefix . "apcal_pre_instructors_groups";
    $wpdb->query("DROP TABLE `$InstructorsGroupsTable`");

    //4. drop class client table
    $ClassClientTable = $wpdb->prefix . "apcal_pre_class_clients";
    $wpdb->query("DROP TABLE `$ClassClientTable`");

    //5. drop manage class bookings table
    $ClassBookingsTable = $wpdb->prefix . "apcal_pre_class_bookings";
    $wpdb->query("DROP TABLE `$ClassBookingsTable`");

    //6. drop payments table
    $PaymentsTable = $wpdb->prefix . "apcal_pre_payments";
    $wpdb->query("DROP TABLE `$PaymentsTable`");

    //7. drop timezone table
    $TimeZoneTable = $wpdb->prefix . "apcal_pre_timezones";
    $wpdb->query("DROP TABLE `$TimeZoneTable`");

    //delete class booking options & settings
    delete_option('acb_class_booking_settings');

    //delete calendar options & settings
    delete_option('acb_calendar_settings');

    //delete payment option & settings
    delete_option('acb_payment_settings');

    //delete notification settings
    delete_option('acb_notification_settings');

    //delete google calendar sync option & settings
    delete_option('acb_google_calendar_sync_settings');

    //delete notification messages
    //-client message
    delete_option('on_booking_client_subject');
    delete_option('on_booking_client_body');
    delete_option('on_approve_client_subject');
    delete_option('on_approve_client_body');
    delete_option('on_cancel_client_subject');
    delete_option('on_cancel_client_body');

    //-admin message
    delete_option('on_booking_admin_subject');
    delete_option('on_booking_admin_body');

    //-instructor message
    delete_option('on_booking_instructor_subject');
    delete_option('on_booking_instructor_body');
    delete_option('on_approve_instructor_subject');
    delete_option('on_approve_instructor_body');
    delete_option('on_cancel_instructor_subject');
    delete_option('on_cancel_instructor_body');


    // DEACTIVATE APPOINTMENT CALENDAR PLUGIN
    deactivate_plugins($PluginName = plugin_basename(__DIR__)."/appointzilla-class-bookings.php");
    ?>
    <div class="alert" style="width:95%; margin-top:10px;">
        <p><?php _e('Appointzilla Class Bookings Premium Plugin has been successfully removed. It can be re-activated from the', 'appointzilla'); ?> <strong><a href="plugins.php"><?php _e('Plugins Page', 'appointzilla'); ?></a></strong>.</p>
    </div>
    <?php
    return;
}
?>

<?php
if(isset($_GET['page']) == 'uninstall-plugin') { ?>

<div style="background:#C3D9FF; margin-bottom:10px; padding-left:10px;">
  <h3><?php _e('Remove Plugin', 'appointzilla'); ?></h3> 
</div>

<div class="alert alert-error" style="width:95%;">
    <form method="post">
        <h3><?php _e('Remove Appointzilla Class Bookings Plugin', 'appointzilla'); ?></h3>
        <p><?php _e('This operation will delete all Appointzilla Class Bookings data & settings. If you continue, You will not be able to retrieve or restore your booking entries.', 'appointzilla'); ?></p>
        <p><button id="uninstallapcal" type="submit" class="btn btn-danger" name="uninstallapcal" value="" onclick="return confirm('<?php _e('Warning! Appointzilla Class Bookings data & settings, including appointment entries will be deleted. This cannot be undone. OK to delete, CANCEL to stop', 'appointzilla'); ?>')" ><i class="icon-trash icon-white"></i> <?php _e('REMOVE PLUGIN', 'appointzilla'); ?></button></p>
    </form>
</div><?php
} ?>