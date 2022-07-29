<?php

use app\models\search\ImageSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;


/**
 * @var $this View
 * @var $searchModel ImageSearch
 * @var $dataProvider ActiveDataProvider
 */

$this->title = Yii::t('app', 'Images List');
$this->params['breadcrumbs'] = ['index' => $this->title];
?>

<div class="image-index-page">
    <h1><?= $this->title ?></h1>

    <div class="form-group">
        <?= Html::a(Yii::t('app', 'Upload Files'), '/image/upload', [
            'class' => 'btn btn-primary',
        ]) ?>
    </div>

    <?php Pjax::begin([
        'id' => 'image-grid-pjax',
        'scrollTo' => true,
    ]); ?>

    <div class="image-grid">
        <?= $this->render('_grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); ?>
    </div>

    <?php Pjax::end() ?>
</div>


