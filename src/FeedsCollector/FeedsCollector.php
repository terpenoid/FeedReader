<?php

namespace FeedsReaderBackend;

class FeedsCollector
{

	protected $providers = [];

	public function addProvider($providerClass)
	{
		$this->providers[] = new $providerClass();
	}

	public function getAllFeeds()
	{

		$feeds = [];
		foreach ($this->providers as $provider) {
			$feeds = array_merge($feeds, $provider->getFeeds());
		}

		return $feeds;
	}

}