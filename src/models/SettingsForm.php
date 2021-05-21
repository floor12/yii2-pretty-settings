<?php


namespace floor12\settings\models;

use yii\base\Model;

class SettingsForm extends Model
{
    public $values = [];

    public function rules()
    {
        return [
            ['values', 'safe']
        ];
    }

    public function save()
    {
        foreach ($this->values as $path => $value) {
            if (($value === '')) {
                SettingItem::clear($path);
            } else {
                SettingItem::updateData($path, $value);
            }
        }
        return true;
    }
}