<?php

namespace app\models\db;

use app\models\query\ImageQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Class Image
 *
 * @property string $id [integer]
 * @property string $name [varchar(255)] Имя файла изображения
 * @property string $old_name [varchar(255)] оригинальное имя файла
 * @property string $created_at [integer] unix timestamp загрузки файла
 * @property string $ext [varchar(255)] расширение файла
 *
 * @property-read string $uri полный uri путь файла
 * @property-read string $url url файла
 * @property-read string $previewUrl url превью файла
 * @property-read string $previewUri полный uri путь превью файла
 *
 * @author Alexander Zakharov <sys@eml.ru>
 * @package app\models\db
 */
class Image extends ActiveRecord
{
    /** @var string Имя папки загрузки изображений */
    public const UPLOAD_FOLDER = 'images';

    /** @var string|null */
    private ?string $_uri = null;
    /** @var string|null */
    private ?string $_url = null;
    /** @var string|null */
    private ?string $_previewUri = null;
    /** @var string|null */
    private ?string $_previewUrl = null;


    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return '{{%image}}';
    }

    /**
     * {@inheritdoc}
     * @return ImageQuery the active query used by this AR class.
     */
    public static function find(): ImageQuery
    {
        return new ImageQuery(get_called_class());
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'old_name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'preview' => Yii::t('app', 'Preview'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->isNewRecord) {
            $this->created_at = time();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete(): bool
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if (is_file($this->getUri())) {
            @unlink($this->getUri());
        }

        if (is_file($this->getPreviewUri())) {
            @unlink($this->getPreviewUri());
        }

        return true;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        if ($this->_uri == null) {
            $file = sprintf('@app/web/%s/%s.%s', self::UPLOAD_FOLDER, $this->name, $this->ext);
            $this->_uri = Yii::getAlias($file);
        }

        return $this->_uri;
    }

    /**
     * @return string
     */
    public function getPreviewUri(): string
    {
        if ($this->_previewUri === null) {
            $file = sprintf('@app/web/%s/%s-preview.%s', self::UPLOAD_FOLDER, $this->name, $this->ext);
            $this->_previewUri = Yii::getAlias($file);
        }

        return $this->_previewUri;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        if ($this->_url === null) {
            $file = sprintf('/%s/%s.%s', self::UPLOAD_FOLDER, $this->name, $this->ext);
            $this->_url = Url::to($file, true);
        }

        return $this->_url;
    }

    /**
     * @return string
     */
    public function getPreviewUrl(): string
    {
        if ($this->_previewUrl === null) {
            $file = sprintf('/%s/%s-preview.%s', self::UPLOAD_FOLDER, $this->name, $this->ext);
            $this->_previewUrl = Url::to($file, true);
        }

        return $this->_previewUrl;
    }
}