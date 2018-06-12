<?php
/**
 * This library is useful for counting the number of times a clock chimes within a given range
 * Created by PhpStorm.
 * User: Fredrick Nsiah
 * Date: 6/12/2018
 * Time: 2:02 PM
 */

class BellCounter
{
    /**
     * Function to validate time input
     * @param string $time
     * @return false|int
     */
    private function validateTime(string $time) {
        return preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", $time);
    }

    /**
     * This function validates the time range to ensure its chime friendliness
     * @param string $start
     * @param string $end
     * @return bool
     */
    private function validateTimeRange(string $start, string $end){
        $startTime = explode(":", $start);
        $endTime = explode(":", $end);
        if($startTime[1] !== '00' && $endTime[1] !== '00' && ($startTime[0] == $endTime[0]) && (($endTime[1] - $startTime[1]) > 0)) {
            return false;
        }
        return true;
    }

    /**
     * Function to convert 24 hour time to 12-hour time and returns only the hour, used to count the number of chimes
     * @param $time
     * @return false|string
     */
    private function getHourCount($time) {
        return date('g', strtotime($time));
    }

    /**
     * Function to format start time. If time is not exactly on the hour, it adds an hour to the time and set minute to 00
     * @param $time
     * @return string
     */
    private function formatStartTime($time){
        $startTime = explode(":", $time);

        if(isset($startTime[0], $startTime[1]) && $startTime[1] !== '00') {
            $startTime[0] = ((int) $startTime[0] + 1) % 24;
            $startTimeFormatted = sprintf('%02d', $startTime[0]) . ':' . '00';
            return $startTimeFormatted;
        }
        return $time;

    }

    /**
     * Function to format end time. If time is not exactly on the hour, it sets the minute to 00
     * @param $time
     * @return string
     */
    private function formatEndTime ($time) {
        $endTime = explode(":", $time);

        if(isset($endTime[0], $endTime[1]) && $endTime[1] !== '00') {
            $endTimeFormatted = $endTime[0] . ':' . '00';
            return $endTimeFormatted;
        }
        return $time;
    }

    /**
     * Loop counter function to count chimes
     * @param int $start
     * @param int $end
     * @return int
     */
    public function counter (int $start, int $end) {
        $chimeCount = 0;

        while (true) {
            if($start % 24 == 0) {
                $start = 0;
            }
            $chimeCount += $this->getHourCount(sprintf('%02d', $start) . ':' . '00');

            if($start == $end) {
                break;
            }
            $start++;
        }
        return $chimeCount;
    }

    /**
     * Function to count number of chimes within a time range
     * @param string $start
     * @param string $end
     * @return int
     * @throws Exception
     */
    public function countBells(string $start, string $end) {

        if(!$this->validateTime($start) || !$this->validateTime($end)) {
            throw new Exception('Time format is incorrect.');
        }

        if(!$this->validateTimeRange($start, $end)) {
            throw new Exception('No hour in range, chime failed');
        }
        $formattedStart = $this->formatStartTime($start);
        $formattedEnd = $this->formatEndTime($end);

        $startTime = explode(":", $formattedStart);
        $startHour = (int) $startTime[0];
        $endTime = explode(":", $formattedEnd);
        $endHour = (int) $endTime[0];

        return $this->counter($startHour, $endHour);
    }
}