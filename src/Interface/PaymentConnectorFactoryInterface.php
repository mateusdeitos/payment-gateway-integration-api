<?php

namespace App\Interface;

use App\Enum\ConnectorIntegrationEnum;

interface PaymentConnectorFactoryInterface {

	public function getInstanceForCreatePayment(ConnectorIntegrationEnum $connectorSlug): CreatePaymentConnectorInterface;

}
