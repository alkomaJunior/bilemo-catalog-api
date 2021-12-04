<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route(name="api_login", path="/api/login_check", methods={"POST"})
     */
    public function apiLogin()
    {
    }
}
