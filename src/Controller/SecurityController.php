<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    public function index()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('wines_list');
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route("/acces-refuse", name="access_denied")
     */
    public function access_denied()
    {
        return $this->render('accessDenied.html.twig');
    }

}
