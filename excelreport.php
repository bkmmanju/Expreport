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
require_once("$CFG->libdir/excellib.class.php");
//getting the users array from the url. 
$data = required_param('data',PARAM_RAW);
//getting the name of the report.
$name = required_param('name',PARAM_RAW);
//decoding the json data into array.
$content = json_decode($data);

if(!empty($content)){
	$config = get_config('expreport');
	$allcourses = $config->courseids;
	$allcourseids = explode(",", $allcourses);
	$userfields = $config->profilefields;
	$exploadvalus = explode(",", $userfields);
	$header = expreport_header_data();
	$tabledata = expreport_table_data($content);
	// Make temporary file
	$filename = $CFG->tempdir.'/report_file.csv';
	$file = fopen($filename, 'w+');
	foreach ($tabledata as $fields) {
		fputcsv($file, (array)$fields);
	}

	//adding timestamp to reportname.
	$currenttime =  date("dmYhis",time());
	$finalfilename=$name.'_'.$currenttime.'.xls';
	
	fclose($file);
	$fs = get_file_storage();
	$systemcontext = context_system::instance();
	$file_record = new stdClass;
	$file_record->component = 'local_expreport'; 
	$file_record->contextid =  context_system::instance()->id;
	$file_record->filearea  = 'excelreport';
	$file_record->filename = $finalfilename;             
	$file_record->filepath  = '/';      
	$file_record->itemid    = 0;
	$existingfile = $fs->file_exists($file_record->contextid, $file_record->component, $file_record->filearea,
		$file_record->itemid, $file_record->filepath, $file_record->filename);
	if ($existingfile) {
        //throw new file_exception('filenameexist');
	} else {
		$stored_file = $fs->create_file_from_pathname($file_record, $filename);
		//when the file id added to the moodledata create a new record in local_expreport table.
		$insert = new stdClass;
		$insert->filename = $file_record->filename;
		$insert->timecreated = time();
		$insert->mailsent = 0;
		$result = $DB->insert_record('local_expreport',$insert);
		if($result){
			echo "inserted";
		}
	}
}
