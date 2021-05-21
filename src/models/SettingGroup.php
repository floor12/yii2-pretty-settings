<?php


namespace floor12\settings\models;


class SettingGroup
{
    private $name;
    private $elements = [];

    public function setElements($elements)
    {
        $this->elements = $elements;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSetting($id)
    {
        return $this->elements[$id];
    }

    public function hasValues()
    {
        $hasValue = false;
        foreach ($this->elements as $element) {
            if ($element->value !== null) {
                $hasValue = true;
            }
        }
        return $hasValue;
    }

    public function isGroup()
    {
        return true;
    }

    public function isMultiple()
    {
        return false;
    }
}