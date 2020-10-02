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


//        $paginatedList = $programRepository->findByQuery($query);

//        return $viewFactory->createPaginatedListView($paginatedList);

//        $user = new User();
//        $form = $this->createForm(RegistrationFormType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // encode the plain password
//            $user->setPassword(
//                $passwordEncoder->encodePassword(
//                    $user,
//                    $form->get('plainPassword')->getData()
//                )
//            );
//
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            // generate a signed url and email it to the user
//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('robot@onlineCoure-domain.com', 'onlineCource robot'))
//                    ->to($user->getEmail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
//            // do anything else you need here, like send an email
//
//            return $this->redirectToRoute('_preview_error');
//        }
//
//        return $this->render('registration/register.html.twig', [
//            'registrationForm' => $form->createView(),
//        ]);
    }

    /**
     * @Route(
     *     "/registration/email",
     *      name="api_user_registration_confirm"
     * )
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
