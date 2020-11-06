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

defined('MOODLE_INTERNAL') || die;
global $DB;
//version 2 changes: Create report page setting added seperately.
require('navigation_setting.php');
if ($hassiteconfig) {

	$moderator = get_admin();
	$site = get_site();

	$settings = new admin_settingpage('expreport', get_string('exportreportsettings','local_expreport'));
	$ADMIN->add('localplugins', $settings);
	//Heading for the export report setting.
	$name = 'expreport/expreportsetting';
	$information = get_string('exportreportsettings', 'local_expreport');
	$heading = get_string('exportreportsettings', 'local_expreport');
	$setting = new admin_setting_heading($name,$heading, $information);
	$settings->add($setting);

	//getting all the user profile fields from the db.
	$profilefields = $DB->get_records('user_info_field',array('visible'=>2));
	$profilestring = '';
	foreach ($profilefields as $field) {
		$profilestring = $profilestring.', '.$field->shortname;
	}

	//user fields and user custom profile fields separated by comma which will be considered for report generation.
	$name = 'expreport/profilefields';
	$title = get_string('enterprofilefields', 'local_expreport');
	$description = get_string('enterprofilefieldsdescription', 'local_expreport',$profilestring);
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);    
	$settings->add($setting);

	//Course id's seperated by comma which will be considered for report generation.
	$name = 'expreport/courseids';
	$title = get_string('entercourseids', 'local_expreport');
	$description = get_string('entercourseidsdescription', 'local_expreport');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);    
	$settings->add($setting);

	//Email address to which the report will be sent.
	$name = 'expreport/emailid';
	$title = get_string('enteremailid', 'local_expreport');
	$description = get_string('enteremailiddescription', 'local_expreport');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, $default);    
	$settings->add($setting);

	//Email subject will be added in the report mail.
	$name = 'expreport/emailsubject';
	$title = get_string('enteremailsubject', 'local_expreport');
	$description = get_string('enteremailsubjectdescription', 'local_expreport');
	$default = get_string('defaultmailsubject','local_expreport');
	$setting = new admin_setting_configtext($name, $title, $description, $default);    
	$settings->add($setting);

	//Email body will be added in the report mail.
	$name = 'expreport/emailbody';
	$title = get_string('enteremailbody', 'local_expreport');
	$description = get_string('enteremailbodydescription', 'local_expreport');
	$default = get_string('defaultmailbody','local_expreport');
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);    
	$settings->add($setting);
}