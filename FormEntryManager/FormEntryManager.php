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
    public function getFieldDescriptors($formName, $locale)
    {
        $formConfig = $this->formsConfig[$formName];
        $entityName = $formConfig['entity'];

        /** @var ClassMetadata $metadata */
        $metadata = $this->registry->getManager()->getClassMetadata($entityName);

        $fieldDescriptors = array();
        foreach ($metadata->getFieldNames() as $fieldName) {
            $config = null;
            if (
                array_key_exists('backend', $formConfig) &&
                array_key_exists('field_descriptors', $formConfig['backend']) &&
                array_key_exists($fieldName, $formConfig['backend']['field_descriptors'])
            ) {
                $config = $formConfig['backend']['field_descriptors'][$fieldName];
            }

            if ($config['enabled'] !== false) {
                $fieldDescriptors[$fieldName] = $this->getFieldDescriptor(
                    $fieldName,
                    $metadata,
                    $entityName,
                    $locale,
                    $config
                );
            }
        }

        return $fieldDescriptors;
    }

    private function getFieldDescriptor($fieldName, ClassMetadata $metadata, $entityName, $locale, $config = null)
    {
        $fieldMapping = $metadata->getFieldMapping($fieldName);
        $type = ''; // default
        if ($fieldMapping['type'] === 'datetime') {
            $type = 'date';
        }
        $config = $this->extendConfig($config);

        // TODO config of disabled, default, type, width, minWidth, sortable and editable
        return new DoctrineFieldDescriptor(
            $fieldName,
            $fieldName,
            $entityName,
            $this->getTitle($fieldName, $locale, $config),
            array(),
            $config['disabled'],
            $config['default'],
            $config['type'] !== '' ? $config['type'] : $type,
            $config['width'],
            $config['minWidth'],
            $config['sortable'],
            $config['editable']
        );
    }

    private function extendConfig($config)
    {
        if ($config === null) {
            $config = array();
        }

        return array_merge(
            array(
                'title' => array(),
                'disabled' => false,
                'default' => false,
                'type' => '',
                'width' => '',
                'minWidth' => '',
                'sortable' => true,
                'editable' => false,
            ),
            $config
        );
    }

    private function getTitle($fieldName, $locale, $config = null)
    {
        if ($config !== null && array_key_exists($locale, $config['title'])) {
            return $config['title'][$locale];
        }

        return ucfirst($fieldName);
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
