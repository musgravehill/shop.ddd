<?php

declare(strict_types=1);

namespace app\components\User\App\Form;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

use yii\base\Model;

class FormUserU extends Model
{
    public $username;
    public $email;
    public $phone;
    public $cityName;
    public $address;

    public function beforeValidate()
    {
        if (!empty($this->phone)) { //10 digits without +7
            $this->phone = str_replace('+7', '', $this->phone);
            $this->phone = preg_replace('/[^\d]/Uui', '', $this->phone);
        }

        if (!empty($this->username)) {
            $this->username = HelperY::purify($this->username, '/[^\w\s]/Uui');
        }

        return parent::beforeValidate();
    }

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 1, 'max' => 32],
            //        
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 32],
            //            
            ['phone', 'required'],
            ['phone', 'isPhone'],
            //
            ['cityName', 'required'],
            ['cityName', 'string', 'min' => 1, 'max' => 512],
            //
            //['address', 'required'],
            ['address', 'string', 'min' => 0, 'max' => 512],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Контрагент* (ФИО или Название)',
            'email' => 'Email*',
            'phone' => 'Телефон*',
            'cityName'=>'Город доставки*',
            'address'=>'Адрес доставки',
        ];
    }

    public function isPhone($attribute)
    {
        if (!preg_match('/^[0-9]{10}$/', $this->$attribute)) {
            $this->addError($attribute, 'Нужен мобильный телефон.');
            return false;
        }
        return true;
    }
}
