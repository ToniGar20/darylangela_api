<?php

namespace App\Controller;

use App\Entity\Guest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class EditGuestController {

    #[Route('/api/guests/{id}', methods: ['PATCH'])]
    public function __invoke(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON payload'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $guestRepository = $entityManager->getRepository(Guest::class);
        $guest = $guestRepository->find($id);

        if (!$guest) {
            return new JsonResponse(['error' => 'Guest not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $email = $data['email'] ?? null;

        if ($email) {
            $existingGuest = $guestRepository->findOneBy(['email' => $email]);

            if ($existingGuest && $existingGuest->getId() !== $guest->getId()) {
                return new JsonResponse(
                    ['error' => 'A guest with this email already exists'],
                    JsonResponse::HTTP_CONFLICT
                );
            }

            $guest->setEmail($email);
        }

        if (isset($data['first_name'])) {
            $guest->setFirstName($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $guest->setLastName($data['last_name']);
        }
        if (isset($data['phone_number'])) {
            $guest->setPhoneNumber($data['phone_number']);
        }
        if (isset($data['comments'])) {
            $guest->setComments($data['comments']);
        }
        if (isset($data['attendance'])) {
            $guest->setAttendance($data['attendance']);
        }

        $entityManager->flush();

        return new JsonResponse(
            ['message' => 'Guest updated successfully'],
            JsonResponse::HTTP_OK
        );
    }
}