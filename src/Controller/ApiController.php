<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\RegionRepository;
use App\Repository\DepartementRepository;
use App\Repository\CommuneRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    #[Route('/api/regions', name: 'app_api_regions')]
    public function addRegionByApi(SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $regionJson = file_get_contents("https://geo.api.gouv.fr/regions");
        
        /* Méthode 1 */
        //$regionTab = $serializer->decode($regionJson, "json");
        //$regionObject = $serializer->denormalize($regionTab, "App\Entity\Region[]");

        /* Méthode 2*/
        $regionObject = $serializer->deserialize($regionJson, "App\Entity\Region[]", 'json');

        $em = $doctrine->getManager();

        foreach($regionObject as $region){
            $em->persist($region);
        }

        $em->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        
    }

    #[Route('/api/regions/show', name: 'app_regions_show')]
    public function showRegions(SerializerInterface $serializer, RegionRepository $regionRepository, ManagerRegistry $doctrine): Response
    {
        
        $regions = $regionRepository->findAll();

        /* Méthode 2*/
        $regionsObject = $serializer->serialize($regions, 'json');

        return new JsonResponse($regionsObject, Response::HTTP_CREATED, [], true);

        //dd($regionsObject);

        
    }

    #[Route('/api/regions/add', name: 'app_regions_add')]
    public function addRegion(SerializerInterface $serializer, ValidatorInterface $validator, Request $request, RegionRepository $regionRepository, ManagerRegistry $doctrine): Response
    {

        $regions = $request->getContent();

        $em = $doctrine->getManager();
        
        $regionObject = $serializer->deserialize($regions, "App\Entity\Region[]", 'json');

        foreach($regionObject as $region){
            
            $errors = $validator->validate($region);

            if(count($errors) > 0){
                $errorsString = (string) $errors;
                return new Response($errorsString);
            } else {
                $em->persist($region);
            }

            
        }

        $em->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);

        
    }

    #[Route('/api/departements', name: 'app_api_departement')]
    public function addDepartementByApi(SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $departementsJson = file_get_contents("https://geo.api.gouv.fr/departements");
        
        /* Méthode 1 */
        //$regionTab = $serializer->decode($regionJson, "json");
        //$regionObject = $serializer->denormalize($regionTab, "App\Entity\Region[]");

        /* Méthode 2*/
        $departementsObject = $serializer->deserialize($departementsJson, "App\Entity\Departement[]", 'json');

        $em = $doctrine->getManager();

        foreach($departementsObject as $departement){
            $em->persist($departement);
        }

        $em->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        
    }

    #[Route('/api/departements/show', name: 'app_departements_show')]
    public function showDepartements(SerializerInterface $serializer, DepartementRepository $departementRepository, ManagerRegistry $doctrine): Response
    {
        
        $departements = $departementRepository->findAll();

        /* Méthode 2*/
        $departementsObject = $serializer->serialize($departements, 'json');

        return new JsonResponse($departementsObject, Response::HTTP_CREATED, [], true);

        
    }

    #[Route('/api/departements/add', name: 'app_departements_add')]
    public function addDepartement(SerializerInterface $serializer, ValidatorInterface $validator, Request $request, ManagerRegistry $doctrine): Response
    {

        $departements = $request->getContent();

        $em = $doctrine->getManager();
        
        $departementsObject = $serializer->deserialize($departements, "App\Entity\Departement[]", 'json');

        foreach($departementsObject as $departement){
            $errors = $validator->validate($departement);

            if(count($errors) > 0){
                $errorsString = (string) $errors;
                return new Response($errorsString);
            } else {
                $em->persist($departement);
            }

            
        }

        $em->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);

        
    }

    #[Route('/api/communes', name: 'app_api_communes')]
    public function addCommunesByApi(SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $communesJson = file_get_contents("https://geo.api.gouv.fr/communes");

        /* Méthode 1 */
        //$regionTab = $serializer->decode($regionJson, "json");
        //$regionObject = $serializer->denormalize($regionTab, "App\Entity\Region[]");

        /* Méthode 2*/
        $communesObject = $serializer->deserialize($communesJson, "App\Entity\Commune[]", 'json');
        $em = $doctrine->getManager();

        foreach($communesObject as $commune){
            $commune->setDepartement(null);
            $em->persist($commune);
        }

        $em->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
        
    }

    #[Route('/api/communes/show', name: 'app_communes_show')]
    public function showCommunes(SerializerInterface $serializer, CommuneRepository $communeRepository, ManagerRegistry $doctrine): Response
    {
        
        $communes = $communeRepository->findAll();

        /* Méthode 2*/
        $communesObject = $serializer->serialize($communes, 'json');

        return new JsonResponse($communesObject, Response::HTTP_CREATED, [], true);

        
    }

    #[Route('/api/communes/add', name: 'app_communes_add')]
    public function addCommune(SerializerInterface $serializer, ValidatorInterface $validator, Request $request, ManagerRegistry $doctrine): Response
    {

        $communes = $request->getContent();

        $em = $doctrine->getManager();
        
        $communesObject = $serializer->deserialize($communes, "App\Entity\Commune[]", 'json');

        foreach($communesObject as $commune){
            //dd($commune);
            $errors = $validator->validate($commune);

            if(count($errors) > 0){
                $errorsString = (string) $errors;
                return new Response($errorsString);
            } else {
                $em->persist($commune);
            }
            
        }

        $em->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);

        
    }

    

}
