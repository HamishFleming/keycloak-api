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

namespace HamishVexFleming\KeycloakApi;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\Serializer;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use HamishVexFleming\KeycloakApi\TokenStorages\RuntimeTokenStorage as RuntimeTokenStorage;
use HamishVexFleming\KeycloakApi\TokenStorages\TokenStorage;
use HamishVexFleming\KeycloakApi\TokenStorages\ChainedTokenStorage;
use HamishVexFleming\KeycloakApi\Middleware\RefreshToken;

/**
 * KeycloakClient
 *
 * @package HamishVexFleming\Keycloak
 */
class KeycloakClient extends GuzzleClient {

	public static function factory( array $config = array() ): self {
		$defaultConfig = array(
			'apiVersion'        => '1.0',
			'username'          => null,
			'password'          => null,
			'realm'             => 'master',
			'baseUri'           => null,
			'verify'            => true,
			'token_storage'     => new RuntimeTokenStorage(),
			'middlewares'       => array(),
			'custom_operations' => array(),
		);

		$config            = self::parseConfig( $config, $defaultConfig );
		$config['handler'] = self::createHandlerStack( $config );

		$serviceDescription = include __DIR__ . "/Resources/keycloak-{$config['apiVersion']}.php";
		$description        = self::mergeCustomOperations( $serviceDescription, $config['custom_operations'] );

		return new static(
			new Client( $config ),
			new Description( $description ),
			null,
			function ( Response $response ) {
				return json_decode( $response->getBody()->getContents(), true ) ?? array( 'content' => $response->getBody() );
			},
			null,
			$config
		);
	}

	public function getCommand( $name, $params = array() ): array|\GuzzleHttp\Command\Command {
		if ( ! isset( $params['realm'] ) ) {
			$params['realm'] = $this->getRealmName();
		}
		return parent::getCommand( $name, $params );
	}

	public function setBaseUri( string $baseUri ): void {
		$this->setConfig( 'baseUri', $baseUri );
	}

	public function getBaseUri(): ?string {
		return $this->getConfig( 'baseUri' );
	}

	public function setRealmName( string $realm ): void {
		$this->setConfig( 'realm', $realm );
	}

	public function getRealmName(): ?string {
		return $this->getConfig( 'realm' );
	}

	public function setVersion( string $version ): void {
		$this->setConfig( 'apiVersion', $version );
	}

	public function getVersion(): ?string {
		return $this->getConfig( 'apiVersion' );
	}

	protected static function parseConfig( array $config, array $default ): array {
		return array_merge( $default, $config );
	}

	protected static function createHandlerStack( array $config ): HandlerStack {
		$stack = new HandlerStack();
		$stack->setHandler( new CurlHandler() );

		foreach ( $config['middlewares'] as $middleware ) {
			if ( is_callable( $middleware ) ) {
				$stack->push( $middleware );
			}
		}

		$stack->push( new RefreshToken( $config['token_storage'] ) );

		return $stack;
	}

	protected static function mergeCustomOperations( array $description, array $customOperations ): array {
		foreach ( $customOperations as $operationKey => $operation ) {
			if ( ! isset( $description['operations'][ $operationKey ] ) ) {
				$description['operations'][ $operationKey ] = $operation;
			}
		}

		return $description;
	}
}
