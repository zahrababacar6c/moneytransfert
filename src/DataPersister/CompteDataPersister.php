<?php
namespace App\DataPersister;

use App\Entity\Depot;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CompteDataPersister implements ContextAwareDataPersisterInterface
{
    private $token;
    private $manager ;
    public function __construct(TokenStorageInterface $token ,EntityManagerInterface $manager)
    {
        $this->token=$token;
        $this-> manager =$manager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Compte;
    }

    public function persist($data, array $context = [])
    {
      // call your persistence layer to save $data
      $data->setUser($this->token->getToken()->getUser());
      //dd($data);
    
      $depot = new Depot() ;
      $depot->setMontant($data->getSoldeInit());
      $depot->setCompte($data);
      $depot->setUser($this->token->getToken()->getUser());
      //dd($data);
        $this->manager->persist($depot);
        $this->manager->persist($data);
        $this->manager->flush();
    }
    public function remove($data, array $context = [])
    {
      // call your persistence layer to delete $data
      $this->manager->remove($data);
      $this->manager->flush();
    }
}   
?>