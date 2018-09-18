<?php

namespace App\Controller;

use App\Entity\Server;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    /**
     * @Route("/secure/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('server/dashboard.html.twig', [
            'controller_name' => 'ServerController',
        ]);
    }

    /**
     * @Route("/secure/api/server/get", name="get_server")
     * @Method({"GET"})
     */
    public function getServer()
    {
        $doctrine = $this->getDoctrine();
        $servers = $doctrine->getRepository('App:Server')->findAll();

        $format = 'd.M H:i';

        foreach ($servers as $server) {
            $serverJson['id'] = $server->getId();
            $serverJson['name'] = $server->getName();
            $serverJson['status'] = $server->getStatus();
            $serverJson['blockedBy'] = $server->getBlockedBy();
            if ($serverJson['status'] === Server::STATUS_BLOCKED) {
                $serverJson['blockedSince'] = $server->getBlockedSince();
                $serverJson['blockedBy'] = $server->getBlockedBy();
                $serverJson['description'] = $server->getDescription();
                //$serverJson['blockedSinceFormat'] = $server->getBlockedSince()->format($format);
            }
            $serverJson['createdAt'] = $server->getCreatedAt();
            $serverJson['createdAtFormat'] = $server->getCreatedAt()->format($format);
            $serverJson['description'] = $server->getDescription();
        }

        $json['data']['servers'] = $servers;

        return $this->json($json);
    }

    /**
     * @Route("/secure/api/server/post", name="add_server")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addServer(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);
        $name = $data['name'];
        $doctrine = $this->getDoctrine();

        $dummy = $doctrine->getRepository('App:Server')->findBy(['name' => $name]);
        if ([] !== $dummy) {
            $json['error'] = 'Name: ' . $name . ' already exist!';

            return $this->json($json, 222);
        }

        $server = new Server();
        $server->setName($name);

        $em = $doctrine->getManager();
        $em->persist($server);
        $em->flush();

        return $this->json('Server: ' . $name . ' created!', 200);
    }

    /**
     * @Route("/secure/api/server/block/post", name="block_server")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function blockServer(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $server = $this->getDoctrine()->getRepository('App:Server')->find($data['id']);
        $server
            ->setStatus(Server::STATUS_BLOCKED)
            ->setDescription($data['description'])
            ->setBlockedBy($this->getUser()->getUsername())
            ->setBlockedSince(new \DateTime())
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($server);
        $em->flush();

        return $this->json('Server: ' . $server->getName(), 200);
    }

    /**
     * @Route("/secure/api/server/free/post", name="free_server")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function freeServer(Request $request)
    {
        $data = $request->getContent();
        $data = json_decode($data, true);

        $server = $this->getDoctrine()->getRepository('App:Server')->find($data['id']);
        $server
            ->setStatus(Server::STATUS_FREE)
            ->setDescription(null)
            ->setBlockedSince(new \DateTime())
            ->setBlockedBy(null)
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($server);
        $em->flush();

        return $this->json('Server: ' . $server->getName() . ' free!', 200);
    }
}
