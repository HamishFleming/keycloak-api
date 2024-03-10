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

namespace HamishVexFleming\KeycloakApi\TokenStorages;

/**
 * RuntimeTokenStorage
 *
 * @package HamishVexFleming\KeycloakApi\TokenStorage
 */
class RuntimeTokenStorage implements TokenStorage {
	/**
	 * @var ?array
	 */
	private $token;

	/**
	 * getToken
	 *
	 * @return ?array
	 * */
	public function getToken(): ?array {
		return $this->token;
	}

	/**
	 * saveToken
	 *
	 * @param array $token
	 * @return void
	 * */
	public function saveToken( array $token ): void {
		$this->token = $token;
	}
}
