<?php


namespace floor12\settings\models;

use floor12\settings\Module;
use yii\base\Model;
use Yii;

class SettingsForm extends Model
{
    public $values = [];
    private $module;

    public function init()
    {
        $this->module = Yii::$app->getModule(Module::MODULE_NAME);
        parent::init();
    }

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
                $this->module->settingsProcessor->clear($path);
            } else {
                $this->module->settingsProcessor->updateData($path, $value);
            }
        }
        return true;
    }
}