<?php

namespace FeedsReaderBackend;

/**
 * Class FeedsProvider
 */
abstract class FeedsProvider
{
	abstract function getFeeds();
}