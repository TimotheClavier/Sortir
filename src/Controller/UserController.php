<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\User;
use App\Form\ProfileFormType;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use App\Utils\UploadUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{

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
            $user->setAvatar('users/default.jpg');

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
        $upload = new UploadUtils();
        $profile = $this->getUser();


        $cities = $this->getDoctrine()
            ->getRepository(City::class)
            ->findAll();



        $form = $this->createForm(ProfileFormType::class, $profile, array(
            'profile' => $profile,
            'cities' => $cities
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile->setNom($form->get("nom")->getData());
            $profile->setPrenom($form->get("prenom")->getData());
            $profile->setEmail($form->get("email")->getData());
            $profile->setTelephone($form->get("telephone")->getData());

            $profile->setPassword(
                $passwordEncoder->encodePassword(
                    $profile,
                    $form->get('password')->getData()
                )
            );
            $img = $form['avatar']->getData();

            if($img)
            {
                $fileName = $upload->uploadUserPicture($img,$this->getParameter('users_pictures'),$profile->getNom().'_'.$profile->getPrenom());
                $profile->setAvatar('users/'.$fileName);
            }


            $city = new City();
            $city = $this->getDoctrine()
                ->getRepository(City::class)
                ->find($form->get("city")->getData());

            $profile->setCity($city);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');

        }

        return $this->render('pages/profile.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}
