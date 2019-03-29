<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 28.03.19
 * Time: 10:34
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Content extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'object', 'lang'], 'required'],
            [['type', 'object'], 'integer'],
            [['value'], 'string'],
            [['lang'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'object' => 'Object',
            'lang' => 'Lang',
            'value' => 'Value',
        ];
    }

    public const TYPE_ARTICLE_NAME = 40;
    public const TYPE_ARTICLE_DESCRIPTION = 47;

    public static function remove($typeID, $objectID)
    {
        return self::deleteAll(['type' => $typeID, 'object' => $objectID]);
    }

    public static function getHeaderClass()
    {
        switch (Yii::$app->requestedRoute) {
            default: return 'class="dark"'; break;
        }
    }
}