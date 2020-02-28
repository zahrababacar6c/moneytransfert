<?php
namespace App\DataPersister;

use App\Entity\Depot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DepotDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Depot;
    }

    public function persist($data, array $context = [])
    {
      // call your persistence layer to save $data
      $data->setUser($this->token->getToken()->getUser());
      //dd($data);
        $this->manager->persist($data);
        $this->manager->flush();
        return $this->miseajoursolde($data) ;

    }
    public function remove($data, array $context = [])
    {
      $this->manager->remove($data);
      $this->manager->flush();
    }


  public function miseajoursolde($data) 

    {
        $compte = $data->getCompte();
        $newsolde=$data->getCompte()->getSoldeInit() + $data->getMontant();
        $compte->setSoldeInit($newsolde);
        $this->manager->persist($compte);
        $this->manager->flush();
        $nouveauMontant = 
        [
                'status'=> 201,
                'message'=> 
                "le nouveau solde de votre compte est :$newsolde."
        ];
        return new JsonResponse($nouveauMontant , 201);
    }
}   
?>