<?php
// src/Controller/ListaReservasController.php

namespace App\Controller;

use App\Repository\ReservaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListaReservasController extends AbstractController
{
    #[Route('/listareservas', name: 'listareservas')]
    public function listar(Request $request, UsuarioRepository $usuarioRepository, ReservaRepository $reservaRepository): Response
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
            return $this->render('reserva/listado_reservas.html.twig', ['auth' => $auth]);
        }

        $reservas = $reservaRepository->searchReservasByUsuario($user);
        if ($request->isMethod('POST')) 
        {
            $request->getSession()->set('reserva', $request->request->get('reserva'));
            return $this->redirectToRoute('detallesreserva');
        }
        return $this->render('reserva/listado_reservas.html.twig', ['auth' => $auth, 'reservas' => $reservas]);
    }
}
