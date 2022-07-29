<?php

namespace app\widgets\lightbox;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

/**
 * Class Lightbox
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package app\widgets\lightbox
 */
class Lightbox extends Widget
{
    /** @var string */
    public const PLUGIN_NAME = 'lightbox';
    /**
     * @var array the options for the lightbox2 JS plugin.
     * @see https://lokeshdhakar.com/projects/lightbox2/
     */
    public array $clientOptions = [];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $view = $this->getView();
        $this->registerAssets($view);
        $this->registerJs($view);
    }

    /**
     * @param View $view
     */
    private function registerAssets(View $view)
    {
        LightboxAsset::register($view);
    }

    /**
     * @param View $view
     * @return void
     */
    private function registerJs(View $view)
    {
        $clientOptions = Json::encode(ArrayHelper::merge([
            'resizeDuration' => 200,
            'wrapAround' => true,
        ], $this->clientOptions)
        );

        $js = sprintf('%s.option(%s);', self::PLUGIN_NAME, $clientOptions);
        $view->registerJs($js);
    }

}