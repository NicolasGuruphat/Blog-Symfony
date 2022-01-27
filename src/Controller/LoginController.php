<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Login;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request): Response
    {
        // Creating a login object
        $login = new Login();

        $form = $this->createForm(LoginType::class, $login);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $login = $form->getData();
            $password_check = $this->getLogin($login->getUsername())->getPassword();

            if ($password_check == hash('sha512', $login->getPassword())) {
                return $this->redirectToRoute('category');
            }
            // return $this->redirectToRoute('succes');
        }

        // Getting the user from the database


        return $this->render('login/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tamerelachienne", name="succes")
     */
    public function succes(): Response
    {
        return $this->render('base.html.twig', []);
    }

    public function getLogin($username): Login
    {
        $loginRepository = $this->getDoctrine()->getRepository(Login::class);

        $login = $loginRepository->findOneBy(['username' => $username]);

        if (!$login) {
            throw $this->createNotFoundException(
                "No user with username " . $username . " found."
            );
        } else {
            return $login;
        }
    }
}
