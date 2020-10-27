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

defined('MOODLE_INTERNAL') || die();

function xmldb_local_expreport_upgrade($oldversion) {
   global $CFG,$DB;

   $dbman = $DB->get_manager();
   if ($oldversion < 2020101402) {
    $table = new xmldb_table('local_expreport');
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, 
        XMLDB_NOTNULL, XMLDB_SEQUENCE, null); 
    $table->add_field('filename', XMLDB_TYPE_CHAR, '250',
        null, null,null, null, null);
    $table->add_field('timecreated', XMLDB_TYPE_CHAR, '250',
        null, null,null, null, null);
    $table->add_field('mailsent', XMLDB_TYPE_INTEGER, '10',
        null, null,null, null, null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    upgrade_plugin_savepoint(true, 2020101402,'local', 'expreport');
}

if ($oldversion < 2020101405) {

    $table = new xmldb_table('local_expreport');
    $field = new xmldb_field('mailsenttime', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0', 'mailsent');

    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    upgrade_plugin_savepoint(true, 2020101405, 'local', 'expreport');
}
return true;
}
