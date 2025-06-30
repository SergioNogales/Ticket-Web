<?php
// src/Controller/SolicitudController.php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use App\Repository\SolicitudRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolicitudController extends AbstractController
{
    #[Route('/solicitud', name: 'solicitud')]
    public function createActivity(Request $request, UsuarioRepository $usuarioRepository, SolicitudRepository $solicitudRepository): Response
    {
        $error = [];
        $success = false;
        $authClient = false;
        $authAdmin = false;
        
        $authToken = $request->getSession()->get('authenticated');
        if(!empty($authToken))
        {
            $timestamp = (int) str_replace('authcode-', '', $authToken);
            if((time() - $timestamp) < 1200)
            {
                $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
                if($user->getRol() == 'admin')
                {
                    $authAdmin = true;
                }
                else
                {
                    $authClient = true;
                }
            }
        }

        if($authClient == false && $authAdmin == false)
        {
            return $this->render('solicitud/solicitud_cliente.html.twig', ['auth' => false, 'success' => $success, 'solicitudPendiente' => false]);
        }

        if ($request->isMethod('POST')) 
        {
            if($authClient)
            {   
                $rolSolicitado = $request->request->get('rol');
                if(!empty($rolSolicitado))
                {
                    $success = $solicitudRepository->createSolicitud($rolSolicitado, $user);
                }
                else
                {
                    $error[] = 'Debe seleccionarse un rol a solicitar.';
                }
            }
            else
            {
                $accion = $request->request->get('accion');
                $email = $request->request->get('email');
                $rol_ = $request->request->get('rol');
                $user = $usuarioRepository->searchUser($email);
                if($accion == 'aceptar')
                {
                    $solicitudRepository->aprobarSolicitud($user);
                    $usuarioRepository->setRol($user, $rol_);
                }
                else
                {
                    $solicitudRepository->denegarSolicitud($user);
                }
            }
        }

        if($authClient)
        {
            $solicitudPendiente = $solicitudRepository->searchSolicitud($user);
            if(!empty($solicitudPendiente))
            {
                $solicitudPendiente = true;
            }
            else
            {
                $solicitudPendiente = false;
            }
            $rol = $user->getRol();
            return $this->render('solicitud/solicitud_cliente.html.twig', ['auth' => true, 'success' => $success, 'solicitudPendiente' => $solicitudPendiente, 'rol' => $rol, 'error' => $error]);
        }
        else
        {
            $solicitudes = $solicitudRepository->searchSolicitudes();
            return $this->render('solicitud/solicitud_admin.html.twig', ['auth' => true, 'error' => $error, 'solicitudes' => $solicitudes]);
        }
    }
}