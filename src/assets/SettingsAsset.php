<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 19.06.2018
 * Time: 18:07
 */

namespace floor12\settings\assets;

use yii\web\AssetBundle;

class SettingsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-pretty-settings/assets';

    public $css = [
        'f12-pretty-settings.css'
    ];

    public $js = [
        'f12-pretty-settings.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
