<?php
namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $dateContrat;
    private $encoder ;
    private $manager ;
    public function __construct(UserPasswordEncoderInterface $encoder,EntityManagerInterface $manager)
    {
        $this-> encoder =$encoder;
        $this-> manager =$manager;
        $this-> dateContrat = date_Create();
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
      // call your persistence layer to save $data
      $data->setPassword($this->encoder->encodePassword($data ,$data->getPassword()));
      //dd($data);
      $this->manager->persist($data);
      $this->manager->flush();
      if ($data->getProfil()->getLibelle()== "Partenaire")
    {
      return $this->generateContrat($data);
    }
    
    }


    public function remove($data, array $context = [])
    {
      // call your persistence layer to delete $data
      $this->manager->remove($data);
      $this->manager->flush();
    }
    public function generateContrat($data)
    {
        $contrat = 
        [
                'status'=> 201,
                'message'=> 
                "Le présent contrat est assigné et prend effet à compter du ". $this->dateContrat->format('d/m/Y').
            " entre Fouta transfert Argent et ".$data->getUsername().
            " Dont le siége sociale est situé à ".$data->getPartenaire()->getAdresse().
            " Immatriculée au Registre du Commerce ".$data->getPartenaire()->getRc().
            " Sous Numero d'identification National des Associations et Entreprises ". $data->getPartenaire()->getNinea().
            " Article 1:Les dispositions du présent Contrat définissent les conditions techniques,juridiques et
            financières permettant aux deux Parties de s’engager dans le cadre d’un Partenariat.
            Les dispositions du présent Contrat sont impératives et s’appliquent au Partenaire Distributeur
            lors de toute transaction conclue avec le Partenaire Sponsor."
        ];
        return new JsonResponse($contrat, 201);
    }
}

 ?>