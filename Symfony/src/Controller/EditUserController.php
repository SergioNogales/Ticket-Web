<?php
// src/Controller/EditUserController.php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\Service\ServicioContraseña;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditUserController extends AbstractController
{
    #[Route('/edituser', name: 'edituser')]
    public function register(Request $request, UsuarioRepository $usuarioRepository, ServicioContraseña $servicioContraseña): Response
    {
        $error = [];
        $success = false;
        $auth = false;
        
        $authToken = $request->getSession()->get('authenticated');
        if(!empty($authToken))
        {
            $timestamp = (int) str_replace('authcode-', '', $authToken);
            if((time() - $timestamp) < 1200)
            {
                $auth = true;
            }
        }

        if($auth == false)
        {
            return $this->render('edit/edit_user.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success]);
        }

        $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
        $ccv = (integer) $servicioContraseña->descifrar($user->getCcv(), 'ccv');
        $numTarjeta = $servicioContraseña->descifrar($user->getNumTarjeta(), 'tarjeta');

        if ($request->isMethod('POST')) 
        {
            $data = $request->request;
            $requiredFields = ['email', 'telefono', 'nombre', 'direccion', 'localidad', 'codigoPostal', 'tarjeta', 'fecha', 'ccv', 'contraseña'];
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
                    $error[] = 'El nuevo teléfono introducido no es válido.';
                }
                if (!preg_match('/^[0-9]{3}$/', $data->get('ccv'))) 
                {
                    $error[] = 'El nuevo CCV introducido no es válido.';
                }
                if (!preg_match('/^[0-9]{16}$/', $data->get('tarjeta'))) 
                {
                    $error[] = 'La nueva tarjeta de crédito introducida no es válida.';
                }
            }

            if (empty($error)) 
            {
                $usuario = $usuarioRepository->searchUser($request->getSession()->get('user'));
                $success = $usuarioRepository->editUser($usuario->getEmail(), $data->get('email'), $data->get('telefono'), $data->get('nombre'), $data->get('direccion'), $data->get('localidad'), $data->get('codigoPostal'), $data->get('fecha'), $data->get('contraseña'), $data->get('ccv'), $data->get('tarjeta'));
            }
            return $this->render('edit/edit_user.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'email' => $user->getEmail(), 'telefono' => $user->getTelefono(), 'nombre' => $user->getNombre(), 'direccion' => $user->getDireccion(), 'localidad' => $user->getLocalidad(), 'codigoPostal' => $user->getCodigoPostal(), 'tarjeta' => $numTarjeta, 'fecha' => $user->getFechaCaducidad(), 'ccv' => $ccv]);
        }

        return $this->render('edit/edit_user.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'email' => $user->getEmail(), 'telefono' => $user->getTelefono(), 'nombre' => $user->getNombre(), 'direccion' => $user->getDireccion(), 'localidad' => $user->getLocalidad(), 'codigoPostal' => $user->getCodigoPostal(), 'tarjeta' => $numTarjeta, 'fecha' => $user->getFechaCaducidad(), 'ccv' => $ccv]);
    }
}
