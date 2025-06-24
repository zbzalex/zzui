<?php

namespace zzui\orm;

abstract class Entity
{
    protected $__modified;

    public function __construct(array $data = [])
    {
        $this->__modified = [];

        self::from($data, $this, '\zzui\orm\Entity::camelToSnake');
    }

    public static function camelToSnake($input)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }

    public static function snakeToCamel($input)
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }

    public static function from(array $data, $entity, $namingStrategy = null, $prefix = null)
    {
        $reflectionClass = new \ReflectionClass($entity);

        if (is_string($entity)) {
            $entity = $reflectionClass->newInstance();
        }

        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $k = $namingStrategy !== null
                ? call_user_func_array($namingStrategy, [
                    $property->getName()
                ])
                : $property->getName();

            $k = $prefix !== null ? $prefix . $k : $k;

            if (isset($data[$k])) {
                $property->setValue($entity, $data[$k]);
            }
        }
    }

    protected function getColumns()
    {
        $reflector  = new \ReflectionClass($this);
        $properties = $reflector->getProperties();

        $properties = array_filter(
            $properties,
            function (\ReflectionProperty $property) {
                return strpos($property->getName(), "_") === false;
            }
        );

        $properties = array_reduce(
            $properties,
            function ($ax, $dx) {
                try {
                    $ax[$dx->getName()] = $dx->getValue();
                } catch (\ReflectionException $e) {
                    $ax[$dx->getName()] = null;
                }
                return $ax;
            },
            []
        );

        return $properties;
    }

    public function __set($property, $value)
    {
        $columns = $this->getColumns();

        if (!array_key_exists($property, $columns)) {
            throw new \InvalidArgumentException();
        }

        $this->__modified[$property] = $value;
    }

    public function __get($property)
    {
        $columns = $this->getColumns();

        if (!array_key_exists($property, $columns)) {
            throw new \InvalidArgumentException();
        }

        return $columns[$property];
    }

    public function getModifiedColumns()
    {
        return $this->__modified;
    }

    public function reset()
    {
        $this->__modified = [];
    }

    public function getPrimaryKey()
    {
        return 'id';
    }
}
