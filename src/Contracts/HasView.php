<?php

namespace AwStudio\LaravelStrapi\Contracts;

use Closure;
use Illuminate\Contracts\View\View;

interface HasView
{
    public function render(): View|Closure|string;
}
