<?php

namespace App\Controller;


use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;


class SerieController extends AbstractController
{
    /**
     * @Route("/series", name="serie_list")
     */
    public function list(SerieRepository $serieRepository): Response
    {
      //$series = $serieRepository->findAll();  //findAll() récupère tout sans trier
      //$series = $serieRepository->findBy([], ['popularity' => 'DESC', 'vote' => 'DESC'],30 ); //permet le tri, ici les plus populaires en premier si égalité, tri par vote
        $series = $serieRepository->findBestSeries();

        return $this->render('serie/list.html.twig', [
            "series" => $series
        ]);
    }

    /**
     * @Route("/series/details/{id}", name="serie_details")
     */
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        return $this->render('serie/details.html.twig', [
            "serie" => $serie
        ]);
    }

    /**
     * @Route("/series/create", name="serie_create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $serie = new Serie();
        $serie->setDateCreated(new \DateTime());

        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm ->handleRequest($request); //Sert à traiter les données

        if ($serieForm->isSubmitted() && $serieForm-> isValid()){
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'Serie added! Good job.');
            return $this->redirectToRoute('serie_details',['id' => $serie->getId()]);
        }

        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView() //Ne pas oublier le createView, le navigateur ne comprends pas le form mais seulement la view
        ]);
    }

    /**
     * @Route("/series/demo", name="serie_em-demo")
     */
    public function demo(EntityManagerInterface $entityManager): Response
    {
        //Créer une instance de mon entité
        $serie = new Serie();

        //Hydrater toutes les propriétés
        $serie->SetName('Pif');
        $serie->SetBackdrop('dafs');
        $serie->setPoster('dafs');
        $serie->setDateCreated(new \DateTime());
        $serie->setFirstAirDate(new \DateTime("- 1 year"));
        $serie->setLastAirDate(new \DateTime("- 6 months"));
        $serie->setGenres('drama');
        $serie->setOverview('bla bla bla');
        $serie->setPopularity(123.00);
        $serie->setVote(8.2);
        $serie->setStatus('Canceled');
        $serie->setTmdbId(329432);

        dump($serie);

        //$entityManager->persist($serie); //Indique que l'objet serie doit être enregistré lors du prochain flush
        //$entityManager->flush(); //Sauvegarde l'objet dans la BDD

        dump($serie);

        //$serie->setGenres('comedy'); //Indique que l'objet serie doit être modifié lors du prochain flush
        //$entityManager->remove($serie); //Indique que l'objet serie doit être supprimé lors du prochain flush
        //$entityManager->flush(); //Sauvegarde l'objet dans la BDD

        return $this->render('serie/create.html.twig');
    }



}
