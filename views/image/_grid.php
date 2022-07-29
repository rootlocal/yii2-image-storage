<?php

use app\models\search\ImageSearch;
use app\widgets\lightbox\Lightbox;
use kartik\date\DatePicker;
use yii\bootstrap4\Html;
use yii\bootstrap4\LinkPager;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\web\View;

/**
 * @var $this View
 * @var $searchModel ImageSearch
 * @var $dataProvider ActiveDataProvider
 */
?>

<?= Lightbox::widget(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => '<div class="box-header">{summary}{pager}</div>{items}<div class="box-footer">{pager}</div>',

    'tableOptions' => [
        'class' => 'table table-striped table-hover',
    ],

    'pager' => [
        'class' => LinkPager::class,
        'options' => [
            'class' => 'image-pager',
        ]
    ],

    'columns' => [
        [
            'class' => SerialColumn::class,
            'headerOptions' => ['width' => '20px', 'class' => 'text-center'],
        ],

        [
            'attribute' => 'preview',
            'format' => 'raw',
            'value' => function ($model) {
                /** @var $model ImageSearch */

                $thumb = Html::img($model->getPreviewUrl(), ['alt' => $model->old_name]);

                return Html::a($thumb, $model->getUrl(), [
                    'data-lightbox' => 'roadtrip',
                    'data-title' => $model->old_name,
                    'data-alt' => $model->old_name,
                ]);
            }
        ],

        [
            'attribute' => 'old_name',
            'format' => 'raw',
            'value' => function ($model) {
                /** @var $model ImageSearch */
                return $model->old_name;
            }
        ],

        [
            'attribute' => 'created_at',
            'format' => 'datetime',
            'headerOptions' => [
                'style' => 'width: 210px;  min-width:210px;',
                'class' => 'text-center',
            ],
            'filter' => DatePicker::widget([
                'bsVersion' => '4.x',
                'model' => $searchModel,
                'attribute' => 'created_at',
                'value' => date('d-M-Y', strtotime('+2 days')),

                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'Select date',
                    'autocomplete' => 'off',
                ],
                'language' => 'ru',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'removeIcon' => '<i class="fa fa-trash text-primary"></i>',
                'pluginOptions' => [
                    'calendarWeeks' => true,
                    'autoclose' => true,
                    'todayBtn' => false,
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true
                ],
            ]),
        ],

        [
            'class' => ActionColumn::class,
            'template' => '{delete}',
            'buttons' => [

            ],


        ],
    ]
]); ?>
