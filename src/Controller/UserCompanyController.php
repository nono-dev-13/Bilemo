<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Entity\UserCompany;
use App\Repository\PhoneRepository;
use App\Repository\UserCompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
}
