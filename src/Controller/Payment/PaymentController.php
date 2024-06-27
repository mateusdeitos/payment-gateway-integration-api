<?php

namespace App\Controller\Payment;

use App\DTO\CreatePaymentDTO;
use App\Enum\ConnectorIntegrationEnum;
use App\Services\Payment\CreatePaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController {

	#[Route('/api/v1/{connectorSlug}/payment', name: 'create-payment', methods: ['POST'])]
	public function createPayment(
		CreatePaymentService $createPaymentService,
		ConnectorIntegrationEnum $connectorSlug,
		// TODO: improve error response for invalid payload
		#[MapRequestPayload()] CreatePaymentDTO $createPaymentDTO
	) {
		$response = $createPaymentService->run($connectorSlug, $createPaymentDTO);
		return $this->json($response);
	}
}
