<?php

namespace App\Factory\Payment;

use App\Enum\ConnectorIntegrationEnum;
use App\Interface\PaymentConnectorFactoryInterface;
use App\Interface\CreatePaymentConnectorInterface;
use Symfony\Component\DependencyInjection\Argument\ServiceLocator;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class CreatePaymentConnectorFactory implements PaymentConnectorFactoryInterface {

	public function __construct(
		#[AutowireLocator([
			ConnectorIntegrationEnum::SHIFT_4->value => \App\Connector\Payment\Shift4\Shift4PaymentConnector::class,
			ConnectorIntegrationEnum::ACI->value => \App\Connector\Payment\ACI\ACIPaymentConnector::class,
		])]
		private ServiceLocator $paymentConnectors
	)
	{
		
	}

	public function getInstanceForCreatePayment(ConnectorIntegrationEnum $connectorSlug): CreatePaymentConnectorInterface {
		$connector = $this->paymentConnectors->get($connectorSlug->value);
		if (!$connector instanceof CreatePaymentConnectorInterface) {
			throw new \InvalidArgumentException('Payment connector not found');
		}

		return $connector;		
	}
}
