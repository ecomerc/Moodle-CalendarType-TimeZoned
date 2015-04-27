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

namespace calendartype_gregoriantmz;
use calendartype_gregorian\structure as type_base;

//use core_calendar\type_base;
/**
 * Handles calendar functions for the gregorian calendar.
 *
 * @package calendartype_gregoriantmz
 * @copyright 2008 onwards Foodle Group {@link http://foodle.org}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class structure extends type_base {

    /**
     * Returns the name of the calendar.
     *
     * This is the non-translated name, usually just
     * the name of the folder.
     *
     * @return string the calendar name
     */
    public function get_name() {
        return 'gregoriantmz';
    }


    /**
     * Returns a formatted string that represents a date in user time.
     *
     * Returns a formatted string that represents a date in user time
     * <b>WARNING: note that the format is for strftime(), not date().</b>
     * Because of a bug in most Windows time libraries, we can't use
     * the nicer %e, so we have to use %d which has leading zeroes.
     * A lot of the fuss in the function is just getting rid of these leading
     * zeroes as efficiently as possible.
     *
     * If parameter fixday = true (default), then take off leading
     * zero from %d, else maintain it.
     *
     * @param int $time the timestamp in UTC, as obtained from the database
     * @param string $format strftime format
     * @param int|float|string $timezone the timezone to use
     *        {@link http://docs.moodle.org/dev/Time_API#Timezone}
     * @param bool $fixday if true then the leading zero from %d is removed,
     *        if false then the leading zero is maintained
     * @param bool $fixhour if true then the leading zero from %I is removed,
     *        if false then the leading zero is maintained
     * @return string the formatted date/time
     */
    public function timestamp_to_date_string($time, $format, $timezone, $fixday, $fixhour) {
		$tmz = get_user_timezone($timezone);	
        $timezoneoffset = get_user_timezone_offset($timezone);	
		$tmzrecord = get_timezone_record($tmz);	
	
		$output_tmz = "GMT" . ($timezoneoffset > 0 ? "+" : "") . $timezoneoffset;
		if (!empty($tmzrecord)) {			
			$output_tmz = "<abbr title='".$tmzrecord->name."'>" . $output_tmz . "</abbr>";
		}
	
        $formatnotimezone = str_replace('%Z', 'TMZ', $format);
		$output = parent::timestamp_to_date_string($time, $formatnotimezone, $timezone, $fixday, $fixhour);
        $output = str_replace('TMZ', $output_tmz, $output);
        return $output;
    }

}
