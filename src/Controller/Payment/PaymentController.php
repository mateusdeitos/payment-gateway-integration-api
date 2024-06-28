<?php

namespace App\Controller\Payment;

use App\DTO\CreatePaymentDTO;
use App\Enum\ConnectorIntegrationEnum;
use App\Service\Payment\CreatePaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController {

	#[Route('/api/v1/{connectorSlug}/payment', name: 'create-payment', methods: ['POST'], format: 'json')]
	public function createPayment(
		CreatePaymentService $createPaymentService,
		ConnectorIntegrationEnum $connectorSlug,
		#[MapRequestPayload()] CreatePaymentDTO $createPaymentDTO
	) {
		$response = $createPaymentService->run($connectorSlug, $createPaymentDTO);
		return $this->json($response);
	}
}
