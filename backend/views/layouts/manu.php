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

    <li <?= Yii::$app->requestedRoute == 'article/index' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['article/index']); ?>">
            <em class="fa fa-wordpress"></em>
            Блог
        </a>
    </li>

    <li <?= Yii::$app->requestedRoute == 'site/feedback' ? 'class="active"' : ''; ?>>
        <a href="<?= Url::toRoute(['testimonial/index']); ?>">
            <em class="fa fa-terminal"></em>
            Отзывы
        </a>
    </li>

</ul>