<?php

namespace AwStudio\LaravelStrapi\Models;

use Illuminate\Support\Str;

/**
 * CollectionType Model (Plural Endpoint)
 */
abstract class StrapiCollectionType extends StrapiModel
{
    protected function generateEndpoint(): string
    {
        return Str::of(class_basename(static::class))->snake()->plural()->toString();
    }
}
