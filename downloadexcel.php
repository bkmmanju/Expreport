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
 * @copyright  Dhruv Infoline Pvt Ltd <lmsofindia.com>
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
	$filename = $name.'.xls';
// Creating a workbook.
	$workbook = new \MoodleExcelWorkbook("-");
// Sending HTTP headers.
	$workbook->send($filename);
// Creating the first worksheet.
	$sheettitle = get_string('report', 'scorm');
	$myxls = $workbook->add_worksheet($sheettitle);
    // Format types.
	$format = $workbook->add_format();
	$format->set_bold(0);
	$formatbc = $workbook->add_format();
	$formatbc->set_bold(1);
	$formatbc->set_align('center');
	$formatb = $workbook->add_format();
	$formatb->set_bold(1);
	$formaty = $workbook->add_format();
	$formaty->set_bg_color('yellow');
	$formatc = $workbook->add_format();
	$formatc->set_align('center');
	$formatr = $workbook->add_format();
	$formatr->set_bold(1);
	$formatr->set_color('red');
	$formatr->set_align('center');
	$formatg = $workbook->add_format();
	$formatg->set_bold(1);
	$formatg->set_color('green');
	$formatg->set_align('center');
	$colnum = 0;
	foreach ($header as $item) {
		$myxls->write(0, $colnum, $item, $formatbc);
		$colnum++;
	}
	$rownum = 1;
    // Generate the data for the body of the spreadsheet.
	$row = 1;
	foreach($tabledata as $key => $tdataarray) {
		$i = 0;
		if($key > 0){
			foreach ($tdataarray as $tdata) {
				$myxls->write_string($row, $i++, $tdata);
			}
			$row++;
		}
	}
	$workbook->close();
	exit;
}
