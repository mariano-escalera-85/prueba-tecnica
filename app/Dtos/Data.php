<?php

namespace App\Dtos;

use Illuminate\Support\Arr;

abstract class Data
{
    public static function from(array $data): static
    {
        return new static(...$data);
    }

    public function only(array $only): array
    {
        return Arr::only($this->toArray(), $only);
    }

    public function except(array $except): array
    {
        return Arr::except($this->toArray(), $except);
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
