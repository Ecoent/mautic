<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<?php if ($item->hasChildren() && $options["depth"] !== 0 && $item->getDisplayChildren()): ?>

<?php if ($isRoot = ($item->isRoot())): ?>
<ul class="nav nav-pills navbar-left admin-menu" role="navigation">
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <?php echo $view['translator']->trans('mautic.core.menu.admin'); ?><i class="fa fa-lg fa-fw fa-angle-double-down"></i>
        </a>

        <ul class="dropdown-menu pull-right">
<?php else: ?>
        <ul<?php echo $view["menu_helper"]->parseAttributes($item->getChildrenAttributes()); //convert array to name="value" ?>>
<?php endif; ?>
            <?php foreach ($item->getChildren() as $child):
            if (!$child->isDisplayed()) continue; ?>
            <?php $view["menu_helper"]->buildClasses($child, $matcher, $options); //builds the class attributes based on options ?>
            <li<?php echo $view["menu_helper"]->parseAttributes($child->getAttributes()); ?>>

                <?php if ($showAsLink = ($child->getUri() && (!$matcher->isCurrent($child) || $options["currentAsLink"]))): ?>
                <a href="<?php echo $child->getUri(); ?>"<?php echo $view["menu_helper"]->parseAttributes($child->getLinkAttributes()); ?>>
                    <?php endif; ?>

                    <?php if ($icon = ($child->getExtra("iconClass"))): ?>
                        <i class="fa fa-lg fa-fw <?php echo $icon; ?>"></i>
                    <?php endif; ?>

                    <span<?php echo $view["menu_helper"]->parseAttributes($child->getLabelAttributes()); ?>><?php
                        echo $view['translator']->trans($child->getLabel());?></span>

                    <?php if ($showAsLink): ?>
                </a>
                <?php endif; ?>

                <?php if ($child->hasChildren() && $child->getDisplayChildren()): //parse children/next level(s)
                    $options["depth"]         = ($options["depth"]) ? $options["depth"]-- : "";
                    $options["matchingDepth"] = ($options["matchingDepth"]) ? $options["matchingDepth"]-- : "";

                    //add on a level class
                    $levelClass  = $child->getChildrenAttribute("class") . " nav-level nav-level-" . $child->getLevel();
                    //note if the item has children
                    if ($isAncestor):
                        $levelClass .= ($child->hasChildren()) ? " subnav-open" : "";
                    else:
                        $levelClass .= ($child->hasChildren()) ? " subnav-closed" : "";
                    endif;
                    //set the class
                    $child->setChildrenAttribute("class", $levelClass);
                    echo $view->render('MauticCoreBundle:Menu:main.html.php',
                        array( "item"             => $child,
                               "options"          => $options,
                               "matcher"          => $matcher
                        )
                    );
                endif;
                ?>
            </li>
        <?php endforeach; ?>
        </ul>
<?php if ($isRoot): ?>
    </li>
</ul>
<?php endif; ?>
<?php endif; ?>