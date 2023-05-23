<?php

namespace MatomoManager\Controller\Admin;

use MatomoManager\MatomoManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Template\ParserContext;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Form\FormInterface;
use Thelia\Log\Tlog;

/**
 * @Route("/admin/module/MatomoManager/config", name="matomomanager_configuration")
 */
class ConfigurationController extends BaseAdminController
{
    /**
     * @Route("/{action}/save", name="_action_save", methods="POST")
     */
    public function saveAction(
        Request       $request,
        ParserContext $parser,
        string        $action
    ): JsonResponse|Response
    {
        $formClass = ucwords(str_replace('-', ' ', $action));
        $formClass = ucwords(str_replace('_', ' ', $formClass));
        $formClass = str_replace(' ', '', $formClass);
        $formClass = '\\MatomoManager\\Form\\' . $formClass;

        if (!class_exists($formClass)) {
            return $this->pageNotFound();
        }

        /** @var FormInterface $formClass */
        $actionForm = $this->createForm($formClass::getName());

        try {

            $form = $this->validateForm($actionForm, 'POST');
            $data = $form->getData();

            $parameterNames = array_keys($data);
            $parameters = array_intersect($parameterNames, MatomoManager::CONFIGURATION_PARAMETERS);

            foreach ($parameters as $parameter) {
                MatomoManager::setConfigValue($parameter, $data[$parameter]);
            }

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse($data);
            }

            return $this->generateSuccessRedirect($actionForm);

        } catch (FormValidationException $exception) {
            $error_message = $exception->getMessage();

            Tlog::getInstance()->error($error_message);

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => $error_message]);
            }

            $actionForm
                ->setErrorMessage($error_message);

            $parser
                ->addForm($actionForm)
                ->setGeneralError($error_message);

            return $this->generateErrorRedirect($actionForm);
        }
    }
}