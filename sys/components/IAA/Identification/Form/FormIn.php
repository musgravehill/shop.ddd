<?php

declare(strict_types=1);

namespace app\components\IAA\Identification\Form;

use yii\base\Model;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

class FormIn extends Model
{
    public $password;
    public $email;    

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 3, 'max' => 32],
            ['email', 'trim'],
            //            
            ['password', 'string', 'min' => 6, 'max' => 12],
            ['password', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'email' => 'Email',
        ];
    }

    //  $this->addError('password', 'Неправильные данные.');

    /* 
      public function isPhone($attribute) {
      if (!preg_match('/^[0-9]{10}$/', $this->$attribute)) {
      $this->addError($attribute, 'Нужен мобильный телефон.');
      return false;
      }
      // if (!preg_match('/^9[0-9]{9}$/', $this->$attribute)) {
      // $this->addError($attribute, 'Первая цифра должна быть "9".');
      // return false;
      // }
      return true;
      }*/

    /* 
      public function beforeValidate() {
      if (!empty($this->phone)) {
      $this->phone = HelperY::purify(str_replace('+7', '', $this->phone), '/[^\d]/Uui'); //10 digits without +7
      }


      return parent::beforeValidate();
      } */
}
