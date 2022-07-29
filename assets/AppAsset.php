<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use rmrevin\yii\fontawesome\AssetBundle as FontAwesomeAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    /** @var string[] */
    public $css = ['css/site.css'];
    /** @var string[] */
    public $js = [];
    /** @var string[] */
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        FontAwesomeAsset::class
    ];


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }
}
