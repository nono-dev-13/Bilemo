<?php

namespace App\Controller;

use App\Entity\UserCompany;
use App\Repository\CompanyRepository;
use App\Repository\UserCompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserCompanyController extends AbstractController
{
    #[Route('/api/usercompanies', name: 'userCompanies', methods: ['GET'])]
    public function getPhoneList(UserCompanyRepository $userCompanyRepository, SerializerInterface $serializerInterface): JsonResponse
    {

        $userCompanyList = $userCompanyRepository->findAll();
        $jsonUserCompanyList = $serializerInterface->serialize($userCompanyList, 'json', ['groups' => 'getUserCompany']);
        return new JsonResponse($jsonUserCompanyList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/usercompanies/{id}', name: 'detailUserCompanies', methods: ['GET'])]
    public function getDetailBook(UserCompany $userCompany, SerializerInterface $serializer): JsonResponse 
    {
        $jsonUserCompany = $serializer->serialize($userCompany, 'json', ['groups' => 'getUserCompany']);
        return new JsonResponse($jsonUserCompany, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/usercompanies/{id}', name: 'deleteUserCompanies', methods: ['DELETE'])]
    public function deleteUserCompanies(UserCompany $userCompany, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($userCompany);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/usercompanies', name:"createUserCompanies", methods: ['POST'])]
    public function createUserCompanies(Request $request,CompanyRepository $companyRepository, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {

        $UserCompany = $serializer->deserialize($request->getContent(), UserCompany::class, 'json');

        // Récupération de l'ensemble des données envoyées sous forme de tableau
        $content = $request->toArray();

        // Récupération de l'idCompany. S'il n'est pas défini, alors on met -1 par défaut.
        $idCompany = $content['idCompany'] ?? -1;

        // On cherche le userCompany qui correspond et on l'assigne à la company.
        // Si "find" ne trouve pas le userCompany, alors null sera retourné.
        $UserCompany->setCompany($companyRepository->find($idCompany));

        $em->persist($UserCompany);
        $em->flush();

        $jsonUserCompany = $serializer->serialize($UserCompany, 'json', ['groups' => 'getUserCompany']);
        
        $location = $urlGenerator->generate('detailUserCompanies', ['id' => $UserCompany->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUserCompany, Response::HTTP_CREATED, ["Location" => $location], true);
   }

   #[Route('/api/usercompanies/{id}', name:"updateUserCompanies", methods:['PUT'])]
    public function updateUserCompanies(Request $request, SerializerInterface $serializer, UserCompany $currentUserCompany, EntityManagerInterface $em, CompanyRepository $companyRepository): JsonResponse 
    {
        $updatedUserCompany = $serializer->deserialize($request->getContent(), 
                UserCompany::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUserCompany]);
        $content = $request->toArray();
        $idCompany = $content['idCompany'] ?? -1;
        $updatedUserCompany->setCompany($companyRepository->find($idCompany));
        
        $em->persist($updatedUserCompany);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
