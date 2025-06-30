<?php
// src/Controller/SignInController.php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignInController extends AbstractController
{
    #[Route('/register', name: 'signin')]
    public function register(Request $request, UsuarioRepository $usuarioRepository): Response
    {
        $error = [];
        $success = false;
        

        if ($request->isMethod('POST')) 
        {
            $data = $request->request;
            $requiredFields = ['email', 'telefono', 'nombre', 'direccion', 'localidad', 'codigoPostal', 'tarjeta', 'fecha', 'ccv', 'contraseña', 'repetirContraseña'];
            foreach ($requiredFields as $field) 
            {
                if (empty($data->get($field))) 
                {
                    $error[] = "El campo $field es obligatorio.";
                }
            }
            if (empty($error)) 
            {
                if (!preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9].+[a-z]$/', $data->get('email'))) 
                {
                    $error[] = 'El email introducido no es válido.';
                }
                if (!preg_match('/^(?:\+?[0-9]{1,3})?[0-9]{9}$/', $data->get('telefono'))) 
                {
                    $error[] = 'El teléfono introducido no es válido.';
                }
                if (!preg_match('/^[0-9]{3}$/', $data->get('ccv'))) 
                {
                    $error[] = 'El CCV introducido no es válido.';
                }
                if (!preg_match('/^[0-9]{16}$/', $data->get('tarjeta'))) 
                {
                    $error[] = 'La tarjeta de crédito introducida no es válida.';
                }
            }
            if (empty($error) && $data->get('contraseña') !== $data->get('repetirContraseña')) 
            {
                $error[] = 'Las contraseñas no coinciden.';
            }
            if(empty($error))
            {
                $usuario = $usuarioRepository->searchUser($data->get('contraseña'));
                if ($usuario) 
                {
                    $error[] = 'Un usuario con ese email ya está registrado.';
                }
            }

            if(!empty($error))
            {
                return $this->render('signin/register.html.twig', ['error' => $error, 'success' => false]);
            }

            $success = $usuarioRepository->createUser($data->get('email'), $data->get('telefono'), $data->get('nombre'), $data->get('direccion'), $data->get('localidad'), $data->get('codigoPostal'), $data->get('fecha'), $data->get('contraseña'), $data->get('ccv'), $data->get('tarjeta'));
        }

        return $this->render('signin/register.html.twig', ['error' => $error, 'success' => $success]);
    }
}
