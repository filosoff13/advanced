<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 28.03.19
 * Time: 11:12
 */

namespace backend\controllers;

use common\models\Article;
use yii\filters\AccessControl;
use yii\web\Controller;

class ArticleController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'new',
                            'edit',
                            'compose',
                            'set-active',
                            'set-inactive',
                            'raise-priority',
                            'lower-priority',
                            'delete',
                            'tags',
                            'categories',
                            'ajax',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return ['error' => ['class' => 'yii\web\ErrorAction']];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'articles' => Article::find()->orderBy(['priority' => SORT_DESC])->asArray()->all(),
        ]);
    }

    public function actionNew()
    {
        return $this->redirect(['edit', 'id' => Article::createArticle()]);
    }
}