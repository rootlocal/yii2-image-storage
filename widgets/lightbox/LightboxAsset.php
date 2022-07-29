<?php

namespace app\widgets\lightbox;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class LightboxAsset
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package app\widgets\lightbox
 */
class LightboxAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@bower/lightbox2/dist';
    /** @var string[] */
    public $css = [
        YII_DEBUG ? 'css/lightbox.css' : 'css/lightbox.min.css',
    ];
    /** @var string[] */
    public $js = [
        YII_DEBUG ? 'js/lightbox.js' : 'js/lightbox.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}