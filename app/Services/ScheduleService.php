<?php

namespace App\Services;

use Carbon\Carbon;

class ScheduleService
{
    /**
     * Construct guard schedules based on the given schedule timeline.
     * Plots the guard available schedule time frames for all the displayed dates.
     *
     * @param $guards
     * @param $dates
     * @param $dailyTimeFrames
     * @param $dateSecurityChecker
     * @return array
     */
    public function getGuardScheduleTimeline($guards, $dates, $dailyTimeFrames, $dateSecurityChecker)
    {
        $guardSchedules = [];
        foreach ($guards as $key => $guard) {
            $guardSchedules[$key]['name'] = $guard->name;
            $guardSchedules[$key]['color_indicator'] = $guard->color_indicator;
            $guardSchedules[$key]['schedules'] = [];

            foreach ($dates as $dateKey => $day) {
                $schedule = $guard->schedules()->where('date', $day)->first();
                // Set the guard time frames display.
                if ($schedule) {
                    $dateSecurityChecker[$dateKey] = true;
                    $newTimeFrames = $dailyTimeFrames;
                    $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
                    $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);

                    // Add a color to every time frame as indicator of the schedule duration.
                    foreach ($dailyTimeFrames as $frameKey => $timeFrame) {
                        $currentTime = Carbon::createFromFormat('H:i', $timeFrame);
                        // Check if the time frame is between the guard daily schedule.
                        if ($currentTime->between($startTime, $endTime, false) ||
                            $currentTime->eq($startTime)
                        ) {
                            $newTimeFrames[$frameKey] = true;
                        }
                    }
                    $guardSchedules[$key]['schedules'] = array_merge($guardSchedules[$key]['schedules'], $newTimeFrames);
                } else {
                    $guardSchedules[$key]['schedules'] = array_merge($guardSchedules[$key]['schedules'], $dailyTimeFrames);
                }
            }
        }

        return [
            $guardSchedules,
            $dateSecurityChecker,
        ];
    }

    /**
     * Initialize schedule timeline to be displayed
     * based on the number of days and minutes interval.
     *
     * @param $noOfDays
     * @param $minutesInterval
     * @return array
     */
    public function initializeScheduleTimeline($noOfDays, $minutesInterval)
    {
        $startDay = Carbon::tomorrow();
        $startTime = $startDay->copy();
        $endDay = $startDay->copy()->addDay($noOfDays);
        $nextDay = $startDay->copy()->addDay(1);

        $dailyTimeFrames = [];
        // Set the daily time frames based on the interval.
        for ($time = $startTime; $time->lt($nextDay); $time->addMinute($minutesInterval)) {
            $dailyTimeFrames[] = $time->format('H:i');
        };

        $dates = [];
        $totalTimeFrames = [];
        $dateSecurityChecker = [];
        for ($day = $startDay; $day->lt($endDay); $day->addDay(1)) {
            // List the number of schedule dates to be displayed.
            $dates[] = $day->toDateString();
            // List the time frames to be displayed for all the dates.
            $totalTimeFrames = array_merge($totalTimeFrames, $dailyTimeFrames);
            // List the availability of security guards for all the dates
            $dateSecurityChecker[] = false;
        }

        return [
            $dates,
            $totalTimeFrames,
            $dailyTimeFrames,
            $dateSecurityChecker,
        ];
    }

}