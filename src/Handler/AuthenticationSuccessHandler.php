<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        //used for switch nginx cache off for logged in users. If cookie exists => cache must be off
        $cookie = new Cookie('login', 1, time() + (3600 * 24 * 365), '/', null, false, false);

        $response = parent::onAuthenticationSuccess($request, $token);
        $response->headers->setCookie($cookie);

        return $response;
    }
}