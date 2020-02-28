<?php

// src/App/EventListener/JWTCreatedListener.php

namespace App\EventListener;

use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JWTCreatedListener extends AbstractController
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \AppBundle\Entity\User */
        $user = $event->getUser();
        //dd($user->getIsActive());
        if($user->getIsActive() == false)
        {
             //throw new Exception("utilisateur bloquer",401);
             throw $this->createAccessDeniedException('utilisateur bloquer');
        }

        // merge with existing event data
        $payload = array_merge(
            $event->getData(),
            [
                'login' => $user->getLogin()
            ]
        );

        $event->setData($payload);
    }
}