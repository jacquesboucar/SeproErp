<?php

function getSubMenuIndications($menuItem) {

    if (count($menuItem['subMenuItems']) > 0) {
        return ' class="arrow"';
    } else {
        return '';
    }

}

function getListItemsClass($menuItem, $currentItemDetails) {

    $flag = false;

    if ($menuItem['level'] == 1 && $menuItem['id'] == $currentItemDetails['level1']) {
        return ' class="current"';
    } elseif ($menuItem['level'] == 2 && $menuItem['id'] == $currentItemDetails['level2']) {
        return ' class="selected"';
    }

    return '';

}

function getMenuUrls($menuItem) {

    $url = '#';

    if (!empty($menuItem['module']) && !empty($menuItem['action'])) {
        $url = url_for($menuItem['module'] . '/'. $menuItem['action']);
        $url = empty($menuItem['urlExtras'])?$url:$url . $menuItem['urlExtras'];
    }

    return $url;

}

function getHtmlsId($menuItem) {

    $id = '';

    if (!empty($menuItem['action'])) {
        $id = 'menu_' . $menuItem['module'] . '_' . $menuItem['action'];
    } else {

        $module             = '';
        $firstSubMenuItem   = $menuItem['subMenuItems'][0];
        $module             = $firstSubMenuItem['module'] . '_';

        $id = 'menu_' . $module . str_replace(' ', '', $menuItem['menuTitle']);

    }

    return $id;

}

?>

<div class="row">
    <?php foreach ($menuItemArray as $firstLevelItem) : ?>
        <?php $i = 1;?>
            <?php if(getMenuUrls($firstLevelItem)==="#"){ ?>
                <?php foreach ($firstLevelItem['subMenuItems'] as $secondLevelItem) : ?>
                    <?php if(getMenuUrls($secondLevelItem)==="#"){ ?>
                        <?php foreach ($secondLevelItem['subMenuItems'] as $thirdLevelItem) : ?>
                             <?php if($i==1): ?>
                            <div class="col-xs-4 col-sm-4 col-lg-4" <?php echo getListItemsClass($firstLevelItem, $currentItemDetails); ?>>
                                <a class="" href="<?php echo getMenuUrls($thirdLevelItem); ?>" id="<?php echo getHtmlsId($firstLevelItem); ?>">
                                    <div class="form-hexagone">
                                        <?php echo __($firstLevelItem['menuTitle']) ?>
                                    </div>
                                </a>
                            </div>
                                <?php $i++ ?>
                            <?php  endif ?>
                        <?php endforeach ?>
                    <?php } ?>
                <?php endforeach; ?>
            <?php }else{ ?>
                            <div class="col-xs-4 col-sm-4 col-lg-4" <?php echo getListItemsClass($firstLevelItem, $currentItemDetails); ?>>
                                <a class="" href="<?php echo getMenuUrls($firstLevelItem) ?>" id="<?php echo getHtmlsId($firstLevelItem); ?>">
                                    <div class="form-hexagone">
                                        <?php echo __($firstLevelItem['menuTitle']) ?>
                                    </div>
                                </a>
                            </div>
            <?php } ?>
    <?php endforeach; ?>
</div>