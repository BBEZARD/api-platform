<?php

namespace App\Controller;

use App\Entity\Adherent;

class AdherentPaidController {

	/**
	 * @param Adherent $data
	 *
	 * @return Adherent
	 */
	public function __invoke( Adherent $data ): Adherent {
		$data->setPayment( 1 );

		return $data;
	}

}
