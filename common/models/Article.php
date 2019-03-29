<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 28.03.19
 * Time: 10:25
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\View;

/**
 * This is the model class for table "article".
 *
 * @property int    $id
 * @property string $slug
 * @property int    $status
 * @property int    $priority
 * @property int    $view_on_main
 * @property int    $lead
 * @property int    $created_at
 * @property int    $published_at
 * @property int    $author_id
 */
class Article extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'priority', 'view_on_main', 'lead', 'created_at', 'published_at', 'author_id'], 'required'],
            [['status', 'priority', 'view_on_main', 'lead', 'created_at', 'published_at', 'author_id'], 'integer'],
            [['slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'status' => 'Status',
            'priority' => 'Priority',
            'view_on_main' => 'View On Main',
            'lead' => 'Lead',
            'created_at' => 'Created At',
            'published_at' => 'Published At',
            'author_id' => 'Author ID',
        ];
    }

    public const STATUS_DRAFT = 0;
    public const STATUS_ACTIVE = 1;

    public const NOT_VIEW = 0;
    public const VIEW_ON_MAIN = 1;

    public const SELECTED = 1;
    public const NOT_SELECTED = 0;
    public const DEFAULT_SLUG = 'article';

    public const NOT_SET = 0;

    public const LIMIT = 3; //12
    public const LIMIT_MOBILE = 1; //3
    public const LIMIT_MAIN = 2; //2

    public const LIMIT_LATEST_ARTICLES = 6;
    public const LIMIT_HERO_ARTICLES = 1;

    public static function getArticle($articleID)
    {
        if (self::findOne($articleID)) {
            return self::findOne($articleID);
        }

        return false;
    }

    public static function createArticle()
    {
        $newArticle = new self();
        $newArticle->slug = self::DEFAULT_SLUG . '-' . (self::find()->select(['MAX(id)'])->scalar() + 1);
        $newArticle->status = self::STATUS_DRAFT;
        $newArticle->priority = self::find()
                ->select(['MAX(priority)'])
                ->scalar() + 1;
        $newArticle->created_at = time();
        $newArticle->published_at = self::NOT_SET;
        $newArticle->view_on_main = self::NOT_VIEW;
        $newArticle->lead = self::NOT_SET;
        $newArticle->author_id = self::NOT_SET;

        return $newArticle->save() ? $newArticle->id : false;
    }

    public static function changePriority($articleID, $vector)
    {
        if ($article = self::findOne($articleID)) {
            if ($vector == 'raise') {
                if ($swapArticles = self::findOne(['priority' => $article->priority - 1])) {
                    ++$swapArticles->priority;
                    --$article->priority;

                    return $swapArticles->save() && $article->save();
                }
            } elseif ($vector == 'lower') {
                if ($swapArticles = self::findOne(['priority' => $article->priority + 1])) {
                    --$swapArticles->priority;
                    ++$article->priority;

                    return $swapArticles->save() && $article->save();
                }
            }
        }

        return false;
    }

    public static function deleteArticle($id)
    {
        if ($article = self::findOne($id)) {
            foreach (self::find()
                         ->where(['>', 'priority', $article->priority])
                         ->asArray()
                         ->all() as $shiftedArticle) {
                self::updateAll(['priority' => $shiftedArticle['priority'] - 1], ['id' => $shiftedArticle['id']]);
            }

            Content::remove(Content::TYPE_ARTICLE_DESCRIPTION, $id);
            Content::remove(Content::TYPE_ARTICLE_NAME, $id);

            return $article->delete();
        }

        return false;
    }

    public static function selectViewOnMainArticle($articleID)
    {
        if ($article = self::findOne($articleID)) {
            $article->view_on_main = $article->view_on_main == self::SELECTED ? self::NOT_SELECTED : self::SELECTED;

            return $article->save();
        }

        return false;
    }

    public static function selectLatestArticles($articleID)
    {
        if (self::findOne($articleID)) {
            return self::find()
                ->where([
                    'AND',
                    ['=', 'status', self::STATUS_ACTIVE],
                    ['<>', 'id', $articleID],
                ])
                ->limit(self::LIMIT_LATEST_ARTICLES)
                ->orderBy(['priority' => SORT_DESC])
                ->all();
        }

        return false;
    }

    public static function getHeroArticle()
    {
        if ($hero = self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->limit(self::LIMIT_HERO_ARTICLES)
            ->orderBy(['priority' => SORT_DESC])
            ->all()) {
            return $hero['0'];
        }
    }

    /**
     * @return array
     */
    public static function search($searchQuery): array
    {
        $articles = self::find()
            ->select(['article.id'])
            ->where(['article.status' => self::STATUS_ACTIVE])
            ->orderBy(['created_at' => SORT_DESC]);

        if ($articles->count() > self::LIMIT) {
            $searchQuery['totalPages'] = round($articles->count() / self::LIMIT, 0, PHP_ROUND_HALF_DOWN) + (($articles->count() % self::LIMIT) > 0 ? 1 : 0);
        } else {
            $searchQuery['totalPages'] = $searchQuery['currentPage'] = 1;
        }

        $searchQuery['totalCount'] = $articles->count();
        return [
            'articles' => $articles->offset(($searchQuery['currentPage'] - 1) * self::LIMIT)->limit(self::LIMIT)->column(),
            'searchQuery' => $searchQuery,
        ];
    }

    /**
     * @param $searchQuery
     * @return array
     */
    public static function searchCurrentPage($searchQuery) {
        $articles = self::find()
            ->select(['article.id'])
            ->where(['article.status' => self::STATUS_ACTIVE])
            ->orderBy(['created_at' => SORT_DESC]);

        if ($articles->count() > self::LIMIT) {
            $searchQuery['totalPages'] = round($articles->count() / self::LIMIT, 0, PHP_ROUND_HALF_DOWN) + (($articles->count() % self::LIMIT) > 0 ? 1 : 0);
        } else {
            $searchQuery['totalPages'] = $searchQuery['currentPage'] = 1;
        }

        $searchQuery['totalCount'] = $articles->count();
        return [
            'articles' => $articles->offset(($searchQuery['currentPage'] - 1) * self::LIMIT)->limit(self::LIMIT)->column(),
            'searchQuery' => $searchQuery,
        ];
    }

    /**
     * @param int $offset
     * @param int $counter
     *
     * @return array
     */
    public static function fetchArticles($offset = 0, $counter = 0): array
    {
        $items = [];
        $limit = Site::$isDesktop ? self::LIMIT : self::LIMIT_MOBILE;
        $articles = self::find()
            ->select(['id'])
            ->where([
                'AND',
                ['NOT IN', 'id', self::getHeroArticle()->id],
                ['IN', 'status', self::STATUS_ACTIVE],
            ])
            ->orderBy(['priority' => SORT_DESC]);

        foreach ($articles
                     ->offset($offset)
                     ->limit($limit)
                     ->column() as $articleID) {
            ++$counter;
            Yii::$app->view->registerJs('Article.counter = "' . $counter . '";', View::POS_END);

            $items[] = Yii::$app->view->render('/site/article-item', [
                'articleID' => $articleID,
                'article' => self::findOne($articleID),
                'counter' => $counter,
            ]);
        }
        $totalCount = $articles->count();

        return [
            'result' => true,
            'counter' => $counter,
            'items' => $items,
            'offset' => $offset + $limit,
            'count' => $totalCount,
        ];
    }

    public static function selectAllArticles()
    {
        if ($articles = self::find()
            ->where([
                'AND',
                ['=', 'status', self::STATUS_ACTIVE],
            ])
            ->limit(self::LIMIT_LATEST_ARTICLES)
            ->orderBy(['priority' => SORT_DESC])
            ->all()) {
            return $articles;
        }

        return false;
    }

    public static function selectOtherArticles($articleID)
    {
        if (self::findOne($articleID)) {
            return self::find()
                ->where([
                    'AND',
                    ['=', 'status', self::STATUS_ACTIVE],
                    ['<>', 'id', $articleID],
                ])
                ->limit(self::LIMIT_LATEST_ARTICLES)
                ->orderBy(['priority' => SORT_DESC])
                ->all();
        }

        return false;
    }

    public static function selectMainArticles()
    {
        $articles = self::find()
            ->where([
                'AND',
                ['=', 'status', self::STATUS_ACTIVE],
                ['=', 'view_on_main', self::VIEW_ON_MAIN],
            ])
            ->limit(self::LIMIT_MAIN)
            ->orderBy(['priority' => SORT_DESC])
            ->all();

        if (count($articles) == 0) {
            $articles = self::selectOtherArticles();
        }

        return count($articles) > 0 ? $articles : false;
    }
}