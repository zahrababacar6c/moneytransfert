<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/user/bloquer/{id}", name="bloquer_user", methods={"GET"})
     */
    public function bloquerUser($id)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->find($id);
        $user->setIsActive(false);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
        $data = [
                'status' => 200,
                'message' => "utilisateur bloquer"
                ];
        return $this->json($data,200);
    }

    /**
     * @Route("/api/user/activer/{id}", name="activer_user", methods={"GET"})
     */
    public function activerUser($id)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->find($id);
        $user->setIsActive(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
        $data = [
                'status' => 200,
                'message' => "utilisateur Activer"
                ];
        return $this->json($data,200);
    }
}
