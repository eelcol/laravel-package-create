<?php 

/*
* Part of package: EelcoLuurtsema/LaravelPackageCreate
* Author: Eelco Luurtsema
*/

namespace #NAMESPACE#;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;

/**
* Extends the Schedule class with a random function
*/
class ExtendedSchedule extends Schedule
{
	/**
	* Add a command to run daily at random time
	*/
	public function commandAtRandomTime($command, $startTime=0, $endTime=23)
	{
		$backup_time = \Cache::get('scheduled:time:' . $command);
        if (!$backup_time) {
            $randomHour     = random_int($startTime, $endTime);
            $randomMinute   = random_int(0, 59);

            $backup_time    = [$randomHour, $randomMinute];

            \Cache::put('scheduled:time:' . $command, $backup_time, Carbon::now()->endOfDay());
        }

        $minute     = str_pad($backup_time[1], 2, "0", STR_PAD_LEFT);
        $hour       = str_pad($backup_time[0], 2, "0", STR_PAD_LEFT);
        $hourMin1   = str_pad($backup_time[0], 2, "0", STR_PAD_LEFT);
        $hourPlus1  = str_pad($backup_time[0], 2, "0", STR_PAD_LEFT);

        return $this->command($command)->daily()->at( $hourMin1 . ":" . $minute );
	}

	/**
	* Add a command to run daily at random time, between x and y
	*/
	public function commandAtRandomTimeBetween($command, $startTime, $endTime)
	{
		return $this->scheduleCommandAtRandomTime($command, $startTime, $endTime);
	}
}