<?php

namespace App;

class Graph
{
	private static $client;

	private static function client()
	{
		if (! static::$client) {
			static::$client = \Laudis\Neo4j\ClientBuilder::create()
				->addBoltConnection('default', 'bolt://neo4j:secret123@localhost')
				->setDefaultConnection('default')
				->build();
		}
		
		return static::$client;
	}

	public static function run($command)
	{
		return static::client()->run($command);
	}
}