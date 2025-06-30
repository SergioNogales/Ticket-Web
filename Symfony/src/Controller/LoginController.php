<?php
// src/Controller/LoginController.php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UsuarioRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ServicioContraseña;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, UsuarioRepository $usuarioRepository, ServicioContraseña $servicioContraseña): Response
    {
        $error = [];
        $success = false;

        if ($request->isMethod('POST')) 
        {
            $data = $request->request;
            $requiredFields = ['email', 'contraseña'];
            foreach ($requiredFields as $field) 
            {
                if (empty($data->get($field))) 
                {
                    $error[] = "El campo $field es obligatorio.";
                }
            }

            if(empty($error) && !preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9].+[a-z]$/', $data->get('email')))
            {
                $error[] = 'El formato del email no es válido.';
            }

            if(empty($error))
            {
                $usuario = $usuarioRepository->searchUser($data->get('email'));
                if (empty($usuario)) 
                {
                    $error[] = 'No se ha encontrado ningún usuario con ese email.';
                }
                else
                {
                    if($usuarioRepository->validUser($data->get('email'), $data->get('contraseña')))
                    {
                        $session = $request->getSession();
                        $session->set('authenticated', 'authcode-'.\time());
                        $session->set('user', $usuario->getEmail());
                        if($usuario->getRol() == 'admin')
                        {
                            $session->set('admin', true);
                        }
                        return $this->render('login/login.html.twig', ['error' => $error, 'success' => true]);
                    }
                    else
                    {
                        $error[] = 'Contraseña incorrecta.';
                    }
                }
            }

            if(!empty($error))
            {
                return $this->render('login/login.html.twig', ['error' => $error, 'success' => false]);
            }
        }

        return $this->render('login/login.html.twig', ['error' => $error, 'success' => $success]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $sesion = $request->getSession();
        $sesion->remove('authenticated');
        $sesion->remove('user');
        $sesion->remove('admin');
        $sesion->invalidate();
        return $this->redirectToRoute('login');
    }
}