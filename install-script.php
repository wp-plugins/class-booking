<?php global $wpdb;
$wpdb->query('SET @@global.sql_mode = "";');
$AdminEmail = get_bloginfo('admin_email');


//Default Class Booking Options & Settings
$AllClassBookingSettingsArray = array(
    'acb_client_registration' => "yes",
    'acb_booking_status' => "yes",
    'acb_admin_timezone' => 10,
    'acb_date_format' => "d-m-Y",
    'acb_time_format' => "h:i"
);
update_option('acb_class_booking_settings', serialize($AllClassBookingSettingsArray));

//Default Calendar Options & Settings
$CalendarSettingsArray = array(
    'acb_calendar_slot_time' => '15',                           // 30 min slots
    'acb_day_start_time' => '10:00 AM',                         // 10:00 AM
    'acb_day_end_time' => '5:00 PM',                            // 5:00 PM
    'acb_calendar_view' => 'month',                             // month
    'acb_calendar_start_day' => '1',                            // monday
    'acb_booking_button_text' => 'Schedule A Class',            // schedule A class booking
    'acb_booking_user_timeslot' => '30',                        // user booking time slots
    'acb_show_service_cost' => 'yes',       // for service cost hide or show
    'acb_show_service_duration' => 'yes',    // for service duration hide or show
    'acb_booking_instructions' => 'Put your booking instructions here.<br>Or you can save It blank in case of nothing want to display.', // booking instruction befor booking button
    'acb_thank_you_message' => __("Your booking has been completed. Thanks you for booking with us.", "appointzilla"),
);
add_option('acb_calendar_settings', serialize($CalendarSettingsArray));

//Default Notification Options & Settings
$NotificationSettingsArray = array(
    'acb_enable_notification' => 'yes',
    'acb_notify_admin' => 'yes',
    'acb_notify_instructor' => 'yes',
    'acb_notify_client' => 'yes',
    'acb_notification_type' => 'wp-mail',
    'acb_wp_admin_email' => $AdminEmail,
    'acb_php_admin_email' => $AdminEmail,
    'acb_smtp_host_name' => '',
    'acb_smtp_port' => '',
    'acb_smtp_admin_email' => '',
    'acb_smtp_admin_password' => ''
);
add_option('acb_notification_settings', serialize($NotificationSettingsArray));

//Default Notification Messages
//-on booking notify client
$Subject = "[blog-name]: Your booking status is [book-status]";
$Body = "
Hi [client-name],

Thank you for booking with [blog-name].

Your booking for [class-name] on [book-date] at [book-time].

Currently, your booking status is [book-status].

You will get a confirmation mail once admin accepts the booking request.

Best Wishes
[blog-name].";
add_option('on_booking_client_subject', $Subject);
add_option('on_booking_client_body', $Body);

//-on approve notify client
$Subject = "[blog-name]: Your booking status is [book-status].";
$Body = "
Hi [client-name],

Your booking has been [book-status] by admin.

Now, your booking for [class-name] on [book-date] at [book-time].

Thank you for booking with [blog-name].

Best Wishes
[blog-name].
";
add_option('on_approve_client_subject', $Subject);
add_option('on_approve_client_body', $Body);

//-on cancel notify client
$Subject = "[blog-name]: Your booking status is [book-status].";
$Body = "
Hi [client-name],

Sorry! Due to some reason we are unable to complete your booking request.

Now, your booking for [class-name] on [book-date] at [book-time] has been [book-status] by admin.

Thank you for booking with [blog-name].

Best Wishes
[blog-name].
";
add_option('on_cancel_client_subject', $Subject);
add_option('on_cancel_client_body', $Body);

//-on booking notify admin
$Subject = "[blog-name]: New booking scheduled by [client-name].";
$Body = "
Hi Admin,

Bookings details are:

Client Name: [client-name]
Client Email:[client-email]
Client Phone: [client-phone]
Client Special Instruction: [client-sn]

Booking For: [class-name]
Booking Date: [book-date]
Booking Time: [book-time]
Booking Status: [book-status]

View this booking details at admin dashboard.

Best Regards
[blog-name].
";
add_option('on_booking_admin_subject', $Subject);
add_option('on_booking_admin_body', $Body);



//Class Category Table
$ClassesCategoriesTable = $wpdb->prefix . "apcal_pre_classes_categories";
$ClassesCategoriesTable_SQL = "CREATE TABLE IF NOT EXISTS `$ClassesCategoriesTable` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, PRIMARY KEY (`id`) )DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
$wpdb->query($ClassesCategoriesTable_SQL);

    //insert a demo category
    $DemoCategoriesInsert_SQL = "INSERT INTO `$ClassesCategoriesTable` (`id`, `name`) VALUES (1, 'Demo Category');";
    $wpdb->query($DemoCategoriesInsert_SQL);

//Class Table
$ClassesTable = $wpdb->prefix . "apcal_pre_classes";
$ClassesTable_SQL = "CREATE TABLE IF NOT EXISTS `$ClassesTable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `desc` text NOT NULL,
  `start_time` text NOT NULL,
  `end_time` text NOT NULL,
  `padding_time` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `cost` float NOT NULL,
  `repeat` varchar(50) NOT NULL,
  `repeat_amount` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `availability` varchar(10) NOT NULL,
  `category_id` int(11) NOT NULL,
  `instructors_ids` text NOT NULL,
  `accept_payment` varchar(10) DEFAULT NULL,
  `payment_type` varchar(10) DEFAULT NULL,
  `percentage_amount` float DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
$wpdb->query($ClassesTable_SQL);

    //insert a demo class
    $InstructorsIds = serialize(array(1));
    $StartDate = date("Y-m-d");
    $EndDate = date( "Y-m-d", strtotime("+2 days", strtotime($StartDate)) );
    $DemoClassInsert_SQL = "INSERT INTO `$ClassesTable` (`id`, `name`, `desc`, `start_time`, `end_time`, `padding_time`, `start_date`, `end_date`, `cost`, `repeat`, `repeat_amount`, `capacity`, `availability`, `category_id`, `instructors_ids`, `accept_payment`, `payment_type`, `percentage_amount`, `color`) VALUES (1, 'Demo Class', 'This is demo class. You can edit this class.', '10:00 AM', '11:00 AM', 0, '$StartDate', '$EndDate', 49.99, 'monthly', 1, 25, 'yes', 1, '$InstructorsIds', 'no', 'percentage', 50, '#82AF6F');";
    $wpdb->query($DemoClassInsert_SQL);


//Instructor Group Table
$InstructorsGroupsTable = $wpdb->prefix . "apcal_pre_instructors_groups";
$InstructorsGroupsTable_SQL = "CREATE TABLE IF NOT EXISTS `$InstructorsGroupsTable` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, PRIMARY KEY (`id`) )DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
$wpdb->query($InstructorsGroupsTable_SQL);

    //insert a test group
    $TestGroupsInsert_SQL = "INSERT INTO `$InstructorsGroupsTable` (`id`, `name`) VALUES (1, 'Test Group');";
    $wpdb->query($TestGroupsInsert_SQL);


//Instructor Group
$InstructorsTable = $wpdb->prefix . "apcal_pre_instructors";
$InstructorsTable_SQL = "CREATE TABLE IF NOT EXISTS `$InstructorsTable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` text NOT NULL,
  `experience` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `instructor_hours` text NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
$wpdb->query($InstructorsTable_SQL);

    //insert a test instructor
    $TestInstructorInsert_SQL = "INSERT INTO `$InstructorsTable` (`id`, `name`, `email`, `phone`, `experience`, `group_id`, `instructor_hours`) VALUES (1, 'Test Instructor', '$AdminEmail', '1234567890', 5, 1, '');";
    $wpdb->query($TestInstructorInsert_SQL);

//Class Client Table
$ClassClientTable = $wpdb->prefix . "apcal_pre_class_clients";
$ClassClientTable_SQL = "CREATE TABLE IF NOT EXISTS `$ClassClientTable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `sn` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($ClassClientTable_SQL);

//Class Booking Table
$ClassBookingsTable = $wpdb->prefix . "apcal_pre_class_bookings";
$ClassBookingsTable_SQl = "CREATE TABLE IF NOT EXISTS `$ClassBookingsTable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `sn` text NOT NULL,
  `joining_date` date NOT NULL,
  `key` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `booked_by` varchar(50) NOT NULL,
  `book_date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `client_timezone_difference` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($ClassBookingsTable_SQl);

//Currency Table
$CurrencyTable = $wpdb->prefix . "apcal_pre_currency";
$CurrencyTableSQL = "CREATE TABLE IF NOT EXISTS `$CurrencyTable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(30) NOT NULL,
  `code` varchar(3) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($CurrencyTableSQL);

$CurrencyInsertSQL = "INSERT INTO `$CurrencyTable` (`id`, `currency_name`, `code`, `symbol`) VALUES
(1, 'United States dollar', 'USD', '&#36;'),
(2, 'Euro', 'EUR', '&euro;'),
(3, 'Japanese yen', 'JPY', '&yen;'),
(4, 'British pound', 'GBP', '&#163;'),
(5, 'Australian dollar', 'AUD', '&#36;'),
(6, 'Swiss franc', 'CHF', 'Fr'),
(7, 'Canadian dollar', 'CAD', '&#36;'),
(8, 'Hong Kong dollar', 'HKD', '&#36;'),
(9, 'Swedish krona', 'SEK', 'Kr'),
(10, 'New Zealand dollar', 'NZD', '&#36;'),
(11, 'Singapore dollar', 'SGD', '&#36;'),
(12, 'Norwegian krone', 'NOK', 'kr'),
(13, 'Mexican peso', 'MXN', '&#36;'),
(14, 'Indian rupee', 'INR', 'INR'),
(15, 'Brazilian real', 'BRL', 'R$'),
(16, 'Israeli new shekel', 'NIS', 'NIS'),
(17, 'Czech koruna', 'CZK', 'Kc'),
(18, 'Malaysian ringgit', 'MYR', 'RM'),
(19, 'Philippine peso', 'PHP', 'PHP'),
(20, 'New Taiwan dollar', 'TWD', 'NT$'),
(21, 'Thai baht', 'THB', 'THB'),
(22, 'Turkish lira', 'TL', 't');
";
$wpdb->query($CurrencyInsertSQL);

//Payments Table
$PaymentsTable = $wpdb->prefix . "apcal_pre_payments";
$PaymentsTableSQL = "
CREATE TABLE IF NOT EXISTS `$PaymentsTable` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `payment_amount` float NOT NULL,
  `payment_date` text NOT NULL,
  `payment_status` text NOT NULL,
  `txn_id` text NOT NULL,
  `gateway` text NOT NULL,
  `other_details` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($PaymentsTableSQL);

//Timezone Table
$TimeZoneTable = $wpdb->prefix . "apcal_pre_timezones";
$TimeZoneTableSQL = "CREATE TABLE IF NOT EXISTS `$TimeZoneTable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `GMT` varchar(5) COLLATE utf8_bin NOT NULL,
  `name` varchar(120) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
$wpdb->query($TimeZoneTableSQL);


$TimeZoneTableInsertSQL = "INSERT INTO `$TimeZoneTable` (`id`, `GMT`, `name`) VALUES
(1, '-12.0', '(GMT-12:00)-International Date Line West'),
(2, '-11.0', '(GMT-11:00)-Midway Island, Samoa'),
(3, '-10.0', '(GMT-10:00)-Hawaii'),
(4, '-9.0', '(GMT-09:00)-Alaska'),
(5, '-8.0', '(GMT-08:00)-Pacific Time (US & Canada); Tijuana'),
(6, '-7.0', '(GMT-07:00)-Arizona'),
(7, '-7.0', '(GMT-07:00)-Chihuahua, La Paz, Mazatlan'),
(8, '-7.0', '(GMT-07:00)-Mountain Time (US & Canada)'),
(9, '-6.0', '(GMT-06:00)-Central America'),
(10, '-6.0', '(GMT-06:00)-Central Time (US & Canada)'),
(11, '-6.0', '(GMT-06:00)-Guadalajara, Mexico City, Monterrey'),
(12, '-6.0', '(GMT-06:00)-Saskatchewan'),
(13, '-5.0', '(GMT-05:00)-Bogota, Lima, Quito'),
(14, '-5.0', '(GMT-05:00)-Eastern Time (US & Canada)'),
(15, '-5.0', '(GMT-05:00)-Indiana (East)'),
(16, '-4.0', '(GMT-04:00)-Atlantic Time (Canada)'),
(17, '-4.0', '(GMT-04:00)-Caracas, La Paz'),
(18, '-4.0', '(GMT-04:00)-Santiago'),
(19, '-3.5', '(GMT-03:30)-Newfoundland'),
(20, '-3.0', '(GMT-03:00)-Brasilia'),
(21, '-3.0', '(GMT-03:00)-Buenos Aires, Georgetown'),
(22, '-3.0', '(GMT-03:00)-Greenland'),
(23, '-2.0', '(GMT-02:00)-Mid-Atlantic'),
(24, '-1.0', '(GMT-01:00)-Azores'),
(25, '-1.0', '(GMT-01:00)-Cape Verde Is.'),
(26, '0.0', '(GMT)-Casablanca, Monrovia'),
(27, '0.0', '(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London'),
(28, '1.0', '(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
(29, '1.0', '(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
(30, '1.0', '(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris'),
(31, '1.0', '(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb'),
(32, '1.0', '(GMT+01:00)-West Central Africa'),
(33, '2.0', '(GMT+02:00)-Athens, Beirut, Istanbul, Minsk'),
(34, '2.0', '(GMT+02:00)-Bucharest'),
(35, '2.0', '(GMT+02:00)-Cairo'),
(36, '2.0', '(GMT+02:00)-Harare, Pretoria'),
(37, '2.0', '(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),
(38, '2.0', '(GMT+02:00)-Jerusalem'),
(39, '3.0', '(GMT+03:00)-Baghdad'),
(40, '3.0', '(GMT+03:00)-Kuwait, Riyadh'),
(41, '3.0', '(GMT+03:00)-Moscow, St. Petersburg, Volgograd'),
(42, '3.0', '(GMT+03:00)-Nairobi'),
(43, '3.5', '(GMT+03:30)-Tehran'),
(44, '4.0', '(GMT+04:00)-Abu Dhabi, Muscat'),
(45, '4.0', '(GMT+04:00)-Baku, Tbilisi, Yerevan'),
(46, '4.5', '(GMT+04:30)-Kabul'),
(47, '5.0', '(GMT+05:00)-Ekaterinburg'),
(48, '5.0', '(GMT+05:00)-Islamabad, Karachi, Tashkent'),
(49, '5.5', '(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi'),
(50, '5.75', '(GMT+05:45)-Kathmandu'),
(51, '6.0', '(GMT+06:00)-Almaty, Novosibirsk'),
(52, '6.0', '(GMT+06:00)-Astana, Dhaka'),
(53, '6.0', '(GMT+06:00)-Sri Jayawardenepura'),
(54, '6.5', '(GMT+06:30)-Rangoon'),
(55, '7.0', '(GMT+07:00)-Bangkok, Hanoi, Jakarta'),
(56, '7.0', '(GMT+07:00)-Krasnoyarsk'),
(57, '8.0', '(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi'),
(58, '8.0', '(GMT+08:00)-Irkutsk, Ulaan Bataar'),
(59, '8.0', '(GMT+08:00)-Kuala Lumpur, Singapore'),
(60, '8.0', '(GMT+08:00)-Perth'),
(61, '8.0', '(GMT+08:00)-Taipei'),
(62, '9.0', '(GMT+09:00)-Osaka, Sapporo, Tokyo'),
(63, '9.0', '(GMT+09:00)-Seoul'),
(64, '9.0', '(GMT+09:00)-Vakutsk'),
(65, '9.5', '(GMT+09:30)-Adelaide'),
(66, '9.5', '(GMT+09:30)-Darwin'),
(67, '10.0', '(GMT+10:00)-Brisbane'),
(68, '10.0', '(GMT+10:00)-Canberra, Melbourne, Sydney'),
(69, '10.0', '(GMT+10:00)-Guam, Port Moresby'),
(70, '10.0', '(GMT+10:00)-Hobart'),
(71, '10.0', '(GMT+10:00)-Vladivostok'),
(72, '11.0', '(GMT+11:00)-Magadan, Solomon Is., New Caledonia'),
(73, '12.0', '(GMT+12:00)-Auckland, Wellington'),
(74, '12.0', '(GMT+12:00)-Fiji, Kamchatka, Marshall Is.'),
(75, '-12.0', '(GMT-12:00)-International Date Line West'),
(76, '-11.0', '(GMT-11:00)-Midway Island, Samoa'),
(77, '-10.0', '(GMT-10:00)-Hawaii'),
(78, '-9.0', '(GMT-09:00)-Alaska'),
(79, '-8.0', '(GMT-08:00)-Pacific Time (US & Canada); Tijuana'),
(80, '-7.0', '(GMT-07:00)-Arizona'),
(81, '-7.0', '(GMT-07:00)-Chihuahua, La Paz, Mazatlan'),
(82, '-7.0', '(GMT-07:00)-Mountain Time (US & Canada)'),
(83, '-6.0', '(GMT-06:00)-Central America'),
(84, '-6.0', '(GMT-06:00)-Central Time (US & Canada)'),
(85, '-6.0', '(GMT-06:00)-Guadalajara, Mexico City, Monterrey'),
(86, '-6.0', '(GMT-06:00)-Saskatchewan'),
(87, '-5.0', '(GMT-05:00)-Bogota, Lima, Quito'),
(88, '-5.0', '(GMT-05:00)-Eastern Time (US & Canada)'),
(89, '-5.0', '(GMT-05:00)-Indiana (East)'),
(90, '-4.0', '(GMT-04:00)-Atlantic Time (Canada)'),
(91, '-4.0', '(GMT-04:00)-Caracas, La Paz'),
(92, '-4.0', '(GMT-04:00)-Santiago'),
(93, '-3.5', '(GMT-03:30)-Newfoundland'),
(94, '-3.0', '(GMT-03:00)-Brasilia'),
(95, '-3.0', '(GMT-03:00)-Buenos Aires, Georgetown'),
(96, '-3.0', '(GMT-03:00)-Greenland'),
(97, '-2.0', '(GMT-02:00)-Mid-Atlantic'),
(98, '-1.0', '(GMT-01:00)-Azores'),
(99, '-1.0', '(GMT-01:00)-Cape Verde Is.'),
(100, '0.0', '(GMT)-Casablanca, Monrovia'),
(101, '0.0', '(GMT)-Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London'),
(102, '1.0', '(GMT+01:00)-Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
(103, '1.0', '(GMT+01:00)-Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
(104, '1.0', '(GMT+01:00)-Brussels, Copenhagen, Madrid, Paris'),
(105, '1.0', '(GMT+01:00)-Sarajevo, Skopje, Warsaw, Zagreb'),
(106, '1.0', '(GMT+01:00)-West Central Africa'),
(107, '2.0', '(GMT+02:00)-Athens, Beirut, Istanbul, Minsk'),
(108, '2.0', '(GMT+02:00)-Bucharest'),
(109, '2.0', '(GMT+02:00)-Cairo'),
(110, '2.0', '(GMT+02:00)-Harare, Pretoria'),
(111, '2.0', '(GMT+02:00)-Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),
(112, '2.0', '(GMT+02:00)-Jerusalem'),
(113, '3.0', '(GMT+03:00)-Baghdad'),
(114, '3.0', '(GMT+03:00)-Kuwait, Riyadh'),
(115, '3.0', '(GMT+03:00)-Moscow, St. Petersburg, Volgograd'),
(116, '3.0', '(GMT+03:00)-Nairobi'),
(117, '3.5', '(GMT+03:30)-Tehran'),
(118, '4.0', '(GMT+04:00)-Abu Dhabi, Muscat'),
(119, '4.0', '(GMT+04:00)-Baku, Tbilisi, Yerevan'),
(120, '4.5', '(GMT+04:30)-Kabul'),
(121, '5.0', '(GMT+05:00)-Ekaterinburg'),
(122, '5.0', '(GMT+05:00)-Islamabad, Karachi, Tashkent'),
(123, '5.5', '(GMT+05:30)-Chennai, Kolkata, Mumbai, New Delhi'),
(124, '5.75', '(GMT+05:45)-Kathmandu'),
(125, '6.0', '(GMT+06:00)-Almaty, Novosibirsk'),
(126, '6.0', '(GMT+06:00)-Astana, Dhaka'),
(127, '6.0', '(GMT+06:00)-Sri Jayawardenepura'),
(128, '6.5', '(GMT+06:30)-Rangoon'),
(129, '7.0', '(GMT+07:00)-Bangkok, Hanoi, Jakarta'),
(130, '7.0', '(GMT+07:00)-Krasnoyarsk'),
(131, '8.0', '(GMT+08:00)-Beijing, Chongqing, Hong Kong, Urumqi'),
(132, '8.0', '(GMT+08:00)-Irkutsk, Ulaan Bataar'),
(133, '8.0', '(GMT+08:00)-Kuala Lumpur, Singapore'),
(134, '8.0', '(GMT+08:00)-Perth'),
(135, '8.0', '(GMT+08:00)-Taipei'),
(136, '9.0', '(GMT+09:00)-Osaka, Sapporo, Tokyo'),
(137, '9.0', '(GMT+09:00)-Seoul'),
(138, '9.0', '(GMT+09:00)-Vakutsk'),
(139, '9.5', '(GMT+09:30)-Adelaide'),
(140, '9.5', '(GMT+09:30)-Darwin'),
(141, '10.0', '(GMT+10:00)-Brisbane'),
(142, '10.0', '(GMT+10:00)-Canberra, Melbourne, Sydney'),
(143, '10.0', '(GMT+10:00)-Guam, Port Moresby'),
(144, '10.0', '(GMT+10:00)-Hobart'),
(145, '10.0', '(GMT+10:00)-Vladivostok'),
(146, '11.0', '(GMT+11:00)-Magadan, Solomon Is., New Caledonia'),
(147, '12.0', '(GMT+12:00)-Auckland, Wellington'),
(148, '12.0', '(GMT+12:00)-Fiji, Kamchatka, Marshall Is.'),
(149, '13.0', '(GMT+13:00)-Nuku''alofa ');
";
$wpdb->query($TimeZoneTableInsertSQL);