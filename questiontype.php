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
 * Question type class for the easyomech question type.
 *
 * @package    qtype
 * @subpackage easyomech
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/question/type/easyomech/question.php');
require_once($CFG->dirroot . '/question/type/shortanswer/questiontype.php');

//	echo "HERE";
/**
 * The easyomech question type.
 *
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_easyomech extends qtype_shortanswer {
    public function extra_question_fields() {
        return array('question_easyomech', 'answers', 'hideproducts', 'orderimportant');
    }

    public function questionid_column_name() {
        return 'question';
    }
	protected function initialise_question_instance(question_definition $question, $questiondata) {
        $questiondata->options->usecase = '';
		parent::initialise_question_instance($question, $questiondata);
    }
	
	/**
    * Provide export functionality for xml format
    * @param question object the question object
    * @param format object the format object so that helper methods can be used 
    * @param extra mixed any additional format specific data that may be passed by the format (see format code for info)
    * @return string the data to append to the output buffer or false if error
    */
 /*   function export_to_xml( $question, qformat_xml $format, $extra=null ) {
		// Write out all the answers
		$expout = $format->write_answers($question->options->answers);
        return $expout;
    }

    function import_from_xml($data, $question, qformat_xml $format, $extra=null) {
        if (!array_key_exists('@', $data)) {
            return false;
        }
        if (!array_key_exists('type', $data['@'])) {
            return false;
        }
        if ($data['@']['type'] == 'easyomech') {

            // get common parts
            $question = $format->import_headers($data);

            // header parts particular to easyomech
            $question->qtype = 'easyomech';

		// run through the answers
            $answers = $data['#']['answer'];  
            $a_count = 0;
            foreach ($answers as $answer) {
                $ans = $format->import_answer( $answer );
                $question->answer[$a_count] = $ans->answer;
                $question->fraction[$a_count] = $ans->fraction;
                $question->feedback[$a_count] = $ans->feedback;
                ++$a_count;
            }
			
			$format->import_hints($question, $data);

            return $question;
        }
        return false;
    }*/
}
