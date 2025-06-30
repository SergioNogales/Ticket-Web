<?php
// src/Controller/CreateActivityController.php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ActividadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateActivityController extends AbstractController
{
    #[Route('/createactivity', name: 'createactivity')]
    public function createActivity(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository, ActividadRepository $actividadRepository): Response
    {
        $error = [];
        $success = false;
        $auth = false;
        $evento = null;
        
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
            return $this->render('activity/create_activity.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'evento' => $evento]);
        }
        else
        {
            $evento_ = $request->getSession()->get('evento');
            if(!empty($evento_))
            {
                $eventObj = $eventoRepository->searchEvent($evento_);
                $evento = true;
            }
        }

        if ($request->isMethod('POST')) 
        {
            $data = $request->request;
            $requiredFields = ['nombre', 'lugar', 'descripcion', 'orden', 'plazas', 'fecha', 'hora'];
            foreach ($requiredFields as $field) 
            {
                if (empty($data->get($field))) 
                {
                    $error[] = "El campo $field es obligatorio.";
                }
            }
            if (empty($error)) 
            {
                if ($data->get('plazas') <= 0) 
                {
                    $error[] = 'El número de plazas debe ser mayor que 0.';
                }
                if ($data->get('orden') <= 0) 
                {
                    $error[] = 'El orden debe ser mayor que 0.';
                }
                $fecha = new \DateTime($data->get('fecha'));
                $hora = \DateTime::createFromFormat('H:i', $data->get('hora'));
                if($fecha < new \DateTime()) 
                {
                    $error[] = 'La fecha de inicio no puede ser anterior a la fecha actual';
                } 
                if ($data->get('plazas') > $eventObj->getPlazas()) 
                {
                    $error[] = 'El número de plazas debe inferior o igual al del evento.';
                }
            }


            if (empty($error)) 
            {
                $success = $actividadRepository->createActividad($data->get('nombre'),  $data->get('descripcion'), (int)$data->get('orden'), $data->get('plazas'), $data->get('lugar'), $fecha, $hora, $eventObj); 
            }
            return $this->render('activity/create_activity.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'evento' => $evento]);
        }
        return $this->render('activity/create_activity.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'evento' => $evento]);
    }
}
