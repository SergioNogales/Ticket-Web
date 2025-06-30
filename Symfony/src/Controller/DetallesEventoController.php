<?php
// src/Controller/DetallesEventoController.php

namespace App\Controller;

use App\Repository\ActividadRepository;
use App\Repository\EventoRepository;
use App\Repository\ReservaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetallesEventoController extends AbstractController
{
    #[Route('/detallesevento', name: 'detallesevento')]
    public function detalles(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository, ActividadRepository $actividadRepository, ReservaRepository $reservaRepository): Response
    {
        $auth = false;
        $authPromotor = false;
        $evento = null;
        $plazasOcupadas = null;
        $actividades = null;
        
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
            return $this->render('event/detalles_event.html.twig', ['auth' => $auth, 'authPromotor' => $authPromotor, 'evento' => $evento, 'authPromotor' => $authPromotor]);
        }

        $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
        if($user->getRol() == 'promotor')
        {
            $authPromotor = true;
        }

        if ($request->isMethod('POST')) 
        {
            if(!empty($request->request->get('evento')))
            {
                $evento = $eventoRepository->searchEvent($request->request->get('evento'));
                $actividades = $actividadRepository->searchActividades($evento);
            }

            if($request->request->get('accion') == 'reserva')
            {
                $request->getSession()->set('reserva', $request->request->get('reserva'));
                return $this->redirectToRoute('inscripcion');            
            }
            if($request->request->get('accion') == 'borrar')
            {
                $eventoRepository->deleteEvent($evento->getId());
                return $this->redirectToRoute('listaeventos');            
            }
            elseif($request->request->get('accion') == 'modificar')
            {
                $request->getSession()->set('evento', $request->request->get('evento'));
                return $this->redirectToRoute('editevent');            
            }
            elseif($request->request->get('accion') == 'añadir_actividad')
            {
                $request->getSession()->set('evento', $request->request->get('evento'));
                return $this->redirectToRoute('createactivity'); 
            }
            elseif($request->request->get('accion') == 'borrarActividad')
            {
                $idActividad = $request->request->get('actividad');
                $actividadRepository->deleteActividad($idActividad);
                $actividades = $actividadRepository->searchActividades($evento);
            }
            elseif($request->request->get('accion') == 'modificarActividad')
            {
                $request->getSession()->set('actividad', $request->request->get('actividad'));
                return $this->redirectToRoute('editactivity');      
            }
            elseif($request->request->get('accion') == 'verAsistentes')
            {
                $evento = $eventoRepository->searchEvent($request->request->get('evento'));
                $reservas = $reservaRepository->searchReservasByEvento($evento);
                return $this->render('event/asistentes.html.twig', ['auth' => $auth, 'reservas' => $reservas]);
            }
            $plazasOcupadas = $reservaRepository->PlazasOcupadas($evento);
            return $this->render('event/detalles_event.html.twig', ['auth' => $auth, 'authPromotor' => $authPromotor, 'evento' => $evento, 'actividades' => $actividades, 'plazasOcupadas' => $plazasOcupadas]);
        }

        return $this->render('event/detalles_event.html.twig', ['auth' => $auth, 'authPromotor' => $authPromotor, 'evento' => $evento, 'authPromotor' => $authPromotor]);
    }
}
