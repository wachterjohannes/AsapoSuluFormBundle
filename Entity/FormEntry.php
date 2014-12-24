<?php

namespace Asapo\Bundle\Sulu\FormBundle\Entity;

/**
 * Abstract super class for form-entries
 */
abstract class FormEntry implements FormEntryInterface
{
    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $modified;

    /**
     * {@inheritdoc}
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModified()
    {
        return $this->modified;
    }
}
