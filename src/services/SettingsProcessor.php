<?php


namespace floor12\settings\services;

use Flintstone\Flintstone;
use floor12\settings\models\SettingGroup;
use floor12\settings\models\SettingGroupCollection;
use floor12\settings\models\SettingItem;
use floor12\settings\Module;


class SettingsProcessor
{
    private $settingsMap;
    /** @var SettingItem[] */
    private $settings = [];
    private $settigsFromDB = [];
    private $settingDefaults = [];
    private $path = [];

    public function __construct(array $settingsMap, string $settingsPath)
    {
        $this->settingsDB = (new Flintstone('settings', ['dir' => $settingsPath]));
        $this->settigsFromDB = $this->settingsDB->getAll();
        $this->settingsMap = $settingsMap;
        try {
            $this->settings = $this->processMap($this->settingsMap);
        } catch (\Throwable $exception) {
            new \ErrorException(sprintf('Settings reading error: $s', $exception->getMessage()));
        }
    }

    public function updateData($path, $value)
    {
        $this->settingsDB->delete($path);
        $this->settingsDB->set($path, $value);
        $this->settigsFromDB = $this->settingsDB->getAll();
    }

    public function clear($path)
    {
        $this->settingsDB->delete($path);
        $this->settigsFromDB = $this->settingsDB->getAll();
    }

    /**
     * SettingItem
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($id)
    {
        return $this->settings[$id] ?: $this->settingDefaults[$id];
    }

    private function processMap(array $settingsMap)
    {
        $settings = [];
        foreach ($settingsMap as $key => $configItem) {
            $this->path[] = $key;

            if ($this->isGroup($configItem) === false) {
                $item = new SettingItem();
                $item->name = $configItem[0];
                $item->path = implode('_', $this->path);
                $item->value = isset($this->settigsFromDB[$item->path]) ? $this->settigsFromDB[$item->path] : null;
                $item->default = isset($configItem[2]) ? $configItem[2] : null;
                $item->type_id = $configItem[1];
                $this->settingHeap[$key] = $item;
                $settings[array_pop($this->path)] = $item;
            } else {
                if ($this->isMultyple($configItem)) {
                    $item = new SettingGroupCollection();
                    $item->setName($configItem[0]);
                    $item->groups = $this->readMultiple($configItem);
                    $settings[$key] = $item;
                } else {
                    $item = new SettingGroup();
                    $item->setName($configItem[0]);
                    $item->setElements($this->processMap($configItem[array_key_last($configItem)]));
                    $settings[array_pop($this->path)] = $item;
                }
            }
        }
        return $settings;
    }

    private function readMultiple($configItem)
    {
        $counter = 0;
        $groups = [];
        $this->path[] = '';
        do {
            array_pop($this->path);
            $this->path[] = $counter;
            $basicGroup = new SettingGroup();
            $basicGroup->setName("Элeмент {$counter}");
            $basicGroup->setElements($this->processMap($configItem[array_key_last($configItem)]));
            $counter++;
        } while (($counter === 1 || $basicGroup->hasValues()) && $groups[] = $basicGroup);

        return $groups;
    }

    public function isGroup(array $confitItem)
    {
        foreach ($confitItem as $value) {
            if ($value === Module::TYPE_GROUP)
                return true;
        }
        return false;
    }

    public function isMultyple(array $confitItem)
    {
        foreach ($confitItem as $value) {
            if ($value === Module::TYPE_MULTYPLE)
                return true;
        }
        return false;
    }

}