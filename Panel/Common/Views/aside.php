<?php

use Core\Panel\Authorization\Authorization;

function generateMenu($tree)
{
    echo '<ul>';
    foreach ($tree as $element) {
//        if (!empty($element->permission->group) && !empty($element->permission->name)) {
//            if (!Authorization::getUserData()->permissions->can($element->permission->group, $element->permission->name))
//                continue;
//        }
        echo '<li>';
        $title=$element->title;
        if(isset($element->titleKey)){
            $title=t($element->titleKey);
        }
        if (isset($element->link))
            echo '<a href="'.htmlspecialchars($element->link).'">'.htmlspecialchars($title).'</a>';
        else
            echo '<span>'.htmlspecialchars($title).'</span>';
        if (isset($element->menu))
            generateMenu($element->menu);
        echo '</li>';
    }
    echo '</ul>';
}

?>
<nav>
    <?= generateMenu($data['menu']); ?>
</nav>
