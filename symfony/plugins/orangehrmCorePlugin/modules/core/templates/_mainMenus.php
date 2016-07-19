<?php

function getSubMenusIndication($menuItem) {

    if (count($menuItem['subMenuItems']) > 0) {
        return ' class="arrow"';
    } else {
        return '';
    }

}

function getListsItemClass($menuItem, $currentItemDetails) {

    $flag = false;

    if ($menuItem['level'] == 1 && $menuItem['id'] == $currentItemDetails['level1']) {
        return ' class="current"';
    } elseif ($menuItem['level'] == 2 && $menuItem['id'] == $currentItemDetails['level2']) {
        return ' class="selected"';
    }

    return '';

}

function getMenusUrl($menuItem) {

    $url = '#';

    if (!empty($menuItem['module']) && !empty($menuItem['action'])) {
        $url = url_for($menuItem['module'] . '/'. $menuItem['action']);
        $url = empty($menuItem['urlExtras'])?$url:$url . $menuItem['urlExtras'];
    }

    return $url;

}

function getHtmslId($menuItem) {

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







<ul class="cd-accordion-menu animated">
    <?php foreach ($menuItemArray as $firstLevelItem) : ?>
        <li class="has-children" <?php echo getListsItemClass($firstLevelItem, $currentItemDetails); ?>>
            <input type="checkbox" name ="group-1" id="group-1">
            <label for="group-1">
                <a href="<?php echo getMenusUrl($firstLevelItem); ?>" id="<?php echo getHtmslId($firstLevelItem); ?>" class="firstLevelMenu">
                    <?php echo __($firstLevelItem['menuTitle']) ?>
                </a>
            </label>

            <ul>
                <?php if (count($firstLevelItem['subMenuItems']) > 0) : ?>
                    <?php foreach ($firstLevelItem['subMenuItems'] as $secondLevelItem) : ?>
                        <li class="has-children">
                            <input type="checkbox" name ="sub-group-1" id="sub-group-1">
                            <label for="sub-group-1">
                                <a href="<?php echo getMenusUrl($secondLevelItem); ?>" id="<?php echo getHtmslId($secondLevelItem); ?>"<?php echo getSubMenusIndication($secondLevelItem); ?>>
                                    <?php echo __($secondLevelItem['menuTitle']) ?>
                                </a>
                            </label>
                            <?php if (count($secondLevelItem['subMenuItems']) > 0) : ?>
                                <ul>
                                    <?php foreach ($secondLevelItem['subMenuItems'] as $thirdLevelItem) : ?>

                                        <li>
                                            <a href="<?php echo getMenusUrl($thirdLevelItem); ?>" id="<?php echo getHtmslId($thirdLevelItem); ?>">
                                                <?php echo __($thirdLevelItem['menuTitle']) ?>
                                            </a>
                                        </li>

                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else:
                    // Empty li to add an orange bar and maintain uniform look.
                    ?>
                    <li></li>
                <?php endif; ?>
            </ul>
        </li>

    <?php endforeach; ?>
</ul> <!-- cd-accordion-menu -->