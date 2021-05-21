<?php


namespace floor12\settings\models;


use floor12\settings\Module;

class SettingItem
{
    public $name;
    public $type_id;
    public $value;
    public $path = false;
    public $default = null;

    public static function updateData($id, $value)
    {
        $item = SettingValue::findOne([$id]);
        if (empty($item)) {
            $item = new SettingValue(['id' => $id]);
        }
        $item->value = $value;
        $item->save();
    }

    public static function clear($id)
    {
        if ($item = SettingValue::findOne([$id]))
            $item->delete();
    }

    public function getValue()
    {
        return $this->type_id === Module::TYPE_BOOL ? boolval($this->value) : strval($this);
    }

    public function __toString()
    {
        return $this->value ?: $this->default;
    }


    public function isGroup()
    {
        return false;
    }

    public function isMultiple()
    {
        return false;
    }
}