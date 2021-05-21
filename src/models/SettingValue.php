<?php

namespace floor12\settings\models;

/**
 *
 * @property string $id
 * @property string $value
 */
class SettingValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['id', 'value'], 'string', 'max' => 255],
        ];
    }

}