<?php

namespace Nasa;

use DateTime;

class FilterCriteria
{
    public DateTime $date;
    public DateTime $start_date;
    public DateTime $end_date;
    public int $count;
    public bool $thumbs;
    public int $sol;

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function setSol(int $sol): void
    {
        $this->sol = $sol;
    }

    public function setStartDate(DateTime $start_date): void
    {
        $this->start_date = $start_date;
    }

    public function setEndDate(DateTime $end_date): void
    {
        $this->end_date = $end_date;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function setThumbs(bool $thumbs): void
    {
        $this->thumbs = $thumbs;
    }

    public function getQueryParameters(): string
    {
        $params = [];

        foreach (get_object_vars($this) as $key => $value) {
            if (!is_null($value)) {
                if ($value instanceof DateTime) {
                    $params[$key] = $value->format('Y-m-d');
                } else {
                    $params[$key] = $value;
                }
            }
        }

        return http_build_query($params);
    }
}
