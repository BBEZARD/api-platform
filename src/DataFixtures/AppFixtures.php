<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use App\Entity\Town;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture {

	/**
	 * @inheritDoc
	 */
	public function load( ObjectManager $manager ) {
		$faker = Faker\Factory::create( 'fr_FR' );
		$towns = [];

		for ( $i = 0; $i < 30; $i ++ ) {
			$town = new Town();

			$town->setName( $faker->city() );
			$town->setPostalCode( $faker->postcode() );
			$town->setCreatedAt( $faker->dateTimeBetween( '-2 years', 'now' ) );
			$manager->persist( $town );

			$towns[] = $town;

		}

		for ( $i = 0; $i < 100; $i ++ ) {
			$adherent = new Adherent();

			$adherent->setFirstName( $faker->firstName() );
			$adherent->setLastName( $faker->lastName() );
			$adherent->setBirthDate( $faker->dateTime() );
			$adherent->setPhone( $faker->phoneNumber() );
			$adherent->setPhone( $faker->phoneNumber() );
			$adherent->setEmail( $faker->email() );
			$adherent->setAddress( $faker->address() );
			$adherent->setCreatedAt( $faker->dateTimeBetween( '-10 years', 'now' ) );
			$adherent->setCreatedAt( $faker->dateTimeBetween( '-2 years', 'now' ) );
			$adherent->setTown( $towns[ $faker->numberBetween( 0, 29 ) ] );
			$adherent->setPaidAt( $faker->dateTimeBetween( '-2 years', 'now' ) );
			$adherent->setPayment( $faker->numberBetween( 0, 0 ) );
			$adherent->setReceipt( $faker->numberBetween( 0, 0 ) );
			$adherent->setWorkshop( $faker->numberBetween( 0, 1 ) );
			$manager->persist( $adherent );
		}

		$manager->flush();
	}
}
