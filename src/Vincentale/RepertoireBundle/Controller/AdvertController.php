<?php

// src/Vincentale/RepertoireBundle/Controller/AdvertController.php

namespace Vincentale\RepertoireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller {

    public function indexAction() {
        return $this->render('VincentaleRepertoireBundle:layout.html.twig');
    }

    public function viewAction($id) {
        // $id représente l'id passé en paramètre de l'URL
        // On récupère le repository
    $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('VincentaleRepertoireBundle:User');

        $user = $repository->find($id);

        if (null === $user) {
            throw new NotFoundHttpException("L'utilisateur numéro ".$id." n'existe pas.");
        }

        return $this->render('VincentaleRepertoireBundle:Advert:view.html.twig', array(
            'user' => $user
        ));
    }

    public function viewSlugAction($slug, $year, $_format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$_format."."
        );
    }

    public function addAction(Request $request) {

        $user = new User();
        $user->setName("Alexandre")
            ->setLogin("vincentale")
            ->setPassword("781227")
            ->setAdress("ici")
            ->setEmail("vincentale@eisti.com")
            ->setTel(00000000);

        $em = $this->getDoctrine()->getManager();

        $em-> persist($user);

        $em->flush();
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirect($this->generateUrl('rep_view', array('id' => $user->getId())));
        }

        return $this->render('VincentaleRepertoireBundle:Advert:add.html.twig');
    }

    public function menuAction($limit)
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('VincentaleRepertoireBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }
}