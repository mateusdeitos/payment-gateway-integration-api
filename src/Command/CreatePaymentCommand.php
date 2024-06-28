<?php

namespace App\Command;

use App\DTO\CreatePaymentDTO;
use App\Enum\ConnectorIntegrationEnum;
use App\Services\ConstraintViolationParserService;
use App\Services\Payment\CreatePaymentService;
use ReflectionProperty;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:create-payment')]
class CreatePaymentCommand extends Command {

	public function __construct(
		private ValidatorInterface $validator,
		private CreatePaymentService $createPaymentService,
		private SerializerInterface $serializer,
	) {
		parent::__construct();	
	}

	protected function configure(): void {
		$this
			->setDescription('Create a payment')
			->addArgument('connectorSlug', mode: InputArgument::REQUIRED, description: 'The connector slug')
		;

		$reflection = new \ReflectionClass(CreatePaymentDTO::class);
		
		foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			$this->addOption(
				$property->name,
				mode: InputOption::VALUE_REQUIRED,
				description: $property->getDocComment() ?: $property->getName(),
				default: null
			);
		}
	}
	
	public function run(InputInterface $input, OutputInterface $output): int {
		return match ($this->validateInput($input, $output)) {
			Command::SUCCESS => $this->createPayment($input, $output),
			Command::INVALID => Command::INVALID,
			default => Command::FAILURE
		};
	}

	private function createPayment(InputInterface $input, OutputInterface $output): int {
		$createPaymentDTO = new CreatePaymentDTO();
		$createPaymentDTO->amount = intval($input->getOption('amount'));
		$createPaymentDTO->currency = strval($input->getOption('currency'));
		$createPaymentDTO->cardNumber = strval($input->getOption('cardNumber'));
		$createPaymentDTO->cardExpYear = intval($input->getOption('cardExpYear'));
		$createPaymentDTO->cardExpMonth = intval($input->getOption('cardExpMonth'));
		$createPaymentDTO->cardCvv = intval($input->getOption('cardCvv'));

		$errors = ConstraintViolationParserService::parse($this->validator->validate($createPaymentDTO));
		if (count($errors) > 0) {
			$output->writeln("<bg=red>Validation Errors:</>");
			$output->writeln((string) $errors);
			return Command::FAILURE;
		}

		$connectorSlug = ConnectorIntegrationEnum::from($input->getArgument('connectorSlug'));
		$response = $this->createPaymentService->run($connectorSlug, $createPaymentDTO);
		$output->writeln("<bg=green>Payment created:</>");
		
		foreach ($response as $property => $value) {
			$output->writeln($property . ": " . $this->serializer->serialize($value, 'json'));
		}

		return Command::SUCCESS;
	}

	private function validateInput(InputInterface $input, OutputInterface $output): int {
		$createPaymentDTO = new CreatePaymentDTO();
		$reflection = new \ReflectionClass($createPaymentDTO);

		$properties = array_reduce(
			$reflection->getProperties(ReflectionProperty::IS_PUBLIC),
			fn ($carry, ReflectionProperty $property) => array_merge($carry, [$property->getName() => $property]),
			[]
		);

		foreach ($createPaymentDTO as $property => $value) {
			$required = count($properties[$property]->getAttributes(NotBlank::class)) > 0;
			if ($input->getOption($property) === null && $required) {
				$output->writeln("<bg=red>Required option missing:</> $property");
				return Command::INVALID;
			}
		}

		return Command::SUCCESS;
	}
}
