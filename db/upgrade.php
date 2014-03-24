<?
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



?>
