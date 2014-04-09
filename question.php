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
 * easyomech Molecular Editor question definition class.
 *
 * @package    qtype
 * @subpackage easyomech
 * @copyright  2014 onwards Carl LeBlond 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $qa;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/shortanswer/question.php');

$generatedfeedback = "";

class qtype_easyomech_question extends qtype_shortanswer_question {
    public function compare_response_with_answer(array $response, question_answer $answer) {
        global $generatedfeedback, $DB;
        $test = $DB->get_record('user', array('id' => '1'));
        $orderimportant = $this->orderimportant;
        if (!array_key_exists('answer', $response) || is_null($response['answer'])) {

            return false;
        }
        // Strip arrows from mrv strings!
        $cmlans = new SimpleXMLElement($answer->answer);
        $cmlusr = new SimpleXMLElement($response['answer']);
        $arrowscorrect = 0;
        $i = 0;
        $arrowsusrall = "";
        // Check to see if usr submitted correct type of arrow.



/*
            echo "here1 <br>";
            echo "usr".(string)$cmlusr->MDocument[0]->MEFlow[0]->attributes()->headFlags."<br>";
            echo "ans".(string)$cmlans->MDocument[0]->MEFlow[0]->attributes()->headFlags;
*/
/*
            if ((string)$cmlusr->MDocument[0]->MEFlow[0]->attributes()->headFlags !=
(string)$cmlans->MDocument[0]->MEFlow[0]->attributes()->headFlags) {
                echo "here2";
                if ((string)$cmlans->MDocument[0]->MEFlow[0]->attributes()->headFlags == '2') {
                    $this->usecase = "This is a radical reaction but you used full arrow heads.<br>
                    You should use half arrow heads for radical reactions.  In radical reactions single electrons move.";

                } else {
                    $this->usecase = "This is a polar reaction but you used half arrow heads.<br>
                    You should use full arrow heads for polar reactions.  In polar reactions the electrons move in pairs.";
                }

                return 0;
            }
        }
*/
        foreach ($cmlusr->MDocument[0]->MEFlow as $meflowusr) {
            $numbasepointsusr = $meflowusr->MEFlowBasePoint->count();
            $numsetpointsusr = $meflowusr->MAtomSetPoint->count();
            if ($numbasepointsusr == 1) {
                $attrsusrstart = $meflowusr->MEFlowBasePoint[0]->attributes();
                $attrsusrfinish = $meflowusr->MAtomSetPoint[0]->attributes();
            } else {
                $attrsusrstart = $meflowusr->MAtomSetPoint[0]->attributes();
                $attrsusrfinish = $meflowusr->MAtomSetPoint[1]->attributes();
            }

            $arrowusr[$i] = $attrsusrstart.$attrsusrfinish;
            $arrowsusrall .= "*".$arrowusr[$i];
            $i = $i + 1;
            $numbasepointsans = $cmlans->MDocument[0]->MEFlow->MEFlowBasePoint->count();
        }
            $i = 0;
            $arrowsansall = "";
        foreach ($cmlans->MDocument[0]->MEFlow as $meflowans) {
                $numbasepointsans = $meflowans->MEFlowBasePoint->count();
                $numsetpointsans = $meflowans->MAtomSetPoint->count();
            if ($numbasepointsans == 1) {
                        $attrsansstart = $meflowans->MEFlowBasePoint[0]->attributes();
                        $attrsansfinish = $meflowans->MAtomSetPoint[0]->attributes();
            } else {
                        $attrsansstart = $meflowans->MAtomSetPoint[0]->attributes();
                        $attrsansfinish = $meflowans->MAtomSetPoint[1]->attributes();
            }

            $arrowans[$i] = $attrsansstart.$attrsansfinish;
            $arrowsansall .= "*".$arrowans[$i];
            $i = $i + 1;
        }

        if (!isset($arrowusr)) {
            $this->usecase = "You did not add any arrows.  Use the arrow icon on the left to add arrows next time!";
            return 0;
        }

        if (isset($cmlans->MDocument[0]->MEFlow['headFlags'])) {   // Must be radical reaction!
            // Check to see if usr submitted radical arrows.
            if (!isset($cmlusr->MDocument[0]->MEFlow['headFlags'])) { 
                $this->usecase = "This is a radical reaction but you used full arrow heads.<br>
                    You should use half arrow heads for radical reactions.  In radical reactions single electrons move.";
                return 0;
            }    
        } else {   // Must be polar reaction.
            if (isset($cmlusr->MDocument[0]->MEFlow['headFlags'])) { 
                $this->usecase = "This is a polar reaction but you used half arrow heads.<br>
                    You should use full arrow heads for polar reactions.  In polar reactions the electrons move in pairs.";
                return 0;
            }   
        }

        if ($orderimportant == 0) {

            if (array_count_values($arrowusr) == array_count_values($arrowans)) {
                return 1;
            } else {
                return 0;
            }
        }

        // Order important!
        if ($orderimportant == 1) {

            if ( $arrowusr == $arrowans ) {
                return 1;
            } else {
                // Check to see if it just that tyhe order is incorrect!
                if (array_count_values($arrowusr) == array_count_values($arrowans)) {
                    $this->usecase = "Although you had the correct arrows, you placed them in the wrong order.";
                    return false;
                }
                return 0;
            }
        }
    }

    public function get_expected_data() {
        return array('answer' => PARAM_RAW, 'easyomech' => PARAM_RAW, 'mol' => PARAM_RAW);
    }
}
