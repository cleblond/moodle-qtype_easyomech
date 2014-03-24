<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('qtype_easyomech_options', get_string('easyomech_options', 'qtype_easyomech'),
                   get_string('configeasyomechoptions', 'qtype_easyomech'), '', PARAM_TEXT));
}

