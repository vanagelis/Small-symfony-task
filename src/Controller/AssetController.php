<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\AssetDto;
use App\DTO\GetAssetRequestDTO;
use App\Entity\Asset;
use App\Entity\User;
use App\Repository\AssetRepository;
use App\Service\AssetService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/assets')]
class AssetController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AssetService $assetService,
        private readonly ValidationService $validationService,
    ) {
    }

    #[Route('', name: 'create_asset', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->entityManager->getRepository(User::class)->find($data['user_id']);
        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $assetDto = (new AssetDto())
            ->setLabel($data['label'])
            ->setCurrency($data['currency'])
            ->setValue($data['value'])
            ->setUser($user);

        $errors = $this->validationService->validate($assetDto);
        if (!empty($errors)) {
            return new JsonResponse(
                [
                    'errors' => $errors,
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }
        $asset = (new Asset())
            ->setLabel($data['label'])
            ->setCurrency($data['currency'])
            ->setValue($data['value'])
            ->setUser($user);

        $this->entityManager->persist($asset);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Asset created successfully',
            'id' => $asset->getId()],
            Response::HTTP_CREATED,
        );
    }

    #[Route('', name: 'get_assets', methods: ['GET'])]
    public function getAll(AssetRepository $repository): JsonResponse
    {
        $assets = $repository->findAll();
        $data = array_map(function (Asset $asset) {
            $valueInUsd = $this->assetService->getAssetValueInUsd($asset->getCurrency());

            return (new GetAssetRequestDTO())
                ->setId($asset->getId())
                ->setLabel($asset->getLabel())
                ->setCurrency($asset->getCurrency())
                ->setValue($asset->getValue())
                ->setUserId($asset->getUser()->getId())
                ->setValueInUsd($valueInUsd)
                ->setExchangeRate($valueInUsd);
        }, $assets);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'get_asset', methods: ['GET'])]
    public function get(Asset $asset): JsonResponse
    {
        $valueInUsd = $this->assetService->getAssetValueInUsd($asset->getCurrency());

        $dto = (new GetAssetRequestDTO())
            ->setId($asset->getId())
            ->setLabel($asset->getLabel())
            ->setCurrency($asset->getCurrency())
            ->setValue($asset->getValue())
            ->setUserId($asset->getUser()->getId())
            ->setValueInUsd($valueInUsd)
            ->setExchangeRate($valueInUsd);

        return $this->json($dto);
    }

    #[Route('/{id}', name: 'update_asset', methods: ['PUT'])]
    public function update(Request $request, Asset $asset): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = (new AssetDto())
            ->setValue($data['value'] ?? $asset->getValue())
            ->setLabel($data['label'] ?? $asset->getLabel())
            ->setCurrency($data['currency'] ?? $asset->getCurrency());

        $errors = $this->validationService->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $asset
            ->setLabel($data['label'] ?? $asset->getLabel())
            ->setCurrency($data['currency'] ?? $asset->getCurrency())
            ->setValue($data['value'] ?? $asset->getValue());

        $this->entityManager->persist($asset);
        $this->entityManager->flush();

        return $this->json(['message' => 'Asset updated successfully']);
    }

    #[Route('/{id}', name: 'delete_asset', methods: ['DELETE'])]
    public function delete(Asset $asset): JsonResponse
    {
        $this->entityManager->remove($asset);
        $this->entityManager->flush();

        return $this->json(['message' => 'Asset deleted successfully']);
    }
}