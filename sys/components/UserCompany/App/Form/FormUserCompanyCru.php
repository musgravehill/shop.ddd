<?php

declare(strict_types=1);

namespace app\components\UserCompany\App\Form;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use yii\base\Model;

class FormUserCompanyCru extends Model
{
    public $name;
    public $inn;
    public $kpp=0;
    public $rs;
    public $bik;    

    public function beforeValidate()
    {        
        if (!empty($this->name)) {
            $this->name = HelperY::purify($this->name, '/[^\w\d\s\-]/Uui'); 
        }

        return parent::beforeValidate();
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            // 
            ['inn', 'required'],
            ['inn', 'trim'],
            ['inn', 'string', 'min' => 10, 'max' => 12],
            // 
            ['kpp', 'required'],
            ['kpp', 'trim'],
            ['kpp', 'string', 'min' => 9, 'max' => 9],
            // 
            ['rs', 'required'],
            ['rs', 'trim'],
            ['rs', 'string', 'min' => 20, 'max' => 20],
            // 
            ['bik', 'required'],
            ['bik', 'trim'],
            ['bik', 'string', 'min' => 9, 'max' => 9],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование*',
            'inn' => 'ИНН*',
            'kpp' => 'КПП*',
            'rs' => 'Р\С*',
            'bik' => 'БИК*',
        ];
    }
}
