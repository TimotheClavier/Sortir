<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\User;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Utils\UploadUtils;
use Swift_Mailer;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends Controller
{

    /**
     * @Route("/utilisateurs/index", name="user_index")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param UserAuthenticator $authenticator
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $cities = $this->getDoctrine()->getRepository(City::class)->findAll();
        $form = $this->createForm(RegistrationFormType::class, $user,['cities' => $cities]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setActive(true);
            $user->setAvatar('img/users/default.jpg');
            $user->setRoles(['ROLE_USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/profile", name="app_profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function profile(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createUserForm($user, $request);
        dump($user);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_profile');
        } else {
            return $this->render('pages/profile.html.twig', [
                'profileForm' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/utilisateurs/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createUserForm($user, $request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('user_index');
        } else {
            return $this->render('pages/profile.html.twig', [
                'profileForm' => $form->createView(),
            ]);
        }
    }

    public function createUserForm(User $user, Request $request)
    {
        $upload = new UploadUtils();

        $cities = $this->getDoctrine()
            ->getRepository(City::class)
            ->findAll();

        $form = $this->createForm(ProfileFormType::class, $user, array(
            'profile' => $user,
            'cities' => $cities
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form['avatar']->getData();

            if($img)
            {
                $fileName = $upload->uploadUserPicture($img,$this->getParameter('users_pictures'),$user->getNom().'_'.$user->getPrenom());
                $user->setAvatar('img/users/'.$fileName);
            }

            $city = $this->getDoctrine()
                ->getRepository(City::class)
                ->find($form->get("city")->getData());

            $user->setCity($city);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('Success','Modifications enregistrées !');

            return $form;
        }


        return $form;
    }


    /**
     * @Route("/profile/{id}", name="user_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/utilisateurs/delete/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function delete(Request $request, User $user)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('Success', 'Modifications enregistrées !');
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/utilisateurs/lock/{id}", name="user_lock")
     * @param User $user
     * @return Response
     */
    public function lock(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user->setActive(!$user->getActive());
        $entityManager->flush();
        $this->addFlash('Success', 'Modifications enregistrées !');

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder,Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('Warning', 'Email Inconnu');
                return $this->redirectToRoute('Index');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('Warning', $e->getMessage());
                return $this->redirectToRoute('Index');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $body = " voici le token pour reseter votre mot de passe : <a href=" . $url."> ici </a>";

            $message = (new \Swift_Message('[ENI-SORTIR]Mot de passe oublié'))
                ->setFrom('sortir.eni@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($body, 'text/html');
            $mailer->send($message);

            $this->addFlash('Succes', 'Mail envoyé');

//            return $this->render('user/email.html.twig', [
//                'email' =>$body,
//            ]);
            $this->redirectToRoute('Index');
        }

        return $this->render('user/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('Failure', 'Token Inconnu');
                return $this->redirectToRoute('Index');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('Index');
        }else {

            return $this->render('user/reset_password.html.twig', ['token' => $token]);
        }

    }



}
