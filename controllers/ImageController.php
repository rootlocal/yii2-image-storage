<?php

namespace app\controllers;


use app\models\db\Image;
use app\models\dto\ImageUploadForm;
use app\models\search\ImageSearch;
use rootlocal\crud\actions\DeleteAction;
use rootlocal\crud\actions\IndexAction;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class ImageController
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package app\controllers
 */
class ImageController extends Controller
{


    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET'],
                    'upload' => ['POST', 'GET'],
                    'delete' => ['POST']
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => ImageSearch::class,
            ],

            'delete' => [
                'class' => DeleteAction::class,
                'model' => Image::class,
            ],
        ];
    }

    /**
     * @return string|Response
     */
    public function actionUpload()
    {
        $model = new ImageUploadForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->files = UploadedFile::getInstances($model, 'files');

            if ($model->validate()) {
                $model->uploadFiles();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Files uploaded'));
                return $this->redirect('/image/index');
            }

            if ($model->hasErrors('files')) {
                Yii::$app->session->addFlash('error', $model->getFirstError('files'));
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

}