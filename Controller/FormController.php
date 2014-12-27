<?php

namespace Asapo\Bundle\Sulu\FormBundle\Controller;

use Asapo\Bundle\Sulu\FormBundle\Entity\FormEntry;
use Asapo\Bundle\Sulu\FormBundle\Event\FormEventArgs;
use Asapo\Bundle\Sulu\FormBundle\Event\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Form controller to render form and handle form data
 */
class FormController extends Controller
{
    /**
     * Renders and handles forms
     * @param string $formName
     * @param array $content
     * @param array $view
     * @return Response
     */
    public function renderAction($formName, $content, $view)
    {
        // get parameter from container
        $requestStack = $this->get('request_stack');
        $eventDispatcher = $this->get('event_dispatcher');
        $formsConfig = $this->container->getParameter('asapo_sulu_form.forms')[$formName];

        // init request and response
        $request = $requestStack->getMasterRequest();
        $response = new Response();

        // entity and form init
        $entity = $this->createEntity($formsConfig['entity']);
        $formType = $this->get($formsConfig['form_type']);
        $form = $this->createForm($formType, $entity);

        // handle request
        $form->handleRequest($request);

        // validate request
        if ($form->isValid()) {
            // set default vars
            $entity->setCreated(new \DateTime());
            $entity->setModified(new \DateTime());
            $entity->setClientIp($request->getClientIp());

            // dispatch events
            $eventArgs = new FormEventArgs($entity);
            $eventDispatcher->dispatch(FormEvents::CONTACT_FORM_SUCCESS, $eventArgs);
            $eventDispatcher->dispatch(FormEvents::getSuccessEventName($formName), $eventArgs);

            return $this->render(
                $formsConfig['templates']['success'],
                array(
                    'content' => $content,
                    'view' => $view
                ),
                $response
            );
        }

        return $this->render(
            $formsConfig['templates']['form'],
            array(
                'form' => $form->createView(),
                'content' => $content,
                'view' => $view
            ),
            $response
        );
    }

    /**
     * Return instance of given entity-name
     * @param string $entityName
     * @return FormEntry
     */
    private function createEntity($entityName)
    {
        $em = $this->getEntityManager();
        $entityInfo = $em->getClassMetadata($entityName);

        return new $entityInfo->name;
    }

    /**
     * @return EntityManagerInterface
     */
    private function getEntityManager()
    {
        return $this->get('doctrine')->getManager();
    }
}
