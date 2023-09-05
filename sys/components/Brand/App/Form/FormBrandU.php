<?php

declare(strict_types=1);

namespace app\components\Brand\App\Form;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use yii\base\Model;

class FormBrandU extends Model
{
    public $externalId;
    public $name;
    public $dsc;
    public $imageFile;

    public function rules()
    {
        return [
            [['externalId'], 'trim'],
            [['externalId'], 'required'],
            [['externalId'], 'integer'],
            //
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 128],
            //
            [['dsc'], 'trim'],
            [['dsc'], 'required'],
            [['dsc'], 'string', 'max' => 65535],
            //
            [
                ['imageFile'],
                'file',
                'skipOnEmpty' => true,
                'checkExtensionByMimeType' => false,
                'extensions' => ['jpg', 'jpeg'],
                //'mimeTypes' => ['image/jpeg', 'image/jpg', 'image/png'],
                'maxFiles' => 1,
                'maxSize' => round(1024 * 1024 * 0.3)
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'externalId' => 'ID бренда из CRM',
            'name' => 'Название',
            'dsc' => 'Описание',
            'imageFile' => 'Логотип (если нужно заменить)',
        ];
    }
}
