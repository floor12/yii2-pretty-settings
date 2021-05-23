<?php

namespace floor12\settings;

use floor12\settings\services\SettingsProcessor;


class Module extends \yii\base\Module
{
    const MODULE_NAME = 'f12settings';

    const TYPE_BOOL = 'bool';
    const TYPE_STRING = 'string';
    const TYPE_GROUP = 'group';
    const TYPE_MULTYPLE = 'multyple';
    /**
     * @var SettingsProcessor
     */
    public $settingsProcessor;
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'settings/index';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'floor12\settings\controllers';

    /**
     * @var string Main admin layout
     */
    public $layoutBackend = '@app/views/layouts/main';

    /**
     * @var string[] This value will pass to Access Control Filter of CRUD controller
     */
    public $accessControlRoles = ['@'];

    /**
     * @var array Typed map of the applications settings
     */
    public $settingsMap = [];

    /**
     * @var string <h1> and page title for admin page
     */
    public $settingsPageTitle = 'Settings';

    public function getSettings()
    {
        return $this->settingsProcessor->getSettings();
    }

    public function getSetting($id)
    {
        return $this->settingsProcessor->getSetting($id);
    }

}
