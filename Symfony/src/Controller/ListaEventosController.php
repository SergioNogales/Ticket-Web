<?php
// src/Controller/ListaEventosController.php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListaEventosController extends AbstractController
{
    #[Route('/listaeventos', name: 'listaeventos')]
    #[Route('/listaeventos/{filtro}', name: 'listaeventosfiltrados')]
    public function listar(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository, ?string $filtro = null): Response
    {
        $auth = false;
        $filter = $filtro;
        $authPromotor = false;
        
        $authToken = $request->getSession()->get('authenticated');
        if(!empty($authToken))
        {
            $timestamp = (int) str_replace('authcode-', '', $authToken);
            if((time() - $timestamp) < 1200)
            {
                $auth = true;
                $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
                if($user->getRol() == 'promotor')
                {
                    $authPromotor = true;
                }
            }
        }

        if(!empty($filter))
        {
            $eventos = $eventoRepository->searchEventsFiltrados($filter);
        }
        else
        {
            $eventos = $eventoRepository->searchAllEvents();
        }
        
        if($authPromotor)
        {
            $eventosPromotor = $eventoRepository->searchEvents($user);
            return $this->render('event/listado_eventos.html.twig', ['auth' => $auth, 'authPromotor' => $authPromotor, 'filter_' => $filter, 'eventos' => $eventos, 'eventosPromotor' => $eventosPromotor]);
        }
        else
        {
            return $this->render('event/listado_eventos.html.twig', ['auth' => $auth, 'authPromotor' => $authPromotor, 'filter_' => $filter, 'eventos' => $eventos]);
        }
    }
}
