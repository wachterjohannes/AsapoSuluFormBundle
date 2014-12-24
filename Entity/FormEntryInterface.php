<?php

namespace Asapo\Bundle\Sulu\FormBundle\Entity;

/**
 * Interface for form-entries
 */
interface FormEntryInterface
{
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return FormEntry
     */
    public function setCreated($created);

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated();

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return FormEntry
     */
    public function setModified($modified);

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified();
}
