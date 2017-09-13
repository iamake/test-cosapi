<?php

include "vendor/autoload.php";

use Aws\S3\S3Signature;

$SECRET_ID = 'AKIDGvUs58oICvWxDIFxX9g2DE4jDXge2KQM';
$SECRET_KEY = 'uhJm5RglkaXRCqKeR6g6LPHyUq23ua6Q';
$REGION = 'ap-singapore';
$HOST = 'service.cos.myqcloud.com';
$ENDPOINT = 'http://' . $HOST . '/';

$config = [
  'includes' => ['_aws'],
  'services' => [
    'default_settings' => [
      'params' => [
        'key' => $SECRET_ID,
        'secret' => $SECRET_KEY,
        'region' => $REGION,
        'endpoint' => $ENDPOINT,
        'host' => $HOST,
        'signature' => new S3Signature(),
      ]
    ]
  ]
];

return $config;
