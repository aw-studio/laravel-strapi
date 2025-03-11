<?php

namespace AwStudio\LaravelStrapi\View\Components;

use AwStudio\LaravelStrapi\StrapiComponentResolver;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dynamiczone extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $items,
        public StrapiComponentResolver $resolver
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('strapi::dynamiczone');
    }
}
