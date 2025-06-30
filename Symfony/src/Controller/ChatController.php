<?php
// src/Controller/ChatControllerController.php

namespace App\Controller;

use App\Repository\EventoRepository;
use App\Repository\MensajeRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat')]
    public function chat(Request $request, UsuarioRepository $usuarioRepository, MensajeRepository $mensajeRepository): Response
    {
        $auth = false;
        $contactos = [];
        $mensajes = null;
        $interlocutor = null;
        $authStaff = false;
        
        $authToken = $request->getSession()->get('authenticated');
        if(!empty($authToken))
        {
            $timestamp = (int) str_replace('authcode-', '', $authToken);
            if((time() - $timestamp) < 1200)
            {
                $auth = true;
                $user = $usuarioRepository->searchUser($request->getSession()->get('user'));
                if($user->getRol() == 'admin' || $user->getRol() == 'promotor')
                {
                    $authStaff = true;
                }
            }
        }
        
        if( $auth == true)
        {
            if($authStaff == false)
            {
                $contactos = $usuarioRepository->searchAllChat();
            }
            else
            {
                $contactos_ = $usuarioRepository->searchAllChat();
                $contactos__ = $mensajeRepository->searchEscritos($user);
                $contactos = array_filter(array_unique(array_merge($contactos_, $contactos__), SORT_REGULAR), function($contacto) use ($user) {return $contacto->getEmail() !== $user->getEmail();});
            }
            $interlocutor_ = $request->query->get('interlocutor');
            if ($interlocutor_) 
            {
                $interlocutor = $usuarioRepository->searchUser($interlocutor_);
                $mensajes = $mensajeRepository->getConversacion($user, $interlocutor);
            }
        }

        if ($request->isMethod('POST') && $auth == true) 
        {
            $destinatarioEmail = $request->request->get('destinatario_id');
            $contenido = $request->request->get('mensaje');
            
            if ($auth && $destinatarioEmail && $contenido) 
            {
                $destinatario = $usuarioRepository->searchUser($destinatarioEmail);
                
                if ($destinatario) 
                {
                    $mensajeRepository->enviarMensaje($user, $destinatario, $contenido);
                }
            }
            if ($interlocutor_) 
            {
                $interlocutor = $usuarioRepository->searchUser($interlocutor_);
                $mensajes = $mensajeRepository->getConversacion($user, $interlocutor);
            }
            return $this->render('chat/chat.html.twig', ['auth' => $auth, 'usuarios' => $contactos, 'mensajes' => $mensajes, 'interlocutor' => $interlocutor, 'user' => $user]);
        }

        if($auth == true)
        {
            return $this->render('chat/chat.html.twig', ['auth' => $auth, 'usuarios' => $contactos, 'mensajes' => $mensajes, 'interlocutor' => $interlocutor, 'user' => $user]);
        }
        return $this->render('chat/chat.html.twig', ['auth' => $auth, 'usuarios' => $contactos, 'mensajes' => $mensajes, 'interlocutor' => $interlocutor]);
    }
}
