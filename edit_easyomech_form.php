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

/**
 * Defines the editing form for the easyomech question type.
 *
 * @package    qtype
 * @subpackage easyomech
 * @copyright  2014 and onward Carl LeBlond
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/*
 * easyomech question editing form definition.
 *
 * @copyright  2014 onwards Carl LeBlond
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/question/type/shortanswer/edit_shortanswer_form.php');


/**
 * Calculated question type editing form definition.
 *
 * @copyright  2007 Jamie Pratt me@jamiep.org
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_easyomech_edit_form extends qtype_shortanswer_edit_form {

    protected function definition_inner($mform) {
        global $PAGE, $CFG;

        $PAGE->requires->js('/question/type/easyomech/easyomech_script.js');
        $PAGE->requires->css('/question/type/easyomech/easyomech_styles.css');
        $mform->addElement('static', 'answersinstruct',
        get_string('correctanswers', 'qtype_easyomech'),
        get_string('filloutoneanswer', 'qtype_easyomech'));
        $mform->closeHeaderBefore('answersinstruct');

        $menu = array(
           get_string('caseshowproducts', 'qtype_easyomech'),
             get_string('casenoshowproducts', 'qtype_easyomech')
        );
        $mform->addElement('select', 'hideproducts',
                get_string('caseshowornoshowproducts', 'qtype_easyomech'), $menu);

        $menu = array(
           get_string('ordernotimportant', 'qtype_easyomech'),
           get_string('orderimportant', 'qtype_easyomech')
        );
        $mform->addElement('select', 'orderimportant',
        get_string('caseorderimportant', 'qtype_easyomech'), $menu);
        $mform->addElement('html', html_writer::start_tag('div', array('style' => 'width:650px;', 'id' => 'appletdiv')));
        $mform->addElement('html', html_writer::start_tag('div', array('style' => 'float: left;font-style: italic ;')));
        $mform->addElement('html', html_writer::start_tag('small'));
        $easyomechhomeurl = 'http://www.chemaxon.com';
        $mform->addElement('html', html_writer::link($easyomechhomeurl, get_string('easyomecheditor', 'qtype_easyomech')));
        $mform->addElement('html', html_writer::empty_tag('br'));
        $mform->addElement('html', html_writer::tag('span', get_string('author', 'qtype_easyomech'),
        array('class' => 'easyomechauthor')));
        $mform->addElement('html', html_writer::end_tag('small'));
        $mform->addElement('html', html_writer::end_tag('div'));
        $mform->addElement('html', html_writer::end_tag('div'));

        // Add applet to page
        $jsmodule = array(
            'name'     => 'qtype_easyomech',
            'fullpath' => '/question/type/easyomech/easyomech_script.js',
            'requires' => array(),
            'strings' => array(
                array('enablejava', 'qtype_easyomech')
            )
        );

        $PAGE->requires->js_init_call('M.qtype_easyomech.insert_applet',
                                      array(),
                                      true,
                                      $jsmodule);



        // Add structure to applet.
        $jsmodule = array(
            'name'     => 'qtype_easyomech',
            'fullpath' => '/question/type/easyomech/easyomech_script.js',
            'requires' => array(),
            'strings' => array(
                array('enablejava', 'qtype_easyomech')
            )
        );

        $PAGE->requires->js_init_call('M.qtype_easyomech.insert_structure_into_applet',
                                      array(),
                                      true,
                                      $jsmodule);

        $this->add_per_answer_fields($mform, get_string('answerno', 'qtype_easyomech', '{no}'),
                question_bank::fraction_options());

        $this->add_interactive_settings();
        $PAGE->requires->js_init_call('M.qtype_easyomech.init_getanswerstring', array($CFG->version));
    }

    protected function get_per_answer_fields($mform, $label, $gradeoptions,
            &$repeatedoptions, &$answersoption) {

        $repeated = parent::get_per_answer_fields($mform, $label, $gradeoptions,
                $repeatedoptions, $answersoption);
        $scriptattrs = 'class = id_insert';
        // Construct the insert button.
        //$scriptattrs = 'onClick = "getSmilesEdit(this.name, \'mrv\')"';
        $insertbutton = $mform->createElement('button', 'insert', get_string('insertfromeditor', 'qtype_easyomech'), $scriptattrs);
        array_splice($repeated, 2, 0, array($insertbutton));

        return $repeated;
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        return $question;
    }

    public function qtype() {
        return 'easyomech';
    }
}
