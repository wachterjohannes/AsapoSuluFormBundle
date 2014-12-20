<?php

namespace Asapo\Bundle\Sulu\FormBundle\Event;

use Asapo\Bundle\Sulu\FormBundle\Entity\FormEntry;
use Symfony\Component\EventDispatcher\Event;

class FormEventArgs extends Event
{
    /**
     * @var FormEntry
     */
    private $entity;

    function __construct(FormEntry $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return FormEntry
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
