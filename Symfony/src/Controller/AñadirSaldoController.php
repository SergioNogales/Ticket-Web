<?php
// src/Controller/AñadirSaldoController.php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use App\Service\ServicioContraseña;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class AñadirSaldoController extends AbstractController
{
    #[Route('/añadirsaldo', name: 'añadirsaldo')]
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
            return $this->render('user/añadir_saldo.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success]);
        }

        $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
        $ccv = (integer) $servicioContraseña->descifrar($user->getCcv(), 'ccv');
        $numTarjeta = $servicioContraseña->descifrar($user->getNumTarjeta(), 'tarjeta');

        if ($request->isMethod('POST')) 
        {
            $data = $request->request;
            $requiredFields = ['tarjeta', 'fecha', 'ccv', 'saldo'];
            foreach ($requiredFields as $field) 
            {
                if (empty($data->get($field))) 
                {
                    $error[] = "El campo $field es obligatorio.";
                }
            }

            $fecha = new \DateTime($data->get('fecha'));

            if($fecha < new DateTime)
            {
                $error[] = "La tarjeta de crédito introducida no es válida, está caducada";
            }
            if (!preg_match('/^[0-9]{3}$/', $data->get('ccv'))) 
            {
                $error[] = 'El CCV introducido no es válido.';
            }
            if (!preg_match('/^[0-9]{16}$/', $data->get('tarjeta'))) 
            {
                $error[] = 'La tarjeta de crédito introducida no es válida.';
            }
            if (0>=$data->get('saldo')) 
            {
                $error[] = 'La cantidad a añadir debe ser positiva.';
            }

            if (empty($error)) 
            {
                $usuario = $usuarioRepository->searchUser($request->getSession()->get('user'));
                $success = $usuarioRepository->actualizarSaldo($usuario, $usuario->getSaldo() + $data->get('saldo'));
            }
            return $this->render('user/añadir_saldo.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'tarjeta' => $numTarjeta, 'fecha' => $user->getFechaCaducidad(), 'ccv' => $ccv, 'saldo' => $user->getSaldo()]);
        }

        return $this->render('user/añadir_saldo.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'tarjeta' => $numTarjeta, 'fecha' => $user->getFechaCaducidad(), 'ccv' => $ccv, 'saldo' => $user->getSaldo()]);
    }
}
