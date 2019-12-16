<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class WineController
 * @package App\Controller
 * @Route("/")
 * @IsGranted("ROLE_ACTIVE_USER")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->render('index.html.twig');
    }
}
