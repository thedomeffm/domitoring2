<?php

namespace App\Controller;

use App\Entity\Server;
use App\Entity\User;
use App\Form\UserType;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/index", name="admin")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository('App:User')->findAll();
        $servers = $this->getDoctrine()->getRepository('App:Server')->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'servers' => $servers,
        ]);
    }

    /**
     * @Route("/admin/user/create", name="admin_user_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userCreate(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ('ROLE_ADMIN' === $form['role']->getData()) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $user->setEnabled(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard', [
                // ---
            ]);
        }

        return $this->render('admin/user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/delete/{user}", name="admin_user_delete")
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUser(User $user)
    {
        if (null === $user) {
            $this->addFlash('error', 'user not found');
            return $this->redirectToRoute('admin');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'user removed');

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/server/delete/{server}", name="admin_server_delete")
     *
     * @param Server $server
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteServer(Server $server)
    {
        if (null === $server) {
            $this->addFlash('error', 'server not found');
            return $this->redirectToRoute('admin');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($server);
        $em->flush();

        $this->addFlash( 'success', 'server removed');

        return $this->redirectToRoute('admin');
    }
}
