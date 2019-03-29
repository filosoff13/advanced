<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 28.03.19
 * Time: 11:03
 */

use common\models\Article;
use common\models\Content;

$this->title = 'Новости';

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <a href="/article/new" class="btn btn-primary btn-xs pull-right">
            <span class="glyphicon glyphicon-plus"></span>
            Добавить новость
        </a>
        <h4 class="panel-title">
            <span class="glyphicon glyphicon-cog"></span>
            Новости
        </h4>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-condensed table-hover panel-body">
            <thead>
            <tr>
                <th>Название</th>
                <th>Приоритет</th>
                <th>Главная</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td>
                        <?= Content::get(Content::TYPE_ARTICLE_NAME, $article['id']); ?>
                    </td>
                    <td style="width: 120px;">
                        <span class="label label-primary pull-right">
                            #<?= $article['priority']; ?>
                        </span>
                    </td>
                                        <td style="width: 130px; text-align: center;">
                                            <?php if (Article::VIEW_ON_MAIN == $article['view_on_main']): ?>
                                                <span class="glyphicon glyphicon-ok"
                                                      style="color: green;"></span>
                                            <?php elseif (Article::NOT_VIEW == $article['view_on_main']): ?>
                                                <span class="glyphicon glyphicon-remove"
                                                      style="color: red;"></span>
                                            <?php endif; ?>
                                        </td>
                    <td style="width: 140px;">
                        <?php if (Article::STATUS_ACTIVE == $article['status']): ?>
                            <span class="glyphicon glyphicon-ok"
                                  style="color: green;"></span>
                            Опубликовано
                        <?php elseif (Article::STATUS_DRAFT == $article['status']): ?>
                            <span class="glyphicon glyphicon-remove"
                                  style="color: red;"></span>
                            Черновик
                        <?php endif; ?>
                    </td>
                    <td style="width: 220px; padding: 0;">
                        <div class="btn-group">
                            <?php if (Article::STATUS_DRAFT == $article['status']): ?>
                                <a href="/article/set-active/<?= $article['id']; ?>"
                                   class="btn btn-sm btn-default">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </a>
                            <?php elseif (Article::STATUS_ACTIVE == $article['status']): ?>
                                <a href="/article/set-inactive/<?= $article['id']; ?>"
                                   class="btn btn-sm btn-default">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            <?php endif; ?>
                            <a href="/article/raise-priority/<?= $article['id']; ?>"
                               class="btn btn-sm btn-default">
                                <span class="glyphicon glyphicon-arrow-up"
                                      style="color: green;"></span>
                            </a>
                            <a href="/article/lower-priority/<?= $article['id']; ?>"
                               class="btn btn-sm btn-default">
                                <span class="glyphicon glyphicon-arrow-down"
                                      style="color: red;"></span>
                            </a>
                            <a href="/article/edit/<?= $article['id']; ?>"
                               class="btn btn-sm btn-warning">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>
                            <a href="/article/compose/<?= $article['id']; ?>"
                               class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a href="/article/delete/<?= $article['id']; ?>"
                               class="btn btn-sm btn-danger">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>