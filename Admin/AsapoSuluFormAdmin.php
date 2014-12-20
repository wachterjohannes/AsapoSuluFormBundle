<?php

namespace Asapo\Bundle\Sulu\FormBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;

class AsapoSuluFormAdmin extends Admin
{
    public function __construct($title)
    {
        $rootNavigationItem = new NavigationItem($title);

        $section = new NavigationItem('');
        $dropDown = new NavigationItem('asapo.form.navigation.section');
        $dropDown->setIcon('mail-forward');
        $section->addChild($dropDown);

        $contactItem = new NavigationItem('Kontakt');
        $contactItem->setAction('intern/contact');
        $dropDown->addChild($contactItem);

        $rootNavigationItem->addChild($section);

        $this->setNavigation(new Navigation($rootNavigationItem));
    }
}
