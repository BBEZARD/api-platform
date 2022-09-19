<?php

namespace App\Controller;

use App\Repository\AdherentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdherentCountController extends AbstractController {

	/**
	 * @param AdherentRepository $adherentRepository
	 */
	public function __construct( private readonly AdherentRepository $adherentRepository ) {
	}

	public function __invoke(): int {

		return $this->adherentRepository->count([]);
	}

}
