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

interface TokenStorage {
	/**
	 * @return array|null
	 */
	public function getToken(): ?array;

	/**
	 * @param string $token
	 */
	/* public function setToken( string $token ): void; */

	/**
	 * @param array $token
	 */
	public function saveToken( array $token ): void;

}
