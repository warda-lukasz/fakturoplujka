<?php

namespace Model;

abstract class AbstractModel
{
    /**
     * @param string $path
     * @return $this
     */
    public function setFromFile(string $path): self
    {
        $file = file_get_contents($path);
        $arr = json_decode($file, true);

        foreach ($arr as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value): void
    {
        $this->$name = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @return array
     */
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

