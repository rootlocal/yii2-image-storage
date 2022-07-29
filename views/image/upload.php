<?php

use app\models\dto\ImageUploadForm;
use yii\web\View;

/**
 * @var View $this
 * @var ImageUploadForm $model
 */

$this->title = Yii::t('app', 'Upload Files');
$this->params['breadcrumbs'] = [
    'index' => ['url' => '/image/index', 'label' => Yii::t('app', 'Images List')],
    'upload' => $this->title,
];
?>

<div class="image-upload">
    <h1><?= $this->title ?></h1>

    <?= $this->render('_upload_form', ['model' => $model]) ?>
</div>