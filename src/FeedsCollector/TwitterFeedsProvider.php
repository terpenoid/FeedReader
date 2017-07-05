<?php

namespace FeedsReaderBackend;

/**
 * One of implementations of FeedsProvider
 *
 * Class TwitterApiListener
 */
class TwitterFeedsProvider extends FeedsProvider
{

	protected $config;

	public function __construct()
	{
		$this->config = require 'config/twitter_config.php';
	}

	/**
	 * @param string $baseURI
	 * @param string $method
	 * @param array $params
	 * @return string
	 */
	protected function buildBaseString($baseURI, $method, $params)
	{
		$r = array();
		ksort($params);
		foreach ($params as $key => $value) {
			$r[] = $key . '=' . rawurlencode($value);
		}
		return $method . '&' . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
	}

	/**
	 * @param array $oauth
	 * @return string
	 */
	protected function buildAuthorizationHeader($oauth)
	{
		$r = 'Authorization: OAuth ';
		$values = array();
		foreach ($oauth as $key => $value)
			$values[] = $key . '="' . rawurlencode($value) . '"';
		$r .= implode(', ', $values);
		return $r;
	}

	/**
	 * $twitter_timeline can be 'user_timeline', 'mentions_timeline', 'user_timeline', 'home_timeline or retweets_of_me'
	 * $request - if need set count or search-query
	 *
	 * @param string $twitter_timeline
	 * @param array $request
	 * @return array
	 */
	protected function returnTweets($twitter_timeline, $request = [])
	{
		$oauth_access_token = $this->config['oauth_access_token'];
		$oauth_access_token_secret = $this->config['oauth_access_token_secret'];
		$consumer_key = $this->config['consumer_key'];
		$consumer_secret = $this->config['consumer_secret'];

		$oauth = array(
			'oauth_consumer_key' => $consumer_key,
			'oauth_nonce' => time(),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_token' => $oauth_access_token,
			'oauth_timestamp' => time(),
			'oauth_version' => '1.0'
		);

		$oauth = array_merge($oauth, $request);

		$base_info = $this->buildBaseString('https://api.twitter.com/1.1/statuses/' . $twitter_timeline . '.json', 'GET', $oauth);
		$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
		$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
		$oauth['oauth_signature'] = $oauth_signature;

		$header = array($this->buildAuthorizationHeader($oauth), 'Expect:');
		$options = array(CURLOPT_HTTPHEADER => $header,
			CURLOPT_HEADER => false,
			CURLOPT_URL => 'https://api.twitter.com/1.1/statuses/' . $twitter_timeline . '.json?' . http_build_query($request),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false);

		$feed = curl_init();
		curl_setopt_array($feed, $options);
		$json = curl_exec($feed);
		curl_close($feed);

		return json_decode($json, true);
	}

	function getFeeds()
	{
		$tweets = $this->returnTweets('user_timeline', [
			'screen_name' => $this->config['feed_user_name'],
			'count' => $this->config['feeds_count'],
		]);

		$feeds = [];
		foreach ($tweets as $tweet) {
			$feeds[] = [
				'provider' => 'twitter',
				'date' => $tweet['created_at'],
				'text' => $tweet['text'],
			];
		}

		return $feeds;
	}

}