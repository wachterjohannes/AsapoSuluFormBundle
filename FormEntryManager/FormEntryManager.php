<?php

namespace Asapo\Bundle\Sulu\FormBundle\FormEntryManager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Mapping\ClassMetadata;
use Sulu\Component\Rest\ListBuilder\AbstractFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;

class FormEntryManager
{
    /**
     * @var array
     */
    private $formsConfig;

    /**
     * @var Registry
     */
    private $registry;

    function __construct(Registry $registry, $formsConfig)
    {
        $this->registry = $registry;
        $this->formsConfig = $formsConfig;
    }

    /**
     * Returns field-descriptors for given form-name
     * TODO cache field-descriptors
     * @param string $formName
     * @return AbstractFieldDescriptor[]
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function getFieldDescriptors($formName)
    {
        $formConfig = $this->formsConfig[$formName];
        $entityName = $formConfig['entity'];

        /** @var ClassMetadata $metadata */
        $metadata = $this->registry->getManager()->getClassMetadata($entityName);

        $fieldDescriptors = array();
        foreach ($metadata->getFieldNames() as $fieldName) {
            $fieldMapping = $metadata->getFieldMapping($fieldName);
            $type = ''; // default
            if ($fieldMapping['type'] === 'datetime') {
                $type = 'date';
            }

            // TODO config of name, translation, disabled, default, type, width, minWidth, sortable and editable
            $fieldDescriptors[$fieldName] = new DoctrineFieldDescriptor(
                $fieldName,
                $fieldName,
                $entityName,
                ucfirst($fieldName),
                array(),
                false,
                false,
                $type
            );
        }

        return $fieldDescriptors;
    }

    /**
     * Returns entity-name for given form-name
     * @param string $formName
     * @return string
     */
    public function getEntityName($formName)
    {
        $formConfig = $this->formsConfig[$formName];

        return $formConfig['entity'];
    }
}
