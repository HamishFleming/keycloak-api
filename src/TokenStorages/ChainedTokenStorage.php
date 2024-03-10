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
 * ChainedTokenStorage
 *
 * @package HamishVexFleming\KeycloakApi\TokenStorage
 */
class ChainedTokenStorage implements TokenStorage {
	/**
	 * @var TokenStorage[]
	 */
	private $tokenStorages;

	/**
	 * @param TokenStorage[] $tokenStorages
	 */
	public function __construct( array $tokenStorages ) {
		$this->tokenStorages = $tokenStorages;
	}

	/**
	 * @return string
	 */
	public function getToken(): string {
		foreach ( $this->tokenStorages as $tokenStorage ) {
			$token = $tokenStorage->getToken();
			if ( ! empty( $token ) ) {
				return $token;
			}
		}

		return '';
	}

	/**
	 * @param string $token
	 */
	public function setToken( string $token ): void {
		foreach ( $this->tokenStorages as $tokenStorage ) {
			$tokenStorage->setToken( $token );
		}
	}

	/**
	 * @param TokenStorage $tokenStorage
	 */
	public function saveTokenStorage( TokenStorage $tokenStorage ): void {
		$this->tokenStorages[] = $tokenStorage;
	}

	/**
	 * @param TokenStorage $tokenStorage
	 */
	public function removeTokenStorage( TokenStorage $tokenStorage ): void {
		$key = array_search( $tokenStorage, $this->tokenStorages, true );
		if ( $key !== false ) {
			unset( $this->tokenStorages[ $key ] );
		}
	}
}
