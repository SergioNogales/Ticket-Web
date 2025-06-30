<?php
// src/Controller/CreateEventController.php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\EventoRepository;
use App\Repository\UsuarioRepository;
use App\Service\ServicioContraseña;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateEventController extends AbstractController
{
    #[Route('/createevent', name: 'createevent')]
    public function createEvent(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository): Response
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
                $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
                if($user->getRol() == 'promotor')
                {
                    $auth = true;
                }
            }
        }

        if($auth == false)
        {
            return $this->render('event/create_event.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success]);
        }

        if ($request->isMethod('POST')) 
        {
            $data = $request->request;
            $requiredFields = ['nombre', 'ubicacion', 'descripcion', 'precio', 'numEntrada', 'tipo', 'fInicio', 'fFin'];
            foreach ($requiredFields as $field) 
            {
                if (empty($data->get($field))) 
                {
                    $error[] = "El campo $field es obligatorio.";
                }
            }
            if (empty($error)) 
            {
                if ($data->get('numEntrada') <= 0) 
                {
                    $error[] = 'El número de entradas debe ser mayor que 0.';
                }
                if ($data->get('tipo') != 'cine' && $data->get('tipo') != 'exposicion' && $data->get('tipo') != 'prueba deportiva' && $data->get('tipo') != 'concierto') 
                {
                    $error[] = 'El tipo debe ser concierto, exposicion, cine o prueba deportiva.';
                }
                $fInicio = new \DateTime($data->get('fInicio'));
                $fFin = new \DateTime($data->get('fFin'));
                if ($fInicio > $fFin) 
                {
                    $error[] = 'La fecha de inicio debe ser anterior o igual a la fecha de fin';
                } 
                elseif ($fInicio < new \DateTime()) 
                {
                    $error[] = 'La fecha de inicio no puede ser anterior a la fecha actual';
                } 
                elseif ($fFin < new \DateTime()) 
                {
                    $error[] = 'La fecha de fin no puede ser anterior a la fecha actual';
                }
            }


            if (empty($error)) 
            {
                $usuario = $usuarioRepository->searchUser($request->getSession()->get('user'));
                $success = $eventoRepository->createEvent($data->get('nombre'), $data->get('descripcion'),  $data->get('tipo'), (int)$data->get('numEntrada'), $data->get('precio'), $data->get('ubicacion'), $fInicio, $fFin, $usuario);            
            }
            return $this->render('event/create_event.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success]);
        }
        return $this->render('event/create_event.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success]);
    }
}
