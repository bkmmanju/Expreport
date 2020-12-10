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
require_once('lib.php');
require_once('form/report_form.php');
global $DB, $USER, $SESSION;
require_login(true);
$context = context_system::instance();

$createreport = has_capability('local/expreport:createreport',$context);
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url($CFG->wwwroot . '/local/expreport/index.php');
$title = get_string('title','local_expreport');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/expreport/js/custom.js'));
//Instantiate report_form.
$mform = new report_form();
$heading="";
$heading.=html_writer::start_div('container-fluid');
$heading.=html_writer::start_div('row');
$heading.=html_writer::start_div('col-md-12 p-4');
$heading.=html_writer::start_tag('h2');
$heading.=get_string('reporttitle','local_expreport');
$heading.=html_writer::end_tag('h2');
$heading.=html_writer::end_div();
$heading.=html_writer::end_div();
$heading.=html_writer::end_div();

$html='';
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
	$cancelurl = $CFG->wwwroot.'/my';
	redirect($cancelurl);
} else if ($fromform = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.
	//Getting all the users matched the report form criteria.
	$allusers = expreport_users_matched_filter_criteria($fromform);
	if(!empty($allusers)){
		//count the number of rows will be added to the report.
		$datacount = count(expreport_table_data($allusers));
		//getting html for table display.
		$reporttable = expreport_report_table($allusers);

		$html.=html_writer::start_div('container-fluid');
		$html.=html_writer::start_div('row');
		$html.=html_writer::start_div('col-md-12');
		$html.=html_writer::start_tag('h2');
		$html.=get_string('reporttitle','local_expreport');
		$html.=html_writer::end_tag('h2');
		$html.=html_writer::end_div();
		$html.=html_writer::end_div();


		$html.=html_writer::start_div('row');
		$html.=html_writer::start_div('form-group');
		$html.=html_writer::start_tag('select',array('class'=>'form-control','name'=>'state','id'=>'maxRows'));
		$html.=html_writer::start_tag('option',array('value'=>'5000'));
		$html.=get_string('showallrows','local_expreport');
		$html.=html_writer::end_tag('option');
		$optioarray=array(5,10,15,20,50,70,100);
		foreach ($optioarray as $optionval) {
			$html.=html_writer::start_tag('option',array('value'=>$optionval));
			$html.=$optionval;
			$html.=html_writer::end_tag('option');
		}
		$html.=html_writer::end_tag('select');
		$html.=html_writer::end_div();
		$html.=html_writer::start_div('table-responsive');
		$html.=html_writer::start_div('col-md-12');
		$html.=$reporttable;
		$html.=html_writer::end_div();
		$html.=html_writer::end_div();
		$html.=html_writer::start_div('pagination-container');
		$html.=html_writer::start_tag('nav');
		$html.=html_writer::start_tag('ul',array('class'=>'pagination'));
		$html.=html_writer::start_tag('li',array('data-page'=>'prev'));
		$html.=html_writer::start_tag('span');
		$html.=get_string('preview','local_expreport');
		$html.=html_writer::start_tag('span',array('class'=>'sr-only'));
		$html.=get_string('current','local_expreport');
		$html.=html_writer::end_tag('span');
		$html.=html_writer::end_tag('span');
		$html.=html_writer::end_tag('li');
		$html.=html_writer::start_tag('li',array('data-page'=>'next','id'=>'prev'));
		$html.=html_writer::start_tag('span');
		$html.=get_string('next','local_expreport');
		$html.=html_writer::start_tag('span',array('class'=>'sr-only'));
		$html.=get_string('current','local_expreport');
		$html.=html_writer::end_tag('span');
		$html.=html_writer::end_tag('span');
		$html.=html_writer::end_tag('li');
		$html.=html_writer::end_tag('ul');
		$html.=html_writer::end_tag('nav');
		$html.=html_writer::end_div();
		$html.=html_writer::end_div();
		//creating link for direct download excel if the count is less than 200000.
		$downloadlink=$CFG->wwwroot.'/local/expreport/downloadexcel.php';
		//checking the count of rows needs to be added to report table. if the count is less than 2 lack then the user will get direct download excel button.
		if($datacount < 200000){
			$sendvalue = json_encode($allusers);
			$html.=html_writer::start_div('row');
			$html.=html_writer::start_div('col-md-12 text-center');
			$html.=html_writer::start_tag('form',array('action'=>$downloadlink,'method'=>'post'));
			$html.=html_writer::start_tag('input',array('type'=>'hidden','value'=>$sendvalue,'name'=>'data'));
			$html.=html_writer::start_tag('input',array('type'=>'hidden','value'=>$fromform->reportfilename,'name'=>'name'));
			$html.=html_writer::start_tag('button',array('type'=>'submit','class'=>'btn btn-info'));
			$html.=get_string('downloadinexcel','local_expreport');
			$html.=html_writer::end_tag('button');
			$html.=html_writer::end_tag('form');
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();

		}else{
			//if the record is more than 2 lack display the notice regarding that.
			$html.=html_writer::start_div('row');
			$html.=html_writer::start_div('col-md-12 text-center');
			$html.=html_writer::start_div('p-5 bg-warning');
			$html.=get_string('morerecords','local_expreport');
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();
			$html.=html_writer::end_div();
			//calling curl here.
			$url = $CFG->wwwroot.'/local/expreport/excelreport.php?data='.json_encode($allusers).'&name='.$fromform->reportfilename;
			$remoteurl = $url;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $remoteurl); 
			//Remote Location URL
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE); 
			// making it
			$output = curl_exec($ch);
			curl_error($ch);
			curl_close($ch);
		}
		$html.=html_writer::end_div();
	}else{
		//version 2: added error message if no data found.
		$html.=html_writer::div(
			get_string('norecordsfound', 'local_expreport'),'alert alert-danger'
		);
	}
}
echo $OUTPUT->header();
//checking all the settings value if any of the valuse are empty then showing error message here.
if($createreport){
	$config = get_config('expreport');
	$userfields = $config->profilefields;
	if($userfields == ""){
		echo html_writer::div(
			get_string('userprofilefieldserror', 'local_expreport'),'alert alert-danger'
		);
	}
	if($config->courseids == ""){
		echo html_writer::div(
			get_string('coursefieldserror', 'local_expreport'),'alert alert-danger'
		);
	}
	if($config->emailid ==""){
		echo html_writer::div(
			get_string('emailiderror', 'local_expreport'),'alert alert-danger'
		);
	}
	echo $heading;
	//displays the form
	$mform->display();
//display the report table.
	echo $html;
}else{
	//if the logged in user dont have the capability to view this page then showing error.
	echo html_writer::div(
		get_string('cap', 'local_expreport'),'alert alert-danger'
	);
}
echo $OUTPUT->footer();