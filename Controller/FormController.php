<?php

namespace Asapo\Bundle\Sulu\FormBundle\Controller;

use Asapo\Bundle\Sulu\FormBundle\Event\FormEventArgs;
use Asapo\Bundle\Sulu\FormBundle\Event\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * Form controller to render form and handle form data
 */
class FormController extends Controller
{
    public function renderAction($name, $success)
    {
        $requestStack = $this->get('request_stack');
        $eventDispatcher = $this->get('event_dispatcher');

        $formsConfig = $this->container->getParameter('asapo_sulu_form.forms')[$name];

        $request = $requestStack->getMasterRequest();
        $response = new Response();

        // entity and form init
        $entity = $this->createEntity($formsConfig['entity']);
        $formType = $this->get($formsConfig['type']);
        $form = $this->createForm($formType, $entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setCreated(new \DateTime());

            $eventArgs = new FormEventArgs($entity);
            $eventDispatcher->dispatch(FormEvents::CONTACT_FORM_SUCCESS, $eventArgs);
            $eventDispatcher->dispatch(FormEvents::getSuccessEventName($name), $eventArgs);

            return $this->render(
                $formsConfig['success_template'],
                array(
                    'success' => $success
                ),
                $response
            );
        }

        return $this->render(
            $formsConfig['form_template'],
            array(
                'form' => $form->createView()
            ),
            $response
        );
    }

    private function createEntity($entityName)
    {
        $em = $this->getEntityManager();
        $entityInfo = $em->getClassMetadata($entityName);

        return new $entityInfo->getName();
    }

    private function getEntityManager()
    {
        return $this->get('doctrine')->getManager();
    }
}
