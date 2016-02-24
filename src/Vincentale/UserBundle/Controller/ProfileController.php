<?php



namespace Vincentale\UserBundle\Controller;


use Vincentale\UserBundle\Entity\User;
use Vincentale\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends Controller
{


    public function showAction(Request $request) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')){
            $request->getSession()->getFlashBag()->add('danger', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('fos_user_security_login');
        }
        $user = $this->getUser();

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
    }



    public function editAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')){
            $request->getSession()->getFlashBag()->add('danger', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('fos_user_security_login');
        }
        $userManager =$this->get('fos_user.user_manager');
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Modifications prises en compte.');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        else {
            return $this->render('VincentaleUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
        }
    }
}









/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/02/2016
 * Time: 17:15
 */