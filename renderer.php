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
 * easyomech question renderer class.
 *
 * @package    qtype
 * @subpackage easyomech
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

$generated_feedback = "";


/**
 * Generates the output for easyomech questions.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_easyomech_renderer extends qtype_renderer {
    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {
		global $CFG, $PAGE;
		
        $question = $qa->get_question();
	$order_important=$question->orderimportant;
	//echo "top of renderer order_important=".$order_important;
		
		$questiontext = $question->format_questiontext($qa);
        $placeholder = false;
	$myanswer_id = "my_answer".$qa->get_slot();
	$correctanswer_id = "correct_answer".$qa->get_slot();


        if (preg_match('/_____+/', $questiontext, $matches)) {
            $placeholder = $matches[0];
        }

	$name2 = 'EASYOMECH'.$qa->get_slot();
	$result='';


        if ($placeholder) {
            $toreplace = html_writer::tag('span',
                                      get_string('enablejavaandjavascript', 'qtype_easyomech'),
                                      array('class' => 'ablock'));
            $questiontext = substr_replace($questiontext,
                                            $toreplace,
                                            strpos($questiontext, $placeholder),
                                            strlen($placeholder));
        }

        $result .= html_writer::tag('div', $questiontext, array('class' => 'qtext'));




	if ($options->readonly) {
	$result .= html_writer::tag('input', '', array('type' => 'button','value' => 'My Response', 'onClick' => 'var s = document.getElementById("'.$myanswer_id.'").value; document.getElementById("'.$name2.'").setMol(s, "mrv");'));
	$result .= html_writer::tag('input', '', array('type' => 'button','value' => 'Correct Answer', 'onClick' => 'var s = document.getElementById("'.$correctanswer_id.'").value; document.getElementById("'.$name2.'").setMol(s, "mrv");'));


///if order important add button to show order of arrows
/*
 $jsmodule2 = array(
            'name'     => 'qtype_easyomech',
            'fullpath' => '/question/type/easyomech/module.js',
            'requires' => array(),
            'strings' => array(
                array('enablejava', 'qtype_easyomech')
            )
        );
*/



		if($order_important == 1){


		////show buttons for arrows order controls
		$result .= html_writer::tag('input', '', array('class' => 'arrowbutton','id' => 'showorder'.$qa->get_slot(), 'type' => 'button','value' => 'Forward'));

		$result .= html_writer::tag('input', '', array('class' => 'arrowbutton','id' => 'showorderrev'.$qa->get_slot(), 'type' => 'button','value' => 'Reverse'));

		//$result .= html_writer::tag(new moodle_url('/'), html_writer::empty_tag('img', array( 'src' => 'type/easyomech/pix/icon.png', 'alt' => 'LINK')), array('title' => get_string('home'),'id' => 'showorderrev'.$qa->get_slot()));

		$result .= html_writer::tag('input', '', array('id' => 'curarrow'.$qa->get_slot(), 'type' => 'hidden','value' => 0));


		$appletid = 'EASYOMECH'.$qa->get_slot();
		//echo $appletid;
		$this->page->requires->js_init_call('M.qtype_easyomech.init_showarrows', array($CFG->version, $qa->get_slot()));

		$this->page->requires->js_init_call('M.qtype_easyomech.init_showarrowsrev', array($CFG->version, $qa->get_slot()));

		}


		//$result .= html_writer::tag('BR', '', array());

	}






        $toreplaceid = 'applet'.$qa->get_slot();
        $toreplace = html_writer::tag('span',
                                      get_string('enablejavaandjavascript', 'qtype_easyomech'),
                                      array('id' => $toreplaceid));

/*
        if ($placeholder) {
            $toreplace = html_writer::tag('span',
                                      get_string('enablejavaandjavascript', 'qtype_easyomech'),
                                      array('class' => 'ablock'));
            $questiontext = substr_replace($questiontext,
                                            $toreplace,
                                            strpos($questiontext, $placeholder),
                                            strlen($placeholder));
        }

        $result .= html_writer::tag('div', $questiontext, array('class' => 'qtext'));
*/


        if (!$placeholder) {
            $answerlabel = html_writer::tag('span', get_string('answer', 'qtype_easyomech', ''),
                                            array('class' => 'answerlabel'));
            $result .= html_writer::tag('div', $answerlabel.$toreplace, array('class' => 'ablock'));


        }

        if ($qa->get_state() == question_state::$invalid) {
            $lastresponse = $this->get_last_response($qa);
            $result .= html_writer::nonempty_tag('div',
                                                $question->get_validation_error($lastresponse),
                                                array('class' => 'validationerror'));
 

       }

////crl add answer to page////// 

	if (!$options->readonly) {
                //echo "not readonly";
                
		$question = $qa->get_question();
		//$answer = $question->get_matching_answer($question->get_correct_response());
		
                //$answer_temp = $question->get_matching_answer($question->get_correct_response());

		//echo "quest". var_dump($question->get_correct_response());
		$answer_temp=$question->get_correct_response();
		//echo "newanswer".$new_answer['answer'];


		//echo "quest". $question->get_correct_response();


		if($question->hideproducts==0){
		$stripped_xml=$this->remove_xml_tags($answer_temp['answer'],'MEFlow');}
//		$stripped_xml=$this->remove_xmlattribute($answer->answer,'formalCharge');}
		else{
		$stripped_xml=$this->remove_xml_tags($answer_temp['answer'],'MEFlow');
		$stripped_xml=$this->remove_xml_tags($stripped_xml,'productList');
		}
		
//		$stripped_xml=$this->remove_xml_tags($answer->answer,'MEFlow');

//		$stripped_xml=addslashes($stripped_xml);

		$stripped_answer_id="stripped_answer".$qa->get_slot();
		$result .= html_writer::tag('textarea', $stripped_xml, array('id' => $stripped_answer_id, 'style' => 'display:none;', 'name' => $stripped_answer_id));
//		echo "renderer orderimportant=".$order_important;
	}
/////

		

		if ($options->readonly) {
                   // echo "readonly";
		    $currentanswer = $qa->get_last_qt_var('answer');

		$stripped_answer_id="stripped_answer".$qa->get_slot();
		$result .= html_writer::tag('textarea', $currentanswer, array('id' => $stripped_answer_id, 'style' => 'display:none;', 'name' => $stripped_answer_id));

	
		    
//$result .= html_writer::tag('div', get_string('youranswer', 'qtype_easyomech', s($qa->get_last_qt_var('answer'))), array('class' => 'qtext'));

//$answer = $question->get_matching_answer($question->get_correct_response());
$answer_temp=$question->get_correct_response();


///buttons to show correct and user answers

		$result .= html_writer::tag('textarea', $qa->get_last_qt_var('answer'), array('id' => $myanswer_id, 'name' => $myanswer_id, 'style' => 'display:none;'));

		$result .= html_writer::tag('textarea', $answer_temp['answer'], array('id' => $correctanswer_id, 'name' => $correctanswer_id, 'style' => 'display:none;'));


		}

	

        $result .= html_writer::tag('div',
                                    $this->hidden_fields($qa),
                                    array('class' => 'inputcontrol'));

        $this->require_js($toreplaceid, $qa, $options->readonly, $options->correctness, $CFG->qtype_easyomech_options);

        return $result;
    }






protected function remove_xml_tags($xmlstring, $tag){
	$dom = new DOMDocument();
	$dom->loadXML($xmlstring);
	$featuredde1 = $dom->getElementsByTagName($tag);
		$length=$featuredde1->length;
		for ($i = 0; $i < $length; $i++) {
		//echo "here";
	    $temp = $featuredde1->item(0); //avoid calling a function twice
	//    var_dump($temp);
	    $temp->parentNode->removeChild($temp);
	}
	//echo "<textarea>".$dom->saveXML()."</textarea>";
	return $dom->saveXML();

}




 protected function general_feedback(question_attempt $qa) {

	//global $generated_feedback;
        if (1 ==1){
	$question = $qa->get_question();
        return $question->usecase.$qa->get_question()->format_generalfeedback($qa); 
        }else{

       }
    }




    protected function require_js($toreplaceid, question_attempt $qa, $readonly, $correctness, $appletoptions) {
        global $PAGE;
        
        $jsmodule = array(
            'name'     => 'qtype_easyomech',
            'fullpath' => '/question/type/easyomech/module.js',
            'requires' => array(),
            'strings' => array(
                array('enablejava', 'qtype_easyomech')
            )
        );
        $topnode = 'div.que.easyomech#q'.$qa->get_slot();
        $appleturl = new moodle_url('appletlaunch.jar');
	$feedbackimage = '';
	
        if ($correctness) {
            $feedbackimage = $this->feedback_image($this->fraction_for_last_response($qa));
        } else {
            
        }

        $name = 'EASYOMECH'.$qa->get_slot();
        $appletid = 'easyomech'.$qa->get_slot();

	$stripped_answer_id="stripped_answer".$qa->get_slot();
	    
        $PAGE->requires->js_init_call('M.qtype_easyomech.insert_easyomech_applet',
                                      array($toreplaceid,
                                            $name,
                                            $appletid,
                                            $topnode,
                                            $appleturl->out(),
                                            $feedbackimage,
                                            $readonly,
                                            $appletoptions,
					    $stripped_answer_id),
                                      false,
                                      $jsmodule);

    }

    protected function fraction_for_last_response(question_attempt $qa) {
        $question = $qa->get_question();
        $lastresponse = $this->get_last_response($qa);
        $answer = $question->get_matching_answer($lastresponse);

        if ($answer) {
            $fraction = $answer->fraction;
        } else {
            $fraction = 0;
        }
        return $fraction;
    }


    protected function get_last_response(question_attempt $qa) {
        $question = $qa->get_question();
        $responsefields = array_keys($question->get_expected_data());
        $response = array();
        foreach ($responsefields as $responsefield) {
            $response[$responsefield] = $qa->get_last_qt_var($responsefield);
        }
        return $response;
    }

    public function specific_feedback(question_attempt $qa) {
        $question = $qa->get_question();

        $answer = $question->get_matching_answer($this->get_last_response($qa));
        if (!$answer) {
            return '';
        }

        $feedback = '';
        if ($answer->feedback) {
            $feedback .= $question->format_text($answer->feedback, $answer->feedbackformat,
                    $qa, 'question', 'answerfeedback', $answer->id);
        }
        return $feedback;
    }

    public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();

        $answer = $question->get_matching_answer($question->get_correct_response());
        if (!$answer) {
            return '';
        }

//        return get_string('correctansweris', 'qtype_easyomech', s($answer->answer));
//        return get_string('correctansweris', 'qtype_easyomech', s($answer->answer));


    }

    protected function hidden_fields(question_attempt $qa) {
        $question = $qa->get_question();

        $hiddenfieldshtml = '';
        $inputids = new stdClass();
        $responsefields = array_keys($question->get_expected_data());
        foreach ($responsefields as $responsefield) {
            $hiddenfieldshtml .= $this->hidden_field_for_qt_var($qa, $responsefield);
        }
        return $hiddenfieldshtml;
    }
    protected function hidden_field_for_qt_var(question_attempt $qa, $varname) {
        $value = $qa->get_last_qt_var($varname, '');
        $fieldname = $qa->get_qt_field_name($varname);
        $attributes = array('type' => 'hidden',
                            'id' => str_replace(':', '_', $fieldname),
                            'class' => $varname,
                            'name' => $fieldname,
                            'value' => $value);
        return html_writer::empty_tag('input', $attributes);
    }
}
