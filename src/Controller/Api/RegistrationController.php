<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Form\RegistrationRequestType;
use App\Model\Request\RegistrationRequest;
use App\Security\UserRegisterer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route(
     *     "/registration",
     *     name="api_user_registration",
     *     methods={"POST"}
     * )
     * @param Request        $request
     * @param UserRegisterer $registerer
     *
     * @return FormInterface|null
     */
    public function register(Request $request, UserRegisterer $registerer)
    {
        $registrationRequest = new RegistrationRequest();

        $form = $this->createForm(RegistrationRequestType::class, $registrationRequest);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            //TODO throw exception
            return $form;
        }

        $registerer->register($registrationRequest);

        return null;
    }

    /**
     * @Route(
     *     "/registration/email",
     *      name="api_user_registration_confirm"
     * )
     */
    public function confirmRegistration(Request $request): Response
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//
//        // validate email confirmation link, sets User::isVerified=true and persists
//        try {
//            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
//        } catch (VerifyEmailExceptionInterface $exception) {
//            $this->addFlash('verify_email_error', $exception->getReason());
//
//            return $this->redirectToRoute('app_register');
//        }
//
//        // @TODO Change the redirect on success and handle or remove the flash message in your templates
//        $this->addFlash('success', 'Your email address has been verified.');
//
//        return $this->redirectToRoute('app_register');
    }
}
