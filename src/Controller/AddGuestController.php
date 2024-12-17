<?php

namespace App\Controller;

use App\Entity\Guest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AddGuestController extends AbstractController
{
    #[Route('/api/guests', methods: ['POST'])]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;

        if (!$email) {
            return new JsonResponse(['error' => 'Email is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $guestRepository = $entityManager->getRepository(Guest::class);
        $existingGuest = $guestRepository->findOneBy(['email' => $email]);

        if ($existingGuest) {
            return new JsonResponse(
                ['error' => 'A guest with this email already exists'],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $guest = new Guest();
        $guest->setEmail($email);
        $guest->setFirstName($data['firstName'] ?? '');
        $guest->setLastName($data['lastName'] ?? '');
        $guest->setPhoneNumber($data['phoneNumber'] ?? '');
        $guest->setComments($data['comments'] ?? null);
        $guest->setAttendance($data['attendance'] ?? false);

        $entityManager->persist($guest);
        $entityManager->flush();

        return new JsonResponse(
            ['message' => 'Guest created successfully', 'id' => $guest->getId()],
            JsonResponse::HTTP_CREATED
        );
    }
}
