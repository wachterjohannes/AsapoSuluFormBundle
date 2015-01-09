<?php

namespace Asapo\Bundle\Sulu\FormBundle\Controller;

use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\RestController;
use Symfony\Component\HttpFoundation\Request;

class FormEntryController extends RestController implements ClassResourceInterface
{
    /**
     * Returns field-descriptors for given form-name
     * @param $formName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fieldsAction($formName)
    {
        $formManager = $this->get('asapo_sulu_form.form_entry_manager');
        $fieldDescriptors = $formManager->getFieldDescriptors($formName, $this->getUser()->getLocale());

        return $this->handleView($this->view(array_values($fieldDescriptors), 200));
    }


    public function cgetAction($formName, Request $request)
    {
        if ($request->query->get('sortBy') === null) {
            $request->query->set('sortBy', 'created');
        }
        if ($request->query->get('sortOrder') === null) {
            $request->query->set('sortOrder', 'DESC');
        }

        $formManager = $this->get('asapo_sulu_form.form_entry_manager');
        $restHelper = $this->get('sulu_core.doctrine_rest_helper');
        $factory = $this->get('sulu_core.doctrine_list_builder_factory');

        $entityName = $formManager->getEntityName($formName);

        $listBuilder = $factory->create($entityName);
        $restHelper->initializeListBuilder(
            $listBuilder,
            $formManager->getFieldDescriptors($formName, $this->getUser()->getLocale())
        );

        $list = new ListRepresentation(
            $listBuilder->execute(),
            'entries',
            'get_form_entries',
            array_merge($request->query->all(), array('formName' => $formName)),
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count(),
            $formName
        );
        $view = $this->view($list, 200);

        return $this->handleView($view);
    }
}
