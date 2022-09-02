<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class PhoneController extends AbstractController
{
    /**
     * Cette méthode permet de récupérer l'ensemble des phones.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des phones",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class))
     *     )
     * )
     *
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="Phone")
     *
     */
    #[Route('/api/phones', name: 'phone', methods: ['GET'])]
    public function getPhoneList(Request $request,PhoneRepository $phoneRepository, SerializerInterface $serializerInterface): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);
        $phoneList = $phoneRepository->findAllWithPagination($page, $limit);
        
        $jsonPhoneList = $serializerInterface->serialize($phoneList, 'json');
        return new JsonResponse($jsonPhoneList, Response::HTTP_OK, [], true);
    }


    /**
     * Cette méthode permet de récupérer un phone.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne un phone",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class))
     *     )
     * )
     *
     *
     * @OA\Tag(name="Phone")
     *
     */
    #[Route('/api/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    public function getDetailBook(Phone $phone, SerializerInterface $serializer): JsonResponse 
    {
        $jsonPhone = $serializer->serialize($phone, 'json');
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
