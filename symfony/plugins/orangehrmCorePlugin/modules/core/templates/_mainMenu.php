<?php

function getSubMenuIndication($menuItem) {
    
    if (count($menuItem['subMenuItems']) > 0) {
        return ' class="arrow"';
    } else {
        return '';
    }
    
}

function getListItemClass($menuItem, $currentItemDetails) {
    
    $flag = false;
    
    if ($menuItem['level'] == 1 && $menuItem['id'] == $currentItemDetails['level1']) {
        return ' class="current"';
    } elseif ($menuItem['level'] == 2 && $menuItem['id'] == $currentItemDetails['level2']) {
        return ' class="selected"';
    }
    
    return '';
    
}

function getMenuUrl($menuItem) {
    
    $url = '#';
    
    if (!empty($menuItem['module']) && !empty($menuItem['action'])) {
        $url = url_for($menuItem['module'] . '/'. $menuItem['action']);
        $url = empty($menuItem['urlExtras'])?$url:$url . $menuItem['urlExtras'];
    }
    
    return $url;
    
}

function getHtmlId($menuItem) {
    
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


<ul class="dl-menu">

    <?php foreach ($menuItemArray as $firstLevelItem) : ?>
        <li<?php echo getListItemClass($firstLevelItem, $currentItemDetails); ?>>
            <?php $i = 1;?>
            <?php if(getMenuUrl($firstLevelItem)==="#"){ ?>
                <?php foreach ($firstLevelItem['subMenuItems'] as $secondLevelItem) : ?>
                    <?php if(getMenuUrl($secondLevelItem)==="#"){ ?>
                        <?php foreach ($secondLevelItem['subMenuItems'] as $thirdLevelItem) : ?>
                            <?php if($i==1): ?>
                                <a href="<?php echo getMenuUrl($thirdLevelItem); ?>" id="<?php echo getHtmlId($firstLevelItem); ?>" class="firstLevelMenu">
                                    <b><?php echo __($firstLevelItem['menuTitle']) ?></b>
                                </a>
                                <?php $i++ ?>
                            <?php  endif ?>
                        <?php endforeach ?>
                    <?php } ?>
                <?php endforeach; ?>
            <?php }else{ ?>
                <a href="<?php echo getMenuUrl($firstLevelItem); ?>" id="<?php echo getHtmlId($firstLevelItem); ?>" class="firstLevelMenu">
                    <b><?php echo __($firstLevelItem['menuTitle']) ?></b>
                </a>
            <?php } ?>
        </li>

    <?php endforeach; ?>

</ul> <!-- first level -->