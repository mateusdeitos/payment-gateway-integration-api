<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class PingController extends AbstractController {

	#[Route('/ping', name: 'ping', methods: ['GET'])]
	public function ping() {
		return $this->json([
			'status' => 'ok'
		]);
	}
}
