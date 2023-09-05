<?php

declare(strict_types=1);

namespace app\components\IAA\AccessRecovery\Form;

use yii\base\Model;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

class FormAccessRecoveryInit extends Model
{
    public $email;
    public $verifyCode;   

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'min' => 3, 'max' => 32],
            ['email', 'trim'],
            // ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот email уже используется.'],
            //
            ['verifyCode', 'required'],
            ['verifyCode', 'captcha', 'captchaAction' => 'auth/captcha',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'verifyCode' => 'Буквы',
        ];
    }

    //  $this->addError('pass', 'Неправильные данные.');

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
