<?php
// src/Controller/EditEventController.php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditEventController extends AbstractController
{
    #[Route('/editevent', name: 'editevent')]
    public function editEvent(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository): Response
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
            return $this->render('event/edit_event.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'evento' => $evento]);
        }
        else
        {
            $evento_ = $request->getSession()->get('evento');
            if(!empty($evento_))
            {
                $eventObj = $eventoRepository->searchEvent($evento_);
                $evento = true;
            }
            else
            {
                return $this->render('event/edit_event.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'evento' => $evento]);
            }
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
                $plazas = (int)$data->get('numEntrada');
                $success = $eventoRepository->editEvent($eventObj->getId(), $data->get('nombre'), $data->get('tipo'), $plazas, $data->get('precio'), $data->get('ubicación'), $fInicio, $fFin, $data->get('descripcion'));            
            }
            return $this->render('event/edit_event.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'evento' => $evento, 'nombre' => $eventObj->getNombre(), 'ubicacion' => $eventObj->getLugar(), 'descripcion' => $eventObj->getDescripcion(), 'precio' => $eventObj->getPrecio(), 'numEntrada' => $eventObj->getPlazas(), 'tipo' => $eventObj->getTipo(), 'fInicio' => $eventObj->getFechaInicio(), 'fFin' => $eventObj->getFechaFin()]);
        }
        return $this->render('event/edit_event.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'evento' => $evento, 'nombre' => $eventObj->getNombre(), 'ubicacion' => $eventObj->getLugar(), 'descripcion' => $eventObj->getDescripcion(), 'precio' => $eventObj->getPrecio(), 'numEntrada' => $eventObj->getPlazas(), 'tipo' => $eventObj->getTipo(), 'fInicio' => $eventObj->getFechaInicio(), 'fFin' => $eventObj->getFechaFin()]);
    }
}
