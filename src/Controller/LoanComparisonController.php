<?php

// src/Controller/LoanComparisonController.php

namespace App\Controller;

use App\DTO\LoanRequestDTO;
use App\Repository\LoanRateRepository;
use App\Service\LoanComparisonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class LoanComparisonController extends AbstractController
{
    public function __construct(
        private readonly LoanComparisonService $comparisonService,
        private readonly LoanRateRepository $loanRateRepository,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/loans/compare', name: 'loans_compare', methods: ['POST'])]
    public function compare(Request $request): JsonResponse
    {
        try {
            $loanRequest = $this->serializer->deserialize(
                $request->getContent(),
                LoanRequestDTO::class,
                'json'
            );

            $errors = $this->validator->validate($loanRequest);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $results = $this->comparisonService->findBestRates(
                $loanRequest->amount,
                $loanRequest->duration
            );

            return new JsonResponse([
                'request' => [
                    'amount'   => $loanRequest->amount,
                    'duration' => $loanRequest->duration,
                ],
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Une erreur est survenue lors du traitement de votre demande',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/loan_rates', name: 'loan_rates_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $page         = $request->query->getInt('page', 1);
        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $amount       = $request->query->get('amount') ? (int) $request->query->get('amount') : null;
        $duration     = $request->query->get('duration') ? (int) $request->query->get('duration') : null;

        $result = $this->loanRateRepository->findAll($page, $itemsPerPage, $amount, $duration);

        return new JsonResponse([
            'hydra:member' => array_map(function ($rate) {
                return [
                    'id'       => $rate->getId(),
                    'amount'   => $rate->getAmount(),
                    'duration' => $rate->getDuration(),
                    'rate'     => $rate->getRate(),
                    'partner'  => [
                        'id'   => $rate->getPartner()->getId(),
                        'name' => $rate->getPartner()->getName(),
                    ],
                ];
            }, $result['items']),
            'hydra:totalItems' => $result['total'],
            'hydra:view'       => [
                'hydra:first'    => $this->generateUrl('api_loan_rates_list', ['page' => 1]),
                'hydra:last'     => $this->generateUrl('api_loan_rates_list', ['page' => $result['totalPages']]),
                'hydra:previous' => $page > 1 ? $this->generateUrl('api_loan_rates_list', ['page' => $page - 1]) : null,
                'hydra:next'     => $page < $result['totalPages'] ? $this->generateUrl('api_loan_rates_list', ['page' => $page + 1]) : null,
            ],
        ]);
    }
}
