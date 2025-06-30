<?php
// src/Controller/InscripcionController.php

namespace App\Controller;

use App\Repository\ActividadRepository;
use App\Repository\EventoRepository;
use App\Repository\ReservaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscripcionController extends AbstractController
{
    #[Route('/inscripcion', name: 'inscripcion')]
    public function inscripcion(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository, ActividadRepository $actividadRepository, ReservaRepository $reservaRepository): Response
    {
        $auth = false;
        $evento = null;
        $success = false;
        $error = [];
        
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
            return $this->render('event/inscripcion_event.html.twig', ['auth' => $auth, 'evento' => $evento, 'errores' => $error, 'success' => $success]);
        }

        $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
        if($user->getRol() == 'promotor')
        {
            $authPromotor = true;
        }

        if ($request->isMethod('POST')) 
        {
            $data = $request->request;
            $requiredFields = ['nombre', 'edad', 'dni'];
            foreach ($requiredFields as $field) 
            {
                if (empty($data->get($field))) 
                {
                    $error[] = "El campo $field es obligatorio.";
                }
            }
            if($data->get('edad') < 3)
            {
                $error[] = "Los asistentes deberán tener 3 años como mínimo";
            }
            if (!preg_match('/^\d{8}[a-zA-Z]$/', $data->get('dni'))) 
            {
                $error[] = 'El dni introducido no es válido.';
            }
            if (empty($error)) 
            {
                $evento = $eventoRepository->searchEvent($request->getSession()->get('reserva'));
                if(!empty($evento) && $evento->getPrecio()<= $user->getSaldo())
                {
                    $usuarioRepository->actualizarSaldo($user, $user->getSaldo()-$evento->getPrecio());
                    $success = $reservaRepository->createReserva($user, $evento, $data->get('dni'), $data->get('nombre'), $data->get('edad'), true);
                }
                else
                {
                    $reservaRepository->createReserva($user, $evento, $data->get('dni'), $data->get('nombre'), $data->get('edad'), false, true);
                }
            }
        }

        return $this->render('event/inscripcion_event.html.twig', ['auth' => $auth, 'evento' => $evento, 'errores' => $error, 'success' => $success]);
    }
}
