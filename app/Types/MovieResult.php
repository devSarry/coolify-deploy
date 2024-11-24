<?php

namespace App\Types;

use Livewire\Wireable;

class MovieResult implements Wireable
{
    public string $title;
    public int $year;
    public string $poster;

    public function __construct($title, $year, $poster)
    {
        $this->title = $title;
        $this->year = $year;
        $this->poster = $poster;
    }

    public function toLivewire(): array
    {
        return [
            'title' => $this->title,
            'year' => $this->year,
            'poster' => $this->poster,
        ];
    }

    public static function fromLivewire($value): MovieResult
    {
        return new self($value['title'], $value['year'], $value['poster']);
    }
}
