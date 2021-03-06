<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
defined('MOODLE_INTERNAL') || die();

function xmldb_qtype_easyomech_upgrade($oldversion = 0) {

    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2013093000) {

        // Define field orderimportant to be added to question_easyomech.
        $table = new xmldb_table('question_easyomech');
        $field = new xmldb_field('orderimportant', XMLDB_TYPE_INTEGER, '2', null, null, null, 0, 'hideproducts');

        // Conditionally launch add field orderimportant.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Easyomech savepoint reached.
        upgrade_plugin_savepoint(true, 2013093000, 'qtype', 'easyomech');
    }

    return true;
}

