<?php

namespace App\EventListeners;

use App\Exception\ConnectorException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ErrorEventSubscriber implements EventSubscriberInterface {

	public function __construct(
		private LoggerInterface $logger
	) {}

	public static function getSubscribedEvents(): array {
		return [
			KernelEvents::EXCEPTION => 'onKernelException',
		];
	}

	public function onKernelException(ExceptionEvent $event): void {
		$exception = $event->getThrowable();

		$code = 500;
		$message = 'Internal Server Error';
		if ($exception instanceof HttpExceptionInterface) {
			$code = $exception->getStatusCode();
			$message = $exception->getMessage();
		}

		if ($exception instanceof InvalidArgumentException) {
			$code = 400;
			$message = $exception->getMessage();
		}

		if ($exception instanceof \App\Exception\NotFoundException) {
			$code = 404;
			$message = $exception->getMessage();
		}

		if ($code === 500) {
			$this->logger->critical($exception->getMessage(), ['exception' => $exception]);
		}

		$error = [
			'message' => $message,
		];

		if ($exception instanceof ConnectorException) {
			$error['code'] = $exception->getStatusCode();
			$originalMessage = $exception->getOriginalMessage();
			if (json_validate($originalMessage)) {
				$originalMessage = json_decode($originalMessage, true);
			}

			$error['originalMessage'] = $originalMessage;
		}

		$response = new JsonResponse($error, $code);

		$response->headers->add([
			'X-Error-From-Connector' => $exception instanceof ConnectorException ? '1' : '0',
		]);

		$event->allowCustomResponseCode();
		$event->setResponse($response);
	}
}
