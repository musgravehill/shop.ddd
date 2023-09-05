<?php

declare(strict_types=1);

namespace app\components\BrandCategory\App\Form;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;
use yii\base\Model;
use yii\web\UploadedFile;

class FormBrandCategoryCr extends Model
{
    public $brandId;
    public $name;
    public $dsc;
    public $searchQuery;
    public $searchPriceMin;
    public $searchPriceMax;
    public $searchOffers;

    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['brandId'], 'required'],
            //
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 128],
            //
            [['dsc'], 'trim'],
            [['dsc'], 'required'],
            [['dsc'], 'string', 'max' => 65535],
            //
            [['searchQuery'], 'trim'],
            [['searchQuery'], 'required'],
            [['searchQuery'], 'string', 'max' => 128],
            //
            [['searchPriceMin'], 'required'],
            [['searchPriceMin'], 'integer'],
            //
            [['searchPriceMax'], 'required'],
            [['searchPriceMax'], 'integer'],
            //
            [['searchOffers'], 'trim'],
            //[['searchOffers'], 'required'],
            [['searchOffers'], 'string', 'max' => 65535],
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
            'brandId' => 'Производитель',
            'name' => 'Название',
            'dsc' => 'Описание',
            'searchQuery' => 'Поиск товаров: фраза',
            'searchPriceMin' => 'Поиск товаров: цена Min',
            'searchPriceMax' => 'Поиск товаров: цена Max (0-без ограничений)',
            'searchOffers' => 'Советы-фразы по поиску товаров, ',
            'imageFile' => 'Логотип',
        ];
    }
}
