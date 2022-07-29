<?php

namespace app\models\dto;

use app\models\db\Image;
use Exception;
use Symfony\Component\Mime\MimeTypes;
use Throwable;
use Yii;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

/**
 * Class ImageUploadForm Data transfer object
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package app\models\dto
 */
class ImageUploadForm extends Model
{
    /** @var int максимальный Общий размер загруженных файлов в документе Мб */
    public const MAX_FILES_SIZE = 30;
    /** @var string разрешенные расширения */
    public const ALLOWED_EXTENSIONS = 'png, jpg, gif, jpeg';

    /** @var UploadedFile[]|null */
    public ?array $files = null;

    /** @var MimeTypes|null */
    private ?MimeTypes $_mimeTypes = null;
    /** @var string|null */
    private ?string $_accept = null;


    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                ['files'], 'file',
                'skipOnEmpty' => false,
                'extensions' => self::ALLOWED_EXTENSIONS,
                'maxFiles' => 5,
                'maxSize' => 1024 * 1024 * self::MAX_FILES_SIZE,
                'checkExtensionByMimeType' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'files' => Yii::t('app', 'Files attachments'),
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if (!empty($this->files)) {
            $size = 0;
            foreach ($this->files as $file) {
                $size += $file->size;
            }

            if (round($size / pow(1024, 2)) > self::MAX_FILES_SIZE) {

                $this->addError('files',
                    Yii::t('app', 'The maximum file size is more than {max_files_size} MB', [
                        'max_files_size' => self::MAX_FILES_SIZE,
                    ]));

                return false;
            }
        }

        return true;
    }

    /**
     * Uploaded files method post
     */
    public function uploadFiles()
    {
        foreach ($this->files as $file) {
            if (!empty($file)) {
                $this->uploadFile($file);
            }
        }
    }

    /**
     * Upload one file
     *
     * @param UploadedFile $file
     * @return false|Image
     */
    public function uploadFile(UploadedFile $file)
    {
        try {
            $model = $this->createModel($file);

            if ($model->save(false)) {
                $file->saveAs($model->getUri());
                \yii\imagine\Image::thumbnail($model->getUri(), 150, 150)
                    ->save($model->getPreviewUri());

                return $model;
            } else {
                $this->addError('files', Yii::t('app', 'Error Save Model.'));
                @unlink($file->tempName);
                return false;
            }

        } catch (Exception|Throwable $e) {

            Yii::error($e->getMessage(), self::class);
            $this->addError('files', Yii::t('app',
                'Error upload File. {message}', [
                    'message' => $e->getMessage(),
                ]));

            @unlink($file->tempName);
            return false;
        }

    }

    /**
     * Создание объекта класса модели [[Image]] и заполнение атрибутов
     *
     * @param UploadedFile $file
     * @return Image
     */
    private function createModel(UploadedFile $file): Image
    {
        $model = new Image();
        $model->name = $this->generateName($file->baseName);
        $model->old_name = $file->baseName;
        $model->ext = mb_strtolower($file->extension, 'UTF-8');
        return $model;
    }

    /**
     * Транслитерация, замена пробелов и проверка уникальности именени файла
     * если файл с таким именем уже содержится в бд добавляется в конец имени "_[порядковый номер]"
     * и производится повторная проверка
     *
     * @param string $filename
     * @return string
     */
    public function generateName(string $filename): string
    {
        $name = mb_strtolower(Inflector::transliterate($filename), 'UTF-8');
        $name = str_replace(' ', '-', $name);
        $if_exits = Image::find()->name($name)->one();

        if ($if_exits !== null) {
            $arr = explode('_', $if_exits->name);

            if (count($arr) > 1 && is_numeric($arr[count($arr) - 1])) {
                $arr[count($arr) - 1] = ((int)$arr[count($arr) - 1] + 1);
                $name = implode('_', $arr);
            } else {
                $name .= '_2';
            }

            $name = $this->generateName($name);
        }

        return $name;
    }

    /**
     * @return string
     */
    public function getAccept(): string
    {
        if ($this->_accept === null) {
            $this->_accept = Yii::$app->cache->getOrSet('accept_image_files', function () {
                $accept = '';
                $array_extensions = explode(',', self::ALLOWED_EXTENSIONS);

                foreach ($array_extensions as $key => $item) {
                    $item = trim($item);
                    $accept .= implode(',', $this->getMimeTypes()->getMimeTypes($item));
                    $accept .= ',.' . $item;
                    if ($key < count($array_extensions) - 1) {
                        $accept .= ',';
                    }
                }

                return $accept;

            }, Yii::$app->params['cacheTime']);

        }

        return $this->_accept;
    }

    /**
     * @return MimeTypes
     */
    public function getMimeTypes(): MimeTypes
    {
        if (empty($this->_mimeTypes)) {
            $this->_mimeTypes = new MimeTypes();
        }

        return $this->_mimeTypes;
    }
}