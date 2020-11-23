# Expreport
Export Report - Moodle local plugin
====================

This plugin is used to create report using custom profile field of user and course selection. Once the report is created if the number of rows are more than 200,000 then the excel will be sent via mail. Otherwise the report can be downloaded directly.
------------

Add the plugin to /local/expreport/

Run the Moodle upgrade.

# Configuration

Upon first installation you will see a notification across the screen that has not been set. There is a convenient link in the bar to:

Site administration > Plugins -> Local plugins -> Export Report Settings

Fill all the settings needed for report generation. On installation this plugin will create a new table in the database called 'local_expreport'. 
