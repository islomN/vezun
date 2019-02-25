<nav class="menu">
    <ul class="main_menu">
        <?php $controller = Yii::$app->controller->id;?>

        <?php foreach($menus as $menu):?>
            <li <?= ("/".$controller == $menu['url']) || ($controller == "main" && $menu['url'] == "/")
                ?'class="main_menu_active_a"' : ''?>>
                <a href="<?= $menu['url']?>"><?= $menu['name']?></a>
            </li>
        <?php endforeach;?>

    </ul>
</nav>