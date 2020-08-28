<?php

namespace App\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;

class AuthenticationLogoutHandler extends DefaultLogoutSuccessHandler
{

    public function onLogoutSuccess(Request $request)
    {
        $response = parent::onLogoutSuccess($request);
        //used for switch nginx cache off for logged in users. If cookie exists => cache must be off
        $response->headers->clearCookie('login');

        return $response;
    }
}