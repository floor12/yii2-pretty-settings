<?php


namespace floor12\settings\models;


class SettingGroupCollection
{
    public $groups = [];
    private $name;

    public function isGroup()
    {
        return true;
    }

    public function isMultiple()
    {
        return true;
    }

    public function setElements($elements)
    {
        $this->groups = $elements;
    }

    public function getElements()
    {
        return $this->groups;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}