<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 28.03.19
 * Time: 11:05
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<ul class="nav menu">
    <li <?= Yii::$app->requestedRoute == 'site/translations' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['site/translations']); ?>">
            <em class="fa fa-book"></em>
            Переводы
        </a>
    </li>
    <li <?= Yii::$app->requestedRoute == 'site/leads' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['site/leads']); ?>">
            <em class="fa fa-user-times"></em>
            Заявки
        </a>
    </li>
    <li <?= Yii::$app->requestedRoute == 'vacancy/index' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['vacancy/index']); ?>">
            <em class="fa fa-shopping-bag"></em>
            Вакансии
        </a>
    </li>
    <li <?= Yii::$app->requestedRoute == 'article/index' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['article/index']); ?>">
            <em class="fa fa-wordpress"></em>
            Блог
        </a>
    </li>
    <li <?= Yii::$app->controller->id == 'project' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['project/index']); ?>">
            <em class="fa fa-apple"></em>
            Проекты
        </a>
    </li>
    <li <?= Yii::$app->requestedRoute == 'contact/index' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['contact/index']); ?>">
            <em class="fa fa-phone"></em>
            Контакты
        </a>
    </li>
    <li <?= Yii::$app->requestedRoute == 'site/feedback' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['testimonial/index']); ?>">
            <em class="fa fa-terminal"></em>
            Отзывы
        </a>
    </li>
    <li <?= Yii::$app->requestedRoute == 'site/seo' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['site/seo']); ?>">
            <em class="fa fa-search"></em>
            SEO
        </a>
    </li>
    <li class="parent ">
        <a data-toggle="collapse" href="#sub-item-1">
            <em class="fa fa-navicon"></em>
            Статические страницы
            <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right">
                <em class="fa fa-plus"></em>
            </span>
        </a>
        <ul class="children collapse" id="sub-item-1">
            <li>
                <a class="<?= Yii::$app->requestedRoute == 'site/main' ? 'active' : ''; ?>" href="<?= Url::toRoute('site/main'); ?>">
                    <span class="fa fa-arrow-right"></span>
                    Главная
                </a>
            </li>
            <li>
                <a class="<?= Yii::$app->requestedRoute == 'site/vacancies' ? 'active' : ''; ?>" href="<?= Url::toRoute('site/vacancies'); ?>">
                    <span class="fa fa-arrow-right"></span>
                    Вакансии
                </a>
            </li>
        </ul>
    </li>
    <li <?= Yii::$app->requestedRoute == 'user/index' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['user/index']); ?>">
            <em class="fa fa-user"></em>
            Пользователи
        </a>
    </li>
    <li>
        <?= Html::a('<em class="fa fa-home"></em> На сайт', FRONTEND_HOSTNAME, ['target' => '_blank']); ?>
    </li>
    <li>
        <a href="<?= Url::toRoute('site/logout'); ?>">
            <em class="fa fa-power-off"></em>
            Выход
        </a>
    </li>
</ul>