<?php
use yii\helpers\Url;
$controller = Yii::$app->controller->id;
?>
<ul class="nav nav-pills nav-stacked">
    <?php foreach($menus as $menu):?>
        <li role="presentation" <?= $controller == $menu['controller'] ? 'class="active"' : ''?>><a href="<?= $menu['url']?>"><?= $menu['text']?></a></li>

    <?php endforeach;?>
</ul>