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
// Used to stay DRY with the get_string function call.
$componentname = 'local_expreport';
$ADMIN->add('localplugins', new \admin_category('local_expreport', get_string('title', $componentname)));
//Add the 'create report' page to the nav tree.
$ADMIN->add(
	'local_expreport',
	new \admin_externalpage(
		'expreportsetting',
		get_string('createreport', $componentname),
		new moodle_url('/local/expreport/index.php')
	));