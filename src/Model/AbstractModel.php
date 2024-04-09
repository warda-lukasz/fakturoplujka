<?php

namespace Model;

abstract class AbstractModel implements ModelInterface
{
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

    public function __set(string $property, $value): void
    {
        $this->$property = $value;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getPatternsAndReplacements(): array
    {
        $arr = [];
        foreach ($this as $key => $value) {
            if (is_object($value)) continue;
            $arr["%(\/\/)({$key})(\/\/)%"] = $value;
        }

        return $arr;
    }
}
