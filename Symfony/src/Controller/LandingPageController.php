<?php
// src/Controller/LandingPageController.php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    #[Route('/landingpage', name: 'landingpage')]
    public function landingPage(Request $request, EventoRepository $eventoRepository): Response
    {
        $conciertos = $eventoRepository->searchEventsFiltrados('concierto');
        $peliculas = $eventoRepository->searchEventsFiltrados('cine');
        return $this->render('login/landing_page.html.twig', ['conciertos' => $conciertos, 'peliculas' => $peliculas]);
    }
}
