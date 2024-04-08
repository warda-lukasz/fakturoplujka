<?php

namespace Model;

abstract class AbstractModel
{
    public function __construct(string $path)
    {
        $this->setFromFile($path);
    }
    
    public function setFromFile(string $path): self
    {
        $file = file_get_contents($path);
        $arr = json_decode($file, true);

        foreach ($arr as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
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
