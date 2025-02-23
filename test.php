<?php
/**
 *
 *
 * @author Hamish Fleming <fleming_hamish@yahoo.com>
 * @version $Id$
 * @copyright Hamish Fleming <fleming_hamish@yahoo.com>, 11 March, 2024
 * @package default
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use HamishVexFleming\KeycloakApi\KeycloakClient;

$kc = KeycloakClient::factory(
	array(
		'realm'         => 'wp',
		'baseUri'       => 'http://localhost:7070',
		'grant_type'    => 'client_credentials',
		'client_id'     => 'wp',
		'client_secret' => 'tLi4CwiAmfvlwTH56kWaGgWDadEbLnBG',
	)
);

$token = $kc->getToken();
var_dump( $token );

$users = $kc->getUsers();
var_dump( $users );
$kc->createUser(
	array(
		'username'    => 'test',
		'email'       => 'test@test.com',
		'enabled'     => true,
		'credentials' => array(
			array(
				'type'  => 'password',
				'value' => '1234',
			),
		),
	)
);


$search = $kc->getUsers( array( 'search' => 'test' ) );
var_dump( $search );

$impersonation = $kc->impersonateUser( array( 'id' => '7056709b-470d-4a70-b765-b5d322dfb2b7' ) );
var_dump( $impersonation );
