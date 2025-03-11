<?php

namespace AwStudio\LaravelStrapi;

use Illuminate\Support\Str;

abstract class StrapiComponent implements Contracts\HasView
{
    public string $key = '';

    public string|array $populate;

    public function getKey()
    {
        if ($this->key == '') {
            $classname = get_class($this);

            $this->key = Str::of(Str::of($classname)->explode('\\')->last())->kebab()->toString();
        }

        return $this->key;
    }
}
