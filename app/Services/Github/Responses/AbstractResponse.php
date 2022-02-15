<?php

declare(strict_types=1);

namespace App\Services\Github\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use InvalidArgumentException;

abstract class AbstractResponse implements Arrayable
{
    /**
     * @var array List of fields that can be put to $attributes.
     */
    protected $fillable = [];

    protected $attributes = [];
    /**
     * @var array List of castable fields. Supported casts are - int, bool, and object.
     */
    protected $casts = [
        // 'id' => 'int',
        // 'deleted' => 'bool',
        // 'user' => \Responses\User:class,
        // 'reviewers.*' => \Responses\User:class,
    ];

    public function __construct(array $responseData)
    {
        $this->attributes = Arr::only($responseData, $this->fillable);
    }

    public function __get(string $name)
    {
        if (!in_array($name, $this->fillable)) {
            throw new InvalidArgumentException("Attribute '$name' is not available.");
        }

        if (key_exists($name, $this->attributes)) {

            $value = $this->attributes[$name];

            if (key_exists($name, $this->casts)) {
                $value = $this->castAttribute($value, $this->casts[$name]);
            }

            $arrayCast = $name . '.*';
            if (key_exists($arrayCast, $this->casts)) {
                $cast = $this->casts[$arrayCast];
                $value = array_map(function ($valueItem) use ($cast) {
                    return $this->castAttribute($valueItem, $cast);
                }, $value);
            }


            return $value;
        }

        return null;
    }

    public function __isset(string $name): bool
    {
        return in_array($name, $this->fillable);
    }

    protected function castAttribute($value, $cast)
    {
        switch ($cast) {
            case 'int':
                return $this->castInt($value);

            case 'bool':
                return $this->castBool($value);

            default: // object
                $result = $this->castObject($value, $cast);

                if (!is_object($result)) {
                    throw new InvalidArgumentException(sprintf('Unknown cast "%s"', $cast));
                }

                return $result;
        }

    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    protected function castInt($value): int
    {
        return (int) $value;
    }

    protected function castBool($value): bool
    {
        return (bool) $value;
    }

    protected function castObject($value, string $className): ?object
    {
        if (class_exists($className)) {
            return new $className($value);
        }

        return null;
    }
}
