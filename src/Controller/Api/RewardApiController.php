<?php
// src/Controller/Api/RewardApiController.php
namespace App\Controller\Api;

use App\Entity\Reward;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RewardApiController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/api/rewards', name: 'api_reward_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $rewards = $this->entityManager->getRepository(Reward::class)->findAll();

        $jsonContent = $this->serializer->serialize($rewards, 'json');

        return new JsonResponse($jsonContent, 200, [], true);
    }

    #[Route('/api/rewards', name: 'api_reward_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $reward = new Reward();
        $reward->setName($data['name']);
        $reward->setDescription($data['description']);

        $entityManager->persist($reward);
        $entityManager->flush();

        return $this->json($reward, 201);
    }

    #[Route('/api/rewards/{id}', name: 'api_reward_show', methods: ['GET'])]
    public function show(Reward $reward): JsonResponse
    {
        return $this->json($reward);
    }

    #[Route('/api/rewards/{id}', name: 'api_reward_update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, Reward $reward, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $reward->setName($data['name']);
        }

        if (isset($data['description'])) {
            $reward->setDescription($data['description']);
        }

        $entityManager->flush();

        return $this->json($reward);
    }

    #[Route('/api/rewards/{id}', name: 'api_reward_delete', methods: ['DELETE'])]
    public function delete(Reward $reward, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($reward);
        $entityManager->flush();

        return $this->json(null, 204);
    }
}
