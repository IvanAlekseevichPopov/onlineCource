<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\ConfirmationToken;
use App\Exception\Registration\RegistrationException;
use App\Form\Request\RegistrationRequestType;
use App\Model\Request\RegistrationRequest;
use App\Security\UserRegisterer;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route(
     *     "/registration",
     *     name="api_user_registration",
     *     methods={"POST"}
     * )
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
     *     "/registration/{token}",
     *     name="api_user_registration_confirm",
     *     methods={"POST"}
     * )
     *
     * @return null
     */
    public function confirmRegistration(ConfirmationToken $token, UserRegisterer $registerer, AuthenticationSuccessHandler $authenticationSuccessHandler)
    {
        try {
            $user = $registerer->confirmRegistration($token);

            //TODO DTO instead of raw data
            return $authenticationSuccessHandler->handleAuthenticationSuccess($user);
        } catch (RegistrationException $exception) {
            throw new BadRequestException($exception);
        }
    }

    /**
     * @Route(
     *     "/user/self",
     *     name="api_user_self_info",
     *     methods={"GET"}
     * )
     *
     * @return null
     */
    public function getUserInfo()
    {
        //TODO DTO
        return $this->getUser();
    }
}
