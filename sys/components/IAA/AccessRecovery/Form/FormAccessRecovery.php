<?php

declare(strict_types=1);

namespace app\components\IAA\AccessRecovery\Form;

use yii\base\Model;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelperY;

class FormAccessRecovery extends Model
{
    public $password;    

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 12],
            ['password', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Придумайте пароль',

        ];
    }
}
