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
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $qa;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/shortanswer/question.php');

$generated_feedback="";

/**
 * Represents a easyomech question.
 *
 * @copyright  1999 onwards Martin Dougiamas {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_easyomech_question extends qtype_shortanswer_question {
	// all comparisons in easyomech are case sensitive
	public function compare_response_with_answer(array $response, question_answer $answer) {
        global $generated_feedback, $DB;


///var_dump($response);
///var_dump($answer);
$test = $DB->get_record('user', array('id'=>'1'));

$order_important = $this->orderimportant;

//echo "usecase".$this->usecase;

//echo "impotant=".$order_important;
//$question = $qa->get_question();
//	=$question->orderimportant;






if (!array_key_exists('answer', $response) || is_null($response['answer'])) {

            return false;
        }



//////////////strip arrows from mrv strings
$cmlans = new SimpleXMLElement($answer->answer);
$cmlusr = new SimpleXMLElement($response['answer']);
	$arrows_correct=0;
	$i=0;
	$arrowsusrall="";



if(isset($cmlusr->MDocument[0]->MEFlow['headFlags']) || isset($cmlans->MDocument[0]->MEFlow['headFlags'])){

//echo "usr=".var_dump((string)$cmlusr->MDocument[0]->MEFlow[0]->attributes()->headFlags);
//echo "ans=".var_dump((string)$cmlans->MDocument[0]->MEFlow[0]->attributes()->headFlags);

if((string)$cmlusr->MDocument[0]->MEFlow[0]->attributes()->headFlags != (string)$cmlans->MDocument[0]->MEFlow[0]->attributes()->headFlags){
	if((string)$cmlans->MDocument[0]->MEFlow[0]->attributes()->headFlags == '2'){
//	echo "radical reaction";
		$this->usecase = "This is a radical reaction but you used full arrow heads.<br> You should use half arrow heads for radical reactions.  In radical reactions single electrons move.";
		
	} 
        else{
		$this->usecase = "This is a polar reaction but you used half arrow heads.<br> You should use full arrow heads for polar reactions.  In polar reactions the electrons move in pairs.";
	}
	
		return 0;
}
}

	foreach ($cmlusr->MDocument[0]->MEFlow as $meflowusr) {


			$numbasepointsusr=$meflowusr->MEFlowBasePoint->count();
			$numsetpointsusr=$meflowusr->MAtomSetPoint->count();
//			echo "Num base points usr=".$numbasepointsusr."<br>";
//			echo "Num set points usr=".$numsetpointsusr."<br>";
		


			if($numbasepointsusr==1){
			$attrsusrstart=$meflowusr->MEFlowBasePoint[0]->attributes();
			$attrsusrfinish=$meflowusr->MAtomSetPoint[0]->attributes();
//			echo "start=".$attrsusrstart;
//			echo "fin=".$attrsusrfinish;

			}else{
			$attrsusrstart=$meflowusr->MAtomSetPoint[0]->attributes();
			$attrsusrfinish=$meflowusr->MAtomSetPoint[1]->attributes();
			}


			$arrowusr[$i]=$attrsusrstart.$attrsusrfinish;
//			echo "arrowusr=".$arrowusr[$i]."<br>";
 			$arrowsusrall.="*".$arrowusr[$i];
			$i=$i+1;
			$numbasepointsans=$cmlans->MDocument[0]->MEFlow->MEFlowBasePoint->count();
                       

	}

//      		echo "arrowusrall=".$arrowsusrall."<br>";
			$i=0;
			$arrowsansall="";
	foreach ($cmlans->MDocument[0]->MEFlow as $meflowans) {

			$numbasepointsans=$meflowans->MEFlowBasePoint->count();
			$numsetpointsans=$meflowans->MAtomSetPoint->count();

//			echo "Num base points ans=".$numbasepointsans."<br>";
//			echo "Num set points ans=".$numsetpointsans."<br>";

			if($numbasepointsans==1){
//			$attrsusrstart=$meflowusr->MAtomSetPoint[0]->attributes();
			$attrsansstart=$meflowans->MEFlowBasePoint[0]->attributes();
			$attrsansfinish=$meflowans->MAtomSetPoint[0]->attributes();
			}else{
			$attrsansstart=$meflowans->MAtomSetPoint[0]->attributes();
			$attrsansfinish=$meflowans->MAtomSetPoint[1]->attributes();
			}

			$arrowans[$i]=$attrsansstart.$attrsansfinish;
			$arrowsansall.="*".$arrowans[$i];
//			echo "arrowans=".$arrowans[$i]."<br>";
			$i=$i+1;
			


	}

 //     		echo "arrowansall=".$arrowsansall."<br>";





	if(!isset($arrowusr)){
	$this->usecase = "You did not add any arrows.  Use the arrow icon on the left to add arrows next time!";
	//$generated_feedback="You did not add any arrows.  Use the arrow icon on the left to add arrows next time!";
	return 0;
        
	}


        ////order not important
	if ($order_important==0){
/*
echo "<br>usr";
//var_dump($arrowusr);
echo "<br>usr";
var_dump(array_count_values($arrowusr));
echo "<br>ans";
//var_dump($arrowans);
echo "<br>ans";
var_dump(array_count_values($arrowans));
echo "<br>";
*/
///		echo "comparison=". array_count_values($arrowusr) == array_count_values($arrowans);

		if (array_count_values($arrowusr) == array_count_values($arrowans)){
//		echo 'order not important - returned 1';
		return 1;
		}else{
//		echo 'order not important - returned 0';
		return 0;
		}
	}

        ///order important
	if ($order_important==1){
/*
echo "order important=".$order_important;
echo "<br>usr";
//var_dump($arrowusr);
echo "<br>usr";
var_dump(array_count_values($arrowusr));
echo "<br>ans";
//var_dump($arrowans);
echo "<br>ans";
var_dump(array_count_values($arrowans));
echo "<br>";
*/




		if ( $arrowusr == $arrowans ) {
//	    	echo 'order important - returned 1';
		return 1;
		}
		else{
                ///check to see if it just that tyhe order is incorrect
		
//			echo "array count array_count_values($arrowusr) == array_count_values($arrowans)";
			if (array_count_values($arrowusr) == array_count_values($arrowans)){
                        //echo "order important (Incorrect arrows order)";
			$this->usecase ="Although you had the correct arrows, you placed them in the wrong order.";
			//echo 'order important - returned 0';
			//return 0;
//				echo 'order important - returned false';
				return false;		
			}
//		echo "order important - returned zero at end";
		return 0;	
		}
	}



    }
	
	public function get_expected_data() {

        return array('answer' => PARAM_RAW, 'easyomech' => PARAM_RAW, 'mol' => PARAM_RAW);
    }
}
