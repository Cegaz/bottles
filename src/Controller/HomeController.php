<?php

namespace App\Controller;

use App\Entity\Wine;
use App\Repository\WineRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\DetectMobileDevice;

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
    public function index(DetectMobileDevice $detectMobileDevice)
    {
        if ($detectMobileDevice->isMobile()) {
            return $this->redirectToRoute('wines_list');
        } else {
            $nbBottles = $this->getDoctrine()
                ->getRepository('App:Wine')
                ->countBottles();
    
            return $this->render('index.html.twig', ['nbBottles' => $nbBottles]);
        }
    }

    /**
     * @Route("/get-navbar", name="get_navbar")
     * @return Response
     */
    public function getNavbar(Request $request)
    {
        $wineRepository = $this->getDoctrine()->getRepository('App:Wine');

        $years = $this->getDoctrine()
            ->getRepository('App:Wine')
            ->getDistinctYears();
        $listYears = [];
        foreach ($years as $year) {
            if ($year['year']) $listYears[] = $year['year'];
        }

        $dluoYears = $this->getDoctrine()
            ->getRepository('App:Wine')
            ->getDistinctDluoYears();
        $listDluoYears = [];
        foreach ($dluoYears as $dluoYear) {
            if ($dluoYear['dluo']) $listDluoYears[] = $dluoYear['dluo'];
        }

        if (DetectMobileDevice::isMobile()) {        
            return $this->render('mobile/navbar.html.twig', [
                'dluoYears' => $listDluoYears,
                'years' => $listYears,
                'areas' => Wine::AREAS,
                'nbBottles' => $wineRepository->countBottles()
            ]);
        } else {
            return $this->render('navbar.html.twig', [
                'dluoYears' => $listDluoYears,
                'years' => $listYears,
                'areas' => Wine::AREAS,
                'nbBottles' => $wineRepository->countBottles()
            ]);
        }
    }
}
