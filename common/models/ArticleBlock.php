<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 28.03.19
 * Time: 10:29
 */

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "article_block".
 *
 * @property int $id
 * @property int $article_id
 * @property int $type_id
 * @property int $priority
 */
class ArticleBlock extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_block';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'type_id', 'priority'], 'required'],
            [['article_id', 'type_id', 'priority'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'type_id' => 'Type ID',
            'priority' => 'Priority',
        ];
    }

    public const TYPE_TEXT = 1;
    public const TYPE_IMAGE = 2;
    public const TYPE_TEXT_AND_TITLE = 3;
    public const TYPE_LIST_MARKED = 4;
    public const TYPE_LIST_NUMBERED = 5;
    public const TYPE_VIDEO = 6;
    public const TYPE_GALLERY = 7;

    public static $types = [
        self::TYPE_TEXT => 'Текст',
        self::TYPE_IMAGE => 'Фото',
        self::TYPE_TEXT_AND_TITLE => 'Текст из заголовком',
        self::TYPE_LIST_MARKED => 'Помеченный список',
        self::TYPE_LIST_NUMBERED => 'Нумерованный список',
        self::TYPE_VIDEO => 'Youtube видео',
        self::TYPE_GALLERY => 'Галерея',
    ];

    public static $typeViews = [
        self::TYPE_TEXT => '/article/block-text',
        self::TYPE_IMAGE => '/article/block-image',
        self::TYPE_TEXT_AND_TITLE => '/article/block-text-title',
        self::TYPE_LIST_MARKED => '/article/block-list-marked',
        self::TYPE_LIST_NUMBERED => '/article/block-list-numbered',
        self::TYPE_VIDEO => '/article/block-youtube',
        self::TYPE_GALLERY => '/article/block-gallery',
    ];

    public static function create($articleID, $typeID)
    {
        $newArticleBlock = new self();
        $newArticleBlock->article_id = $articleID;
        $newArticleBlock->type_id = $typeID;
        $newArticleBlock->priority = self::find()->select(['MAX(priority)'])->where(['article_id' => $articleID])->scalar() + 1;

        if ($newArticleBlock->save()) {
            return ['result' => true, 'id' => $newArticleBlock->id];
        }

        return ['result' => false, 'error' => $newArticleBlock->errors];
    }

    public static function remove($blockID)
    {
        if ($block = self::findOne($blockID)) {
            foreach (self::find()->where(['AND',
                ['=', 'article_id', $block->article_id],
                ['>', 'priority', $block->priority],
            ])->asArray()->all() as $shiftedBlock) {
                self::updateAll(['priority' => $shiftedBlock['priority'] - 1], ['id' => $shiftedBlock['id']]);
            }
            switch ($block->type_id) {
                case self::TYPE_TEXT:
                    Content::remove(Content::TYPE_ARTICLE_BLOCK_TEXT, $blockID);

                    return $block->delete();
                    break;
                case self::TYPE_IMAGE:
                    Image::remove(Image::TYPE_ARTICLE_BLOCK_IMG, $blockID);

                    return $block->delete();
                    break;
                case self::TYPE_GALLERY:
                    Image::remove(Image::TYPE_ARTICLE_GALLERY_IMG, $blockID);

                    return $block->delete();
                    break;
                case self::TYPE_TEXT_AND_TITLE:
                    Content::remove(Content::TYPE_ARTICLE_TEXT_AND_TITLE_TITLE, $blockID);
                    Content::remove(Content::TYPE_ARTICLE_TEXT_AND_TITLE_TEXT, $blockID);

                    return $block->delete();
                    break;
                case self::TYPE_LIST_MARKED:
                    Content::remove(Content::TYPE_ARTICLE_BLOCK_LIST_MARKED_TITLE, $blockID);
                    Content::remove(Content::TYPE_ARTICLE_BLOCK_LIST_MARKED, $blockID);

                    return $block->delete();
                    break;
                case self::TYPE_LIST_NUMBERED:
                    Content::remove(Content::TYPE_ARTICLE_BLOCK_LIST_NUMBERED_TITLE, $blockID);
                    Content::remove(Content::TYPE_ARTICLE_BLOCK_LIST_NUMBERED, $blockID);

                    return $block->delete();
                    break;
                case self::TYPE_VIDEO:
                    Content::remove(Content::TYPE_ARTICLE_BLOCK_VIDEO, $blockID);

                    return $block->delete();
                    break;
                default: break;
            }
        }

        return false;
    }

    public static function raisePriority($blockID)
    {
        if ($block = self::findOne($blockID)) {
            if ($swapBlock = self::findOne(['article_id' => $block->article_id, 'priority' => $block->priority - 1])) {
                ++$swapBlock->priority;
                --$block->priority;

                return $swapBlock->save() && $block->save();
            }
        }

        return false;
    }

    public static function lowerPriority($blockID)
    {
        if ($block = self::findOne($blockID)) {
            if ($swapBlock = self::findOne(['article_id' => $block->article_id, 'priority' => $block->priority + 1])) {
                --$swapBlock->priority;
                ++$block->priority;

                return $swapBlock->save() && $block->save();
            }
        }

        return false;
    }
}