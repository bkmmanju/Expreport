<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Handles uploading files
 *
 * @package    local_expreport
 * @copyright  Manjunath B K<manjunathbk@elearn10.com>
 * @copyright  Edzlearn Services Pvt Limited <lmsofindia.com>
 * @license    http://www.lmsofindia.com 2017 or later
 */

require_once('../../config.php');
global $DB,$CFG;
require_once("$CFG->libdir/formslib.php");

class report_form extends moodleform {
	public function definition(){
		global $CFG,$DB;
		$mform = $this->_form;
		// Don't forget the underscore!
		$mform->addElement('text','reportfilename', get_string('filename','local_expreport'));
		$mform->setType('reportfilename', PARAM_RAW);
		$mform->addRule('reportfilename', get_string('filename','local_expreport'), 'required', 'extraruledata', 'client', false, false);

		$config = get_config('expreport');
		$userfields = $config->profilefields;
		$exploadvalus = explode(",", $userfields);
		foreach ($exploadvalus as $value) {
			$fieldvalues = $DB->get_record('user_info_field',array('shortname'=>$value));

			//Creating text fields if exists.
			if(!empty($fieldvalues)){
				if($fieldvalues->datatype =='text'){
					$mform->addElement('text',"text_".$fieldvalues->id.'_'.$fieldvalues->shortname, $fieldvalues->name);
					$mform->setType("text_".$fieldvalues->id.'_'.$fieldvalues->shortname, PARAM_RAW);
				}
			}
			//Creating text area fields.
			if(!empty($fieldvalues)){
				if($fieldvalues->datatype =='textarea'){
					$mform->addElement('editor', "textarea_".$fieldvalues->id."_".$fieldvalues->shortname, $fieldvalues->name, 'wrap="virtual" rows="10" cols="80"');
					$mform->setType("textarea_".$fieldvalues->id."_".$fieldvalues->shortname, PARAM_RAW);
				}
			}
			//Creating dropdown if exists.
			if(!empty($fieldvalues)){
				if($fieldvalues->datatype =='menu'){
					$selectvalues = explode("\n", $fieldvalues->param1);
					foreach ($selectvalues as $dropdownvalues) {
						$dropdownarray[$dropdownvalues]=$dropdownvalues;
					}
					$mform->addElement('select', "menu_".$fieldvalues->id."_".$fieldvalues->shortname, $fieldvalues->name, $dropdownarray);
					$mform->setType("menu_".$fieldvalues->id."_".$fieldvalues->shortname, PARAM_RAW);
				}
			}
			//Creating date selector if exists.
			if(!empty($fieldvalues)){
				$date_options = array(
					'startyear' => 2010, 
					'stopyear'  => 2050,
					'timezone'  => 99,
					'optional'  => false
				);
				if($fieldvalues->datatype =='datetime'){
					$mform->addElement('date_time_selector', "datetime_".$fieldvalues->id."_".$fieldvalues->shortname, $fieldvalues->name,$date_options);
					$mform->setType("datetime_".$fieldvalues->id."_".$fieldvalues->shortname, PARAM_RAW);
				}
			}

			//Creating checkbox if exists.
			if(!empty($fieldvalues)){
				if($fieldvalues->datatype =='checkbox'){
					$mform->addElement('advcheckbox', "checkbox_".$fieldvalues->id."_".$fieldvalues->shortname, $fieldvalues->name, $fieldvalues->name, '', array(0, 1));
					$mform->setType($fieldvalues->shortname, PARAM_RAW);
				}
			}

		}
		//action buttons start here//
		$buttonarray = array();
		$buttonarray[] = $mform->createElement('submit','submitbutton',get_string('submit','local_expreport'));
		$buttonarray[] = $mform->createElement('cancel');

		$mform->addGroup($buttonarray,'buttonarray','','',false);
	}
	//Custom validation should be added here
	function validation($data, $files) {
		return array();
	}
}