<?php

namespace AwStudio\LaravelStrapi\Models;

use Illuminate\Support\Str;

/**
 * SingleType Model (Singular Endpoint)
 */
abstract class StrapiSingleType extends StrapiModel
{
    protected function generateEndpoint(): string
    {
        return Str::of(class_basename(static::class))->snake()->singular()->toString();
    }
}
