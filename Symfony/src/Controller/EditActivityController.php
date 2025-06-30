<?php
// src/Controller/EditActivityController.php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\ActividadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditActivityController extends AbstractController
{
    #[Route('/editactivity', name: 'editactivity')]
    public function editActivity(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository, ActividadRepository $actividadRepository): Response
    {
        $error = [];
        $success = false;
        $auth = false;
        $evento = null;
        $actividad = null;
        
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
            return $this->render('activity/edit_activity.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'actividad' =>$actividad]);
        }
        else
        {
            $actividad_ = $request->getSession()->get('actividad');
            if(!empty($actividad_))
            {
                $actividadObj = $actividadRepository->searchActividad($actividad_);
                $actividad = true;
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
            }


            if (empty($error)) 
            {
                $success = $actividadRepository->editActividad($data->get('nombre'),  $data->get('descripcion'), (int)$data->get('orden'), $data->get('plazas'), $data->get('lugar'), $fecha, $hora, $actividadObj->getId());            
            }
            return $this->render('activity/edit_activity.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'actividad' =>$actividad, 'nombre' => $actividadObj->getNombre(), 'lugar' => $actividadObj->getLugar(), 'descripcion' => $actividadObj->getDescripcion(), 'orden' => $actividadObj->getOrden(), 'plazas' => $actividadObj->getPlazas(), 'fecha' => $actividadObj->getFecha(), 'hora' => $actividadObj->getHora()]);
        }
        return $this->render('activity/edit_activity.html.twig', ['auth' => $auth, 'error' => $error, 'success' => $success, 'actividad' =>$actividad, 'nombre' => $actividadObj->getNombre(), 'lugar' => $actividadObj->getLugar(), 'descripcion' => $actividadObj->getDescripcion(), 'orden' => $actividadObj->getOrden(), 'plazas' => $actividadObj->getPlazas(), 'fecha' => $actividadObj->getFecha(), 'hora' => $actividadObj->getHora()]);
    }
}
