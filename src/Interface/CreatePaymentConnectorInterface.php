<?php

namespace App\Interface;

use App\DTO\CreatedPaymentResponseDTO;
use App\DTO\CreatePaymentDTO;

interface CreatePaymentConnectorInterface {

	public function createPayment(CreatePaymentDTO $createPaymentDTO): CreatedPaymentResponseDTO;
	
}
