<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\UserCompany;
use App\Repository\CompanyRepository;
use App\Repository\UserCompanyRepository;
use Attribute;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
//use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


class UserCompanyController extends AbstractController
{
    /**
     * Cette méthode permet de récupérer l'ensemble des UserCompany.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des userCompany",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=UserCompany::class, groups={"getUserCompany"}))
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
     * @OA\Tag(name="UserCompanies")
     *
     */
    #[Route('/api/usercompanies', name: 'userCompanies', methods: ['GET'])]
    public function getUserList(UserCompanyRepository $userCompanyRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cache): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);
        $idCache = "getUserList-" . $page . "-" . $limit;
        
        $jsonUserCompany = $cache->get($idCache, function (ItemInterface $item) use ($userCompanyRepository, $serializer, $page, $limit) {
            
            //echo("L'ÉLÉMENT N'EST PAS ENCORE EN CACHE \n");
            $connectedCompany = $this->getUser();
            $item->tag("userCompanyCache");
            
            $UserCompanyList = $userCompanyRepository->findBy(['company' => $connectedCompany],null, $limit, $page);
            $context = SerializationContext::create()->setGroups(['getUserCompany']);
            return $serializer->serialize($UserCompanyList, 'json', $context);
        });
      
        return new JsonResponse($jsonUserCompany, Response::HTTP_OK, [], true);
    }

    /**
     * Cette méthode permet de récupérer un seul des UserCompany.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne un userCompany",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=UserCompany::class, groups={"getUserCompany"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of user",
     *     @OA\Schema(type="integer")
     * )
     *
     * @OA\Tag(name="UserCompanies")
     *
     */
    #[Route('/api/usercompanies/{id}', name: 'detailUserCompanies', methods: ['GET'])]
    public function getDetailUserCompanies(UserCompany $userCompany, SerializerInterface $serializer): JsonResponse 
    {
        $connectedCompagny = $this->getUser();
        
        if ($userCompany->getCompany() === $connectedCompagny) {
            $context = SerializationContext::create()->setGroups(['getUserCompany']);
            $jsonUserCompany = $serializer->serialize($userCompany, 'json', $context);
            return new JsonResponse($jsonUserCompany, Response::HTTP_OK, ['accept' => 'json'], true);
        } else {
            return new JsonResponse("Vous n'avez pas le droit d'accéder à cet utilisateur", Response::HTTP_FORBIDDEN, ['accept' => 'json'], false);
        }
        
    }

    /**
     * Cette méthode permet de supprimer un UserCompany.
     *
     * @OA\Response(
     *     response=200,
     *     description="Supprimer un UserCompany",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=UserCompany::class))
     *     )
     * )
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Id of user to delete",
     *     @OA\Schema(type="integer")
     * )
     *
     * @OA\Tag(name="UserCompanies")
     */
    #[Route('/api/usercompanies/{id}', name: 'deleteUserCompanies', methods: ['DELETE'])]
    public function deleteUserCompanies(UserCompany $userCompany, EntityManagerInterface $em, TagAwareCacheInterface $tagAwareCacheInterface): JsonResponse 
    {
        $connectedCompagny = $this->getUser();
        if ($userCompany->getCompany() === $connectedCompagny) {
            $tagAwareCacheInterface->invalidateTags(["userCompanyCache"]);
            $em->remove($userCompany);
            $em->flush();
    
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } else {
            return new JsonResponse("Vous n'avez pas le droit d'accéder à cet utilisateur", Response::HTTP_FORBIDDEN, ['accept' => 'json'], false);
        }
        
    }

    /**
     * Cette méthode permet de créer un UserCompany.
     *
     * @OA\Response(
     *     response=200,
     *     description="Creér un UserCompany",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=UserCompany::class))
     *     )
     * )
     *
     * @OA\RequestBody(
     *     description="User to add",
     *     required=true,
     *     @Model(type=UserCompany::class, groups={"createUserCompany"})
     * )
     *
     * @OA\Tag(name="UserCompanies")
     */
    #[Route('/api/usercompanies', name:"createUserCompanies", methods: ['POST'])]
    public function createUserCompanies(Request $request,CompanyRepository $companyRepository, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validatorInterface): JsonResponse 
    {

        $UserCompany = $serializer->deserialize($request->getContent(), UserCompany::class, 'json');

        // On vérifie les erreurs
        $errors = $validatorInterface->validate($UserCompany);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $connectedCompany = $this->getUser();
        
        // On cherche le userCompany qui correspond et on l'assigne à la company.
        // Si "find" ne trouve pas le userCompany, alors null sera retourné.
        $UserCompany->setCompany($connectedCompany);

        $em->persist($UserCompany);
        $em->flush();

        $context = SerializationContext::create()->setGroups(['getUserCompany']);
        $jsonUserCompany = $serializer->serialize($UserCompany, 'json', $context);
        
        $location = $urlGenerator->generate('detailUserCompanies', ['id' => $UserCompany->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUserCompany, Response::HTTP_CREATED, ["Location" => $location], true);
   }


    /**
     * Cette méthode permet de modifier un UserCompany.
     *
     * @OA\Response(
     *     response=200,
     *     description="Modifier un UserCompany",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=UserCompany::class))
     *     )
     * )
     *
     * @OA\RequestBody(
     *     description="User to modify",
     *     required=true,
     *     @Model(type=UserCompany::class, groups={"createUserCompany"})
     * )
     *
     * @OA\Tag(name="UserCompanies")
     */
   #[Route('/api/usercompanies/{id}', name:"updateUserCompanies", methods:['PUT'])]

   public function updateUserCompanies(Request $request, SerializerInterface $serializer, UserCompany $currentUserCompany, EntityManagerInterface $em, CompanyRepository $companyRepository, ValidatorInterface $validator, TagAwareCacheInterface $cache): JsonResponse 
    {
        $newUserCompany = $serializer->deserialize($request->getContent(), UserCompany::class, 'json');
        $currentUserCompany->setLastname($newUserCompany->getLastname());
        $currentUserCompany->setFirstname($newUserCompany->getFirstname());
        

        // On vérifie les erreurs
        $errors = $validator->validate($currentUserCompany);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($currentUserCompany);
        $em->flush();

        // On vide le cache.
        $cache->invalidateTags(["userCompanyCache"]);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
