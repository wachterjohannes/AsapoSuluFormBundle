<?php

namespace Asapo\Bundle\Sulu\FormBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;
use Sulu\Component\Security\UserInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AsapoSuluFormAdmin extends Admin
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    public function __construct($title, $forms, SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;

        $rootNavigationItem = new NavigationItem($title);

        $section = new NavigationItem('');
        $dropDown = new NavigationItem('asapo.form.navigation.section');
        $dropDown->setIcon('mail-forward');
        $section->addChild($dropDown);

        $user = $this->getUser();
        $userLocale = $user->getLocale();

        foreach ($forms as $formName => $form) {
            if (array_key_exists('backend', $form)) {
                $item = $this->getItem($formName, $form, $userLocale);
                $dropDown->addChild($item);
            }
        }

        $rootNavigationItem->addChild($section);
        $this->setNavigation(new Navigation($rootNavigationItem));
    }

    private function getItem($formName, $form, $locale)
    {
        $title = ucfirst($formName);
        if (array_key_exists('title', $form['backend']) && array_key_exists($locale, $form['backend']['title'])) {
            $title = $form['backend']['title'][$locale];
        }

        $item = new NavigationItem($title);
        $item->setAction('form/' . $formName);

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function getJsBundleName()
    {
        return 'asaposuluform';
    }

    /**
     * Get a user from the Security Context
     * @return UserInterface
     */
    private function getUser()
    {
        if (null === ($token = $this->securityContext->getToken())) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

}
