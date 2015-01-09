<?php

namespace Asapo\Bundle\Sulu\FormBundle\Widgets;

use Asapo\Bundle\Sulu\FormBundle\FormEntryManager\FormEntryManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Sulu\Bundle\AdminBundle\Widgets\WidgetException;
use Sulu\Bundle\AdminBundle\Widgets\WidgetInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class FormEntryWidget implements WidgetInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var FormEntryManager
     */
    private $formEntryManager;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    function __construct(
        Registry $registry,
        FormEntryManager $formEntryManager,
        SecurityContextInterface $securityContext
    ) {
        $this->registry = $registry;
        $this->formEntryManager = $formEntryManager;
        $this->securityContext = $securityContext;
    }

    /**
     * return name of widget
     * @return string
     */
    public function getName()
    {
        return 'asapo-form-entry';
    }

    /**
     * returns template name of widget
     * @return string
     */
    public function getTemplate()
    {
        // TODO adapt interface to inject params and adapt template dynamically
        return 'AsapoSuluFormBundle:Form:details.html.twig';
    }

    /**
     * returns data to render template
     * @param array $options
     * @throws WidgetException
     * @return array
     */
    public function getData($options)
    {
        /** @var EntityRepository $repository */
        $repository = $this->registry->getRepository($this->formEntryManager->getEntityName($options['formName']));

        return array(
            'entry' => $repository->findOneBy(array('id' => $options['entry'])),
            'fieldDescriptors' => $this->formEntryManager->getFieldDescriptors(
                $options['formName'],
                $this->securityContext->getToken()->getUser()->getLocale()
            )
        );
    }
}
