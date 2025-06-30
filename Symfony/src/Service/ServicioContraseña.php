<?php
// src/Service/ServicioContraseña.php

namespace App\Service;


class ServicioContraseña
{
    public function __construct()
    {
    }

    public function hashear(string $texto, string $salt): string
    {
        $texto = $texto.$salt;
        return hash('sha256', $texto);
    }

    public function cifrar(string $data, string $clave): string
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', hash('sha256', $clave), 0, $iv);
        return base64_encode($iv.$encrypted);
    }

    public function descifrar(string $data, string $clave): string
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', hash('sha256', $clave), 0, $iv);
    }
}