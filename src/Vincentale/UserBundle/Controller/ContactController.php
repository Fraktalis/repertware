<?php



namespace Vincentale\UserBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * Action d'affichage d'un profil de contact
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Request $request, $id) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            $request->getSession()->getFlashBag()->add('danger', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('fos_user_security_login');
        }
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        $selfUser = $this->getUser();
        if ($user === NULL ) { //Si l'user n'existe pas, on affiche l'erreur
            return $this->render('::error.html.twig', array(
                'message' => "Utilisateur inexistant.",));
        }
        elseif ($selfUser->getId() === $user->getId() ) {  // Si on cherche à afficher son propre profil, on redirige vers "Mon profil"
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        else
        return $this->render('VincentaleUserBundle:Contact:view.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Permet d'ajouter un contact à notre liste de contact
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request,$id) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){ //Si on est pas connecté en tant qu'utilisateur on redirige
            $request->getSession()->getFlashBag()->add('danger', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('fos_user_security_login');
        }
        $selfUser = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        if ($user === NULL ) {                          //Si l'user n'existe pas, on affiche l'erreur
            return $this->render('::error.html.twig', array(
                'message' => "Impossible d'ajouter un utilisateur inexistant :/ .",));
        }
        elseif ($selfUser->hasContact($user)) {         //Le contact existe déjà
            return $this->render('::error.html.twig', array(
                'message' => "Impossible d'ajouter un contact pré-existant.",));
        }
        else {
            $selfUser->addContact($user);
            $userManager->updateUser($selfUser);
            $request->getSession()->getFlashBag()->add('success', 'Contact ajouté');
            return $this->render('VincentaleUserBundle:Contact:view.html.twig', array(
                'user' => $user,
            ));
        }

    }

    /**
     * Suppression du contact ayant l'id $id de notre liste de contact
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function delAction(Request $request,$id) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            $request->getSession()->getFlashBag()->add('danger', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('fos_user_security_login');
        }
        $selfUser = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        if ($user === NULL ) { //Si l'user n'existe pas, on affiche l'erreur
            return $this->render('::error.html.twig', array(
                'message' => "Impossible de supprimer un utilisateur inexistant...",));
        }
        elseif (!$selfUser->hasContact($user)) { //User n'est pas notre contact, on ne peut pas l'éliminer...
            return $this->render('::error.html.twig', array(
                'message' => "Impossible de supprimer une personne n'étant pas votre contact...",));
        }
        else {
            $selfUser->delContact($user);
            $userManager->updateUser($selfUser);
            $request->getSession()->getFlashBag()->add('success', 'Contact supprimé...');
            return $this->render('VincentaleUserBundle:Contact:view.html.twig', array(
                'user' => $user,
            ));
        }
    }


    /**
     * Affiche tous les contacts de notre liste
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function displayContactsAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            $request->getSession()->getFlashBag()->add('danger', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('fos_user_security_login');
        }
        $selfUser =$this->getUser();
        $contacts = $selfUser->getContacts();
        if ($contacts === NULL || $contacts->isEmpty()) {
            return $this->render('::error.html.twig', array(
                'message' => "Vous n'avez pas d'amis...",));
        }
        else {
            return $this->render('VincentaleUserBundle:Contact:display.html.twig', array(
                'contacts' => $contacts,
            ));
        }
    }


    /**
     * Permet de rechercher des contacts parmi tous les utilisateurs inscrits
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     *
     */
    public function searchAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            $request->getSession()->getFlashBag()->add('danger', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('fos_user_security_login');
        }
        $cond = $request->request->get('partial_username');
        $repository = $this->getDoctrine()->getManager()->getRepository('VincentaleUserBundle:User');
        $users = $repository->findByUsernameLike($cond);
        if ($users === NULL || empty($users)) {
            return $this->render('::error.html.twig', array(
                'message' => "La recherche n'a donné aucun résultat, Veuillez réessayer."));
        }
        else {
            return $this->render('VincentaleUserBundle:Contact:display.html.twig', array(
                'contacts' => $users,
            ));
        }
    }
}
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/02/2016
 * Time: 23:24
 */