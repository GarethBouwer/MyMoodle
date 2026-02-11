<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $name = 'block_sortedcourses/sortmode';
    $title = get_string('sortmode', 'block_sortedcourses');
    $description = get_string('sortmode_desc', 'block_sortedcourses');

    $options = [
        'idnumber' => get_string('sortmode_idnumber', 'block_sortedcourses'),
        'category' => get_string('sortmode_category', 'block_sortedcourses'),
    ];

    $default = 'idnumber';

    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $settings->add($setting);
}
