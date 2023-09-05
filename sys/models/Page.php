<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $title
 * @property string $txt
 * @property string $seoKey
 * @property string $seoDesc
 * @property string $imgUrl1
 * @property string $imgAlt1
 * @property string $imgUrl2
 * @property string $imgAlt2
 * @property string $imgUrl3
 * @property string $imgAlt3
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'txt', 'seoKey', 'seoDesc', 'imgUrl1', 'imgAlt1', 'imgUrl2', 'imgAlt2', 'imgUrl3', 'imgAlt3', 'changedAt'], 'required'],
            [['txt'], 'string'],
            [['title', 'seoKey', 'seoDesc', 'imgUrl1', 'imgAlt1', 'imgUrl2', 'imgAlt2', 'imgUrl3', 'imgAlt3'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'txt' => 'Txt',
            'seoKey' => 'Seo Key',
            'seoDesc' => 'Seo Desc',
            'imgUrl1' => 'Img Url1',
            'imgAlt1' => 'Img Alt1',
            'imgUrl2' => 'Img Url2',
            'imgAlt2' => 'Img Alt2',
            'imgUrl3' => 'Img Url3',
            'imgAlt3' => 'Img Alt3',
        ];
    }
}
