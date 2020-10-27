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

namespace local_expreport\task;

include_once dirname(dirname(dirname(__FILE__))).'/lib.php';
class report_task extends \core\task\scheduled_task {

	public function get_name() {
		return get_string('pluginname', 'local_expreport');
	}

	public function execute() {
		global $DB, $CFG, $PAGE, $SITE;
        //calling this function will send reports to user.
		$res = expreport_get_excelfile_url();
	}
}

