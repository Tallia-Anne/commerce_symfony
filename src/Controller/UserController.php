<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        //  dd($users);
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }


    // Cette route permet d'ajouter un nouveau rôle à l'utilisateur
    #[Route('/admin/user/{id}/to/editor', name: 'app_user_to_editor')]
    public function changeRole(EntityManagerInterface $entityManager, User $user):Response
    {
        $user->setRoles(["ROLE_EDITOR", "ROLE_USER"]);
        $entityManager->flush();

        $this->addFlash('success', 'le role éditeur a été ajouté à votre utilisateur');
        return $this->redirectToRoute('app_user');
    }

     // Cette route permet de retirer un rôle à l'utilisateur
    #[Route('/admin/user/{id}/remove/editor/role', name: 'app_user_remove_editor_role')]
    public function editorRoleRemove(EntityManagerInterface $entityManager, User $user):Response
    {
        $user->setRoles([]);
        $entityManager->flush();

        $this->addFlash('danger', 'le role éditeur a été retirer à votre utilisateur');
        return $this->redirectToRoute('app_user');
    }
      // Cette route permet de supprimer un utilisateur
    #[Route('/admin/user/{id}/remove/', name: 'app_user_remove')]
    public function userRemove(EntityManagerInterface $entityManager, $id ,UserRepository $userRepository):Response
    {
        $userFind = $userRepository->find($id);
        $entityManager->remove($userFind);
        $entityManager->flush();

        $this->addFlash('danger', 'votre utilisateur a été supprimé');
        return $this->redirectToRoute('app_user');
    }
}
