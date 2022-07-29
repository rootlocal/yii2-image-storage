<?php

use app\models\dto\ImageUploadForm;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ImageUploadForm $model
 */
?>

<div class="image-upload-form">

    <?php $form = ActiveForm::begin([
        'id' => 'image-upload-form',
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="row">
        <div class="col-12">
            <?= $form->errorSummary($model); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'files[]')->fileInput([
                'multiple' => true,
                'accept' => $model->getAccept(),
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Upload'), [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end() ?>

</div>