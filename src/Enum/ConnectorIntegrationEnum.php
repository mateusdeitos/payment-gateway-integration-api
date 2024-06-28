<?php

namespace App\Enum;

/**
 * Enum for all available payment gateway connectors
 */
enum ConnectorIntegrationEnum: string {
	case SHIFT_4 = 'shift4';
	case ACI = 'aci';
}
