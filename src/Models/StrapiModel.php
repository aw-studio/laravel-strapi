<?php

namespace AwStudio\LaravelStrapi\Models;

use ArrayAccess;
use AwStudio\LaravelStrapi\StrapiQueryBuilder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class StrapiModel implements Arrayable, ArrayAccess, Jsonable, JsonSerializable
{
    protected string $endpoint;

    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->endpoint)) {
            $this->endpoint = $this->generateEndpoint();
        }

        $this->attributes = $attributes;
    }

    /**
     * Automatically generates the Strapi endpoint from the class name.
     */
    abstract protected function generateEndpoint(): string;

    /**
     * Start a new query on the Strapi API.
     */
    public static function query(): StrapiQueryBuilder
    {
        return new StrapiQueryBuilder((new static)->endpoint, static::class);
    }

    /**
     * Allow static calls like Post::where()
     */
    public static function __callStatic($method, $arguments)
    {
        return static::query()->$method(...$arguments);
    }

    /**
     * Attribute getter.
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Attribute setter.
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Check if an attribute is set.
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Convert to array.
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Convert to JSON.
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }
}
