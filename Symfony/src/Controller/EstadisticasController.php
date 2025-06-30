<?php
// src/Controller/EstadisticasController.php

namespace App\Controller;

use App\Repository\ActividadRepository;
use App\Repository\EventoRepository;
use App\Repository\ReservaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstadisticasController extends AbstractController
{
    #[Route('/estadisticas', name: 'estadisticas')]
    public function estadisticas(Request $request, UsuarioRepository $usuarioRepository, EventoRepository $eventoRepository, ReservaRepository $reservaRepository, ActividadRepository $actividadRepository): Response
    {
        $auth = false;
        
        $authToken = $request->getSession()->get('authenticated');
        if(!empty($authToken))
        {
            $timestamp = (int) str_replace('authcode-', '', $authToken);
            if((time() - $timestamp) < 1200)
            {
                $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
                if($user->getRol() == 'admin')
                {
                    $auth = true;
                }
            }
        }
        $usuarios = $usuarioRepository->searchAllUsers();
        $eventos = $eventoRepository->searchAllEvents();
        $totalUsuarios = $usuarioRepository->countAll();
        $totalEventos = $eventoRepository->countAll();
        $totalReservas = $reservaRepository->countAll();
        $totalPromotores = $usuarioRepository->countPromotor();
        $totalAdmin = $usuarioRepository->countAdmin();
        $totalActividades = $actividadRepository->countAll();
        return $this->render('admin/estadisticas.html.twig', ['auth' => $auth,'usuarios' => $usuarios, 'eventos' => $eventos, 'total_usuarios' => $totalUsuarios, 'total_eventos' => $totalEventos, 'total_reservas' => $totalReservas, 'total_promotores' => $totalPromotores, 'total_admin' => $totalAdmin, 'total_actividades' => $totalActividades]);
    }
}