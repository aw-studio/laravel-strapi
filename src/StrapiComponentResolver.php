<?php

namespace AwStudio\LaravelStrapi;

use Illuminate\Support\Str;

class StrapiComponentResolver
{
    private $components = [];

    public function __construct()
    {
        $registeredComponentGroups = config('laravel-strapi.components');

        // loop through the registered components and instantiate them
        foreach ($registeredComponentGroups as $groupKey => $groupComponents) {
            foreach ($groupComponents as $component) {
                $component = new $component;
                $componentKey = $component->getKey();
                $key = Str::of($groupKey)->kebab()->toString().'.'.$componentKey;
                $this->components[$key] = $component;
            }
        }
    }

    public function resolve(array $item)
    {
        if (! isset($item['__component'])) {
            return null;
        }

        if (! $component = $this->components[$item['__component']]) {
            return null;
        }

        return $component->render()->with('attributes', $item);
    }
}
