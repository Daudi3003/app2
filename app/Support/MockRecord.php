<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

/**
 * A lightweight stand-in for an Eloquent model during the frontend phase.
 *
 * It supports the same read patterns a Blade view uses against a real model:
 *
 *   {{ $course->course_name }}
 *   {{ $course->instructor->name }}
 *   @foreach ($course->lessons as $lesson)
 *
 * Nested maps become MockRecords and nested lists become Collections, at any
 * depth, so swapping in Eloquent later requires no change to the Blade markup.
 *
 * @extends Fluent<string, mixed>
 */
class MockRecord extends Fluent
{
    public function __construct($attributes = [])
    {
        $cast = [];

        foreach ((array) $attributes as $key => $value) {
            $cast[$key] = static::cast($value);
        }

        parent::__construct($cast);
    }

    /**
     * Recursively convert a value into the shape Blade expects.
     *
     * A list (0,1,2,… keys) becomes a Collection; an associative array becomes
     * a MockRecord; scalars pass through untouched.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected static function cast($value)
    {
        if ($value instanceof self || $value instanceof Collection) {
            return $value;
        }

        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }

        if (! is_array($value)) {
            return $value;
        }

        if (array_is_list($value)) {
            return new Collection(array_map(static fn ($item) => static::cast($item), $value));
        }

        return new static($value);
    }

    /**
     * Mirrors Eloquent's `getKey()` so views can use `$model->getKey()` interchangeably.
     */
    public function getKey(): mixed
    {
        return $this->attributes['id'] ?? null;
    }

    /**
     * Mirrors Eloquent's relation-loaded check used by some Blade guards.
     */
    public function relationLoaded(string $key): bool
    {
        return isset($this->attributes[$key]);
    }
}
