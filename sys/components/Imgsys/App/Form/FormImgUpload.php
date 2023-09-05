<?php

declare(strict_types=1);

namespace app\components\Imgsys\App\Form;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use yii\base\Model;

class FormImgUpload extends Model
{

    /**
     * @var UploadedFiles[]
     */
    public $imageFiles;
    public $tags;

    public function rules()
    {
        return [
            [
                ['imageFiles'],
                'file',
                'skipOnEmpty' => false,
                'checkExtensionByMimeType' => false,
                'extensions' => ['jpg', 'png', 'jpeg'],
                //'mimeTypes' => ['image/jpeg', 'image/jpg', 'image/png'],
                'maxFiles' => 10,
                'maxSize' => 1024 * 1024 * 1
            ],
            //            
            ['tags', 'string', 'min' => 0, 'max' => 512],
            ['tags', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'imageFiles' => 'Выберите файлы с картинками',
            'tags' => 'Ключевые слова',
        ];
    }    
}
