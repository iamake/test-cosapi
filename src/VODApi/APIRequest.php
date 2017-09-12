<?php

namespace TencentTH\VODApi;

use GuzzleHttp\Client;

class APIRequest {
  /** @var Client */
  protected static $client;
  public $method;
  public $params;

  public static function init() {
    self::$client = new Client();
  }

  public function __construct($method, $params) {
    $this->method = $method;
    $this->params = $params;
  }

  public function execute() {
    $nonce = rand(0, 1000000);
    $timestamp = time();

    $params = $this->params + [
        'Nonce' => $nonce,
        'Timestamp' => $timestamp,
        'SecretId' => Constants::$SECRET_ID,
      ];
    ksort($params);

    $signed_plain = sprintf('%s%s?%s', $this->method, Constants::$API_REQUEST, http_build_query($params));
    $signed_hashed = base64_encode(hash_hmac('sha1', $signed_plain, Constants::$SECRET_KEY, true));
    $params += [
      'Signature' => $signed_hashed
    ];

    return self::$client->request($this->method, Constants::$API_ROOT, ['query' => $params]);
  }
}
APIRequest::init();
