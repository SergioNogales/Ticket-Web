<?php
// src/Controller/ReservaController.php

namespace App\Controller;

use App\Repository\ActividadRepository;
use App\Repository\ReservaActividadesRepository;
use App\Repository\ReservaRepository;
use App\Repository\EventoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservaController extends AbstractController
{
    #[Route('/detallesreserva', name: 'detallesreserva')]
    public function detalles(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository, ActividadRepository $actividadRepository, ReservaRepository $reservaRepository, ReservaActividadesRepository $reservaActividadesRepository): Response
    {
        $auth = false;
        $reservas = null;
        
        $authToken = $request->getSession()->get('authenticated');
        if(!empty($authToken))
        {
            $timestamp = (int) str_replace('authcode-', '', $authToken);
            if((time() - $timestamp) < 1200)
            {
                $auth = true;
                $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
            }
        }

        if($auth == false)
        {
            return $this->render('reserva/detalles_reserva.html.twig', ['auth' => $auth]);
        }

        if ($request->isMethod('POST')) 
        {
            if($request->request->get('accion') == 'cancelar')
            {
                $reserva = $reservaRepository->searchReserva($request->getSession()->get('reserva'));
                $evento = $reserva->getEvento();
                $usuarioRepository->actualizarSaldo($user, $user->getSaldo()+$evento->getPrecio());
                $reservaRepository->cancelarReserva($reserva->getId());
                return $this->redirectToRoute('listareservas');
            }
            elseif($request->request->get('accion') == 'cancelarActividad')
            {
                $reserva = $request->request->get('reservaId');
                $reservaActividadesRepository->cancelarReservaActividad($reserva);
            }
            elseif($request->request->get('accion') == 'reservarActividad')
            {
                $reserva = $reservaRepository->searchReserva($request->getSession()->get('reserva'));
                $actividad = $actividadRepository->searchActividad($request->request->get('actividad'));
                $reservaActividadesRepository->createReservaActividad($actividad, $reserva, false);
            }
        }

        $reserva = $reservaRepository->searchReserva($request->getSession()->get('reserva'));
        $evento = $reserva->getEvento();
        $actividades = $actividadRepository->searchActividades($evento);
        $reservaActividad = $reservaActividadesRepository->searchReservasByReserva($reserva);
        return $this->render('reserva/detalles_reserva.html.twig', ['auth' => $auth, 'reserva' => $reserva, 'evento' => $evento, 'actividades' => $actividades, 'reservaActividades' => $reservaActividad]);
    }
}
