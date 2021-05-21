<?php

namespace floor12\settings\controllers;

use floor12\settings\assets\SettingsAsset;
use floor12\settings\models\SettingItem;
use floor12\settings\models\SettingsForm;
use floor12\settings\Module;
use Yii;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * @property Module $module
 */
class DefaultController extends Controller
{
    public function actionTest()
    {
        //var_dump(Yii::$app->getModule('f12settings')->getSetting('imapTicketSettings'));
//                var_dump(Yii::$app->getModule('f12settings')->getSetting('basic')->getSetting('mailSign')->getSetting());
//                var_dump(Yii::$app->getModule('f12settings')->getSetting('basic')->getSetting('imapTicketEnable')->getSetting());
//        var_dump(Yii::$app->getModule('f12settings')->getSettings());
//        var_dump(Yii::$app->getModule('f12settings')->getSetting('basic'));

//        var_dump(Yii::$app->getModule('f12settings')->getSetting('imapTicketSettings'));#->groups[0]->getSetting('imapPath'));

        foreach (Yii::$app->getModule('f12settings')->getSetting('imapTicketSettings')->groups as $imapSettingGruop) {
            var_dump($imapSettingGruop->getSetting('imapPath')->getValue());
            var_dump($imapSettingGruop->getSetting('imapLogin')->getValue());
            var_dump($imapSettingGruop->getSetting('imapPassword')->getValue());
        }
//
//        var_dump(Yii::$app->getModule('f12settings')->getSetting('imapTicketSettings'));
//        var_dump(Yii::$app->getModule('f12settings')->getSetting('basic')->getSetting('imapTicketEnable')->getSetting());
//        var_dump(Yii::$app->getModule('f12settings')->getSetting('basic')->getSetting('mailSign')->getSetting());
//        var_dump(Yii::$app->getModule('f12settings')->getSetting('imapTicketSettings')->getSetting());
    }

    public function actionIndex()
    {
        SettingsAsset::register($this->getView());
        $this->layout = $this->module->layoutBackend;
        $settings = $this->module->getSettings();
        $this->getView()->title = $this->module->settingsPageTitle;
        $h1 = Html::tag('h1', $this->module->settingsPageTitle);
        $formBegin = Html::beginForm(['f12settngs/default/update'], 'post', ['class' => 'f12-settings-content']);
        $formEnd = Html::endForm();
        $renderedSettings = $formBegin . $this->renderSettings($settings) . $formEnd;
        return $this->renderContent(Html::tag('div', $h1 . $this->renderControls() . $renderedSettings, ['class' => 'f12-settings-page']));
    }

    public function actionSave()
    {
        $form = new SettingsForm();
        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            return;
        }
        throw new BadRequestHttpException('Ошибка сохранения настроек.');
    }

    /**
     * @param SettingItem[]
     */
    private function renderSettings($array)
    {
        $index = 0;
        $html = '';
        foreach ($array as $settingItem) {
            if ($settingItem->isGroup()) {
                $content = Html::tag('h2', $settingItem->getName());
                if ($settingItem->isMultiple())
                    $content .= Html::button('Добавить новый элемент', [
                        'type' => 'button',
                        'onclick' => 'f12PrettySettings.appendNewElement(this)'
                    ]);
                $content .= $this->renderSettings($settingItem->getElements());

                $subsettings = Html::tag('div', $content, [
                    'class' => 'f12-sub-settings',
                    'data-index' => $index
                ]);

                $html .= Html::tag('div', $subsettings, ['class' => 'f12-settings-block']);
            } else {
                $html .= Html::tag('div', $this->renderSettingsValueItem($settingItem), ['class' => 'f12-settings-block']);
            }
            $index++;
        }
        return $html;
    }

    private function renderSettingsValueItem($item)
    {
        switch ($item->type_id) {
            case Module::TYPE_BOOL:
                return $this->renderCheckbox($item);
                break;
            case Module::TYPE_STRING:
                return $this->renderInput($item);
                break;
        }
    }

    private function renderCheckbox(SettingItem $item)
    {
        $id = 'checkbox' . rand(0, 999);
        $label = Html::label($item->name, $id);
        $hiddenInput = Html::hiddenInput($item->path, 0);
        $checkbox = Html::checkbox("SettingsForm[values][{$item->path}]", boolval($item->value), ['id' => $id]);
        return Html::tag('div', $hiddenInput . $label . $checkbox, ['class' => 'f12-settings-row']);
    }

    private function renderInput(SettingItem $item)
    {
        $id = 'input' . rand(0, 999);
        $label = Html::label($item->name, $id);
        $input = Html::input(
            'text',
            "SettingsForm[values][{$item->path}]",
            $item->value,
            [
                'id' => $id,
                'placeholder' => $item->default
            ]);
        return Html::tag('div', $label . $input, ['class' => 'f12-settings-row']);
    }

    private function renderControls()
    {
        $buttons[] = Html::tag('p', 'Внимание! Есть несохраненные изменения.');
        $buttons[] = Html::button('Отменить', ['onclick' => 'f12PrettySettings.cancel()']);
        $buttons[] = Html::button('Сохранить', ['onclick' => 'f12PrettySettings.save()']);
        return Html::tag('div', implode(' ', $buttons), ['class' => 'f12-settings-control alert alert-info']);
    }

}