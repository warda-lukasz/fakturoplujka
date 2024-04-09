<?php

namespace Model;

abstract class AbstractModel implements ModelInterface
{
    protected const string MODEL_PREFIX = '';

    public function __construct(string $path)
    {
        $this->setFromFile($path);
    }

    public function setFromFile(string $path): self
    {
        $arr = json_decode(file_get_contents($path), true);

        foreach ($arr as $property => $value) {
            $this->__set($property, $value);
        }

        return $this;
    }

    public function __get(string $property): mixed
    {
        return $this->$property;
    }

    public function __set(string $property, mixed $value): void
    {
        $this->$property = $value;
    }

    public function getPatternsAndReplacements(): array
    {
        $arr = [];
        foreach ($this as $key => $value) {
            if (is_object($value)) continue;
            $keyString = $this::MODEL_PREFIX . $key;
            $arr["%(\/\/)({$keyString})(\/\/)%"] = $value;
        }

        return $arr;
    }
}
