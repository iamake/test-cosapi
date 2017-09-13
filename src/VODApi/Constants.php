<?php

namespace TencentTH\VODApi;

use Aws\S3\S3Client;

class Constants {

  public static $API_REQUEST = 'vod.api.qcloud.com/v2/index.php';
  public static $API_ROOT = 'https://vod.api.qcloud.com/v2/index.php';
  public static $SECRET_ID = 'AKIDGvUs58oICvWxDIFxX9g2DE4jDXge2KQM';
  public static $SECRET_KEY = 'uhJm5RglkaXRCqKeR6g6LPHyUq23ua6Q';

  /**
   * @return Testcase[]
   */
  public static function getTestcases(S3Client $s3Client, S3Client $s3Service) {
    return [
      'listBuckets' => new Testcase(
        'listBuckets',
        function () use ($s3Service) {
          return [
            'objects' => $s3Service->listBuckets('test-1252569596'),
          ];
        },
        FALSE
      ),
      'createPresignedUrl' => new Testcase(
        'createPresignedUrl',
        function () use ($s3Client) {
          $command = $s3Client->getCommand('GetObject', [
            'Bucket' => 'test-1252569596',
            'Key' => 'result.txt',
          ]);
          return [
            'url' => $command->createPresignedUrl('+10 minutes'),
          ];
        },
        TRUE
      ),
      'getObjectUrl' => new Testcase(
        'getObjectUrl',
        function () use ($s3Client) {
          return [
            'url' => $s3Client->getObjectUrl('test-1252569596', 'result.txt', '+10 minutes'),
          ];
        },
        TRUE
      ),
    ];
  }

  public static function getTestcaseSource($func) {
    $reflection = new \ReflectionFunction($func);
    $file = file(__FILE__);
    $source = array_slice(
      $file,
      $reflection->getStartLine(),
      $reflection->getEndLine() - $reflection->getStartLine() - 1
    );

    return implode("", $source);
  }

}
