<?php

namespace App\Controller;

use App\Entity\Wine;
use App\Repository\WineRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\HttpFoundation\JsonResponse;
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
 * @Route("/vins")
 * @IsGranted("ROLE_ACTIVE_USER")
 */
class WineController extends AbstractController
{
    private $wineRepository;

    public function __construct(WineRepository $wineRepository)
    {
        $this->wineRepository = $wineRepository;
    }

    /**
     * @Route("/liste", name="wines_list")
     */
    public function index()
    {
        $years = $this->wineRepository->getDistinctYears();
        $listYears = [];
        foreach ($years as $year) {
            $listYears[] = $year['year'];
        }

        return $this->render('index.html.twig', [
            'years' => $listYears,
            'areas' => Wine::AREAS
        ]);
    }

    /**
     * @Route("/datatable", name="wines_datatable")
     */
    public function datatable()
    {
        $wines = $this->wineRepository->findNotEmpty();
        $data = [];

        foreach ($wines as $wine) {
            dump($wine->getComment());
            $data[] = [
                'id' => $wine->getId(),
                'color' => $wine->getColor(),
                'origin' => $wine->getOrigin(),
                'year' => $wine->getYear(),
                'name' => $wine->getName(),
                'comment' => nl2br($wine->getComment()),
                'dluo' => $wine->getDluo(),
                'quantity' => $this->renderView('bottlesQuantity.html.twig', [
                    'nb' => $wine->getNbBottles() ?? 1,
                ]),
                'rate' => $this->renderView('stars.html.twig', [
                    'nb' => $wine->getRate()
                ]),
                'actions' => $this->renderView('actions.html.twig', [
                    'wineId' => $wine->getId()
                ]),
            ];
        }
        return new JsonResponse(['data' => $data]);
    }

    /**
     * @Route("/creer", name="wine_new")
     */
    public function new(Request $request)
    {
        $wine = new Wine();

        $formWine = $this->createFormBuilder($wine)
            ->setAction($this->generateUrl('wine_new'))
            ->add('color', ChoiceType::class, [
                'choices' => [
                    'rouge' => Wine::COLOR_RED,
                    'blanc' => Wine::COLOR_WHITE,
                    'rosé' => Wine::COLOR_PINKY
                ],
                'label' => 'Couleur',
                'placeholder' => '...'
            ])
            ->add('origin', ChoiceType::class, [
                'label' => 'Région',
                'choices' => Wine::AREAS,
                'placeholder' => '...'
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Année',
                'choices' => $this->getYearsArray(),
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '...'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('dluo', ChoiceType::class, [
                'label' => 'Date limite',
                'choices' => $this->getYearsArray(true),
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '...',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->getForm();

        $formWine->handleRequest($request);

        if ($formWine->isSubmitted() && $formWine->isValid()) {
             $wine
                 ->setNbBottles(1)
                 ->setRate(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($wine);
            $em->flush();

            return $this->redirectToRoute('wines_list');
        }

        $html = $this->renderView('modalNewBottleContent.html.twig', [
            'formWine' => $formWine->createView()
        ]);

        return new JsonResponse($html);
    }

    /**
     * @Route("/modifier/{id}", name="wine_edit")
     * @param Wine $wine
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function edit(Request $request, Wine $wine)
    {
        $formWine = $this->createFormBuilder($wine)
            ->setAction($this->generateUrl('wine_edit', ['id' => $wine->getId()]))
            ->add('color', ChoiceType::class, [
                'choices' => [
                    'rouge' => Wine::COLOR_RED,
                    'blanc' => Wine::COLOR_WHITE,
                    'rosé' => Wine::COLOR_PINKY
                ],
                'label' => 'Couleur',
                'placeholder' => '...'
            ])
            ->add('origin', ChoiceType::class, [
                'label' => 'Région',
                'choices' => Wine::AREAS,
                'placeholder' => '...'
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Année',
                'choices' => $this->getYearsArray(),
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '...',
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false
            ])
            ->add('dluo', ChoiceType::class, [
                'label' => 'Date limite optimale',
                'choices' => $this->getYearsArray(true),
                'expanded' => false,
                'multiple' => false,
                'placeholder' => '...',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Modifier'
            ])
            ->getForm();
        $formWine->handleRequest($request);

        if ($formWine->isSubmitted() && $formWine->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('wines_list');
        }
        $html = $this->renderView('modalEditWineContent.html.twig', [
            'formWine' => $formWine->createView()
        ]);

        return new JsonResponse($html);
    }

    private function getYearsArray($future = false)
    {
        $now = new \DateTime('now');
        $thisYear = $now->format('Y');

        $years = [];
        if ($future) {
            for ($i = $thisYear; $i <= $thisYear + 20; $i++) {
                $years[strval($i)] = strval($i);
            }
        } else {
            for ($i = $thisYear; $i >= $thisYear - 20; $i--) {
                $years[strval($i)] = strval($i);
            }
        }
        natsort($years);

        return $future ? $years : array_reverse($years, true);
    }

    /**
     * @Route("/plus-bouteille", name="plus-one-bottle")
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function plusOneBottle(Request $request)
    {
        $postRequest = $request->request;
        $id = $postRequest->get('id');
        $plus = $postRequest->get('plus');

        $wine = $this->wineRepository->find($id);

        if ($plus) {
            $newQuantity = $wine->getNbBottles() + 1;
        } else {
            $newQuantity = $wine->getNbBottles() - 1;
        }

        if ($newQuantity >= 0) {
            $wine->setNbBottles($newQuantity);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        $html = $this->renderView('bottlesQuantity.html.twig', [
            'nb' => $newQuantity,
        ]);

        return new JsonResponse($html);
    }

    /**
     * @Route("/plus-etoile", name="plus-one-star")
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function plusOneStar(Request $request)
    {
        $postRequest = $request->request;
        $id = $postRequest->get('id');
        $plus = $postRequest->get('plus');

        $wine = $this->wineRepository->find($id);

        $newRate = $plus ? $wine->getRate() + 1 : $wine->getRate() - 1;

        if ($newRate >= 0 && $newRate <= 3) {
            $wine->setRate($newRate);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        $html = $this->renderView('stars.html.twig', [
            'nb' => $newRate,
        ]);

        return new JsonResponse($html);
    }

    /**
     * @Route("/historique", name="historic")
     */
    public function historic()
    {
        $years = $this->wineRepository->getDistinctYears();
        $listYears = [];
        foreach ($years as $year) {
            $listYears[] = $year['year'];
        }

        return $this->render('historic.html.twig', [
            'years' => $listYears,
            'areas' => Wine::AREAS
        ]);
    }

    /**
     * @Route("/historique/datatable", name="historic_datatable")
     */
    public function historicDatatable()
    {
        $wines = $this->wineRepository->findEmpty();
        $data = [];

        foreach ($wines as $wine) {
            $data[] = [
                'id' => $wine->getId(),
                'color' => $wine->getColor(),
                'origin' => $wine->getOrigin(),
                'year' => $wine->getYear(),
                'name' => $wine->getName(),
                'comment' => $wine->getComment(),
                'rate' => $this->renderView('stars.html.twig', [
                    'nb' => $wine->getRate()
                ]),
                'actions' => $this->renderView('actions.html.twig', [
                    'wineId' => $wine->getId()
                ]),
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

}
