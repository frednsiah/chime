<?php
/**
 * This library is useful for counting the number of times a clock chimes within a given range
 * Created by PhpStorm.
 * User: Fredrick Nsiah
 * Date: 6/12/2018
 * Time: 2:02 PM
 */
require __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

class BellCounter
{

    private $carbonStart;

    private $carbonEnd;

    public function __construct() {
        date_default_timezone_set('UTC');
    }
    
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

     * @return bool
     */
    private function validateTimeRange(){
        if($this->carbonStart->copy()->startOfHour() != $this->carbonStart && $this->carbonEnd !== $this->carbonEnd->copy()->startOfHour() && ($this->carbonStart->diffInHours($this->carbonEnd) == 0) && ( $this->carbonStart->diffInMinutes($this->carbonEnd, false) > 0)) {
            return false;
        }
        return true;
    }

    /**
     * Function to convert 24 hour time to 12-hour time and returns only the hour, used to count the number of chimes

     * @return false|string
     */
    private function getHourCount($time) {
        return $time->format('g');
    }

    /**
     * Function to format start time. If time is not exactly on the hour, it adds an hour to the time and set minute to 00
     * @return string
     */
    private function formatStartTime(){
        if($this->carbonStart != $this->carbonStart->copy()->startOfHour()) {
            return $this->carbonStart->copy()->startofHour()->addHour();
        }
        return $this->carbonStart;
    }

    /**
     * Function to format end time. If time is not exactly on the hour, it sets the minute to 00
     * @return string
     */
    private function formatEndTime () {
        if($this->carbonEnd != $this->carbonEnd->copy()->startOfHour()) {
            return $this->carbonEnd->copy()->startofHour();
        }
        return $this->carbonEnd;
    }

    /**
     * Loop counter function to count chimes
     * @param int $start
     * @param int $end
     * @return int
     */
    public function counter (int $start, int $end, int $chimeCount = 0) {

        while (true) {
            if($start % 24 == 0) {
                $start = 0;
            }
            $startCarbon = Carbon::now();
            $startCarbon->hour = $start;
            $chimeCount += $this->getHourCount($startCarbon);

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

        $this->carbonStart = Carbon::createFromFormat('G:i', $start);
        $this->carbonEnd = Carbon::createFromFormat('G:i', $end);

        if(!$this->validateTimeRange()) {
            return 0;
        }

        $formattedStart = $this->formatStartTime();
        $formattedEnd = $this->formatEndTime();

        $start = (int) $formattedStart->format('G');
 
        $end = (int) $formattedEnd->format('G');

        if($this->carbonStart == $this->carbonEnd) {
            $chimeCount = (int) $this->formatStartTime()->format('g');
            //return $chimeCount;
            return $this->counter($start + 1, $end,  $chimeCount);
        }

        return $this->counter($start, $end);
    }
}