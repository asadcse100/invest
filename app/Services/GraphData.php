<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GraphData
{
    private $dateFormat;
    private $dateColumn;
    private $resultColumn;
    private $timeData = [];

    public function set($for, $using, $format = 'd M', $type = 'amount')
    {
        $this->dateFormat = $format;
        $this->dateColumn = $using;
        $this->resultColumn = $for;
        $this->type = $type;
    }

    private function generate(Carbon $start, $end)
    {
        $this->timeData = [];
        array_map(function ($time) {
            $this->timeData[$time->format($this->dateFormat)] = 0;
        }, (new CarbonPeriod($start, '1 days', $end))->toArray());
    }

    private function create($data)
    {
        $decimal = dp_display(get_currency(base_currency(), "type"));
        foreach ($data as $item) {
            $current = Carbon::parse($item[$this->dateColumn])->startOfDay();
            $dateExists = $current->betweenIncluded(array_key_first($this->timeData), array_key_last($this->timeData));
            if ($dateExists) {
                $this->timeData[$current->format($this->dateFormat)] = $this->type == 'amount' ? amount_format($item[$this->resultColumn], ['decimal' => $decimal]) : intval($item[$this->resultColumn]);
            }
        }
        return $this;
    }

    public function getMonthly($data)
    {
        $start = Carbon::now()->startOfMonth()->tz(time_zone());
        $end = Carbon::now()->endOfMonth()->tz(time_zone());

        $this->generate($start, $end);

        return $this->create($data);
    }

    public function getWeekly($data)
    {
        $start = Carbon::now()->startOfWeek()->tz(time_zone());
        $end = Carbon::now()->endOfWeek()->tz(time_zone());

        $this->generate($start, $end);

        return $this->create($data);
    }

    public function getDays($data, $day = 15)
    {
        $start = Carbon::now()->subDays($day - 1)->startOfDay()->tz(time_zone());

        $end = Carbon::now()->tz(time_zone());

        $this->generate($start, $end);

        return $this->create($data);
    }


    public function flatten($inc = true)
    {
        $last = 0;
        foreach ($this->timeData as $key => $value) {
            if ($value !== 0) {
                $last =  ($inc) ? $value : 0;
            } else {
                $this->timeData[$key] = $last;
            }
        }
        return $this->timeData;
    }

    public function get()
    {
        return $this->timeData;
    }
}
