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
        'List Buckets',
        function () use ($s3Service) {
          return [
            'objects' => $s3Service->listBuckets(),
          ];
        },
        TRUE
      ),
      'createBucket' => new Testcase(
        'Create a Bucket',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->createBucket([
              'Bucket' => 'testcreatebucket-1252569596'
            ]),
          ];
        },
        TRUE
      ),
      'deleteBucket' => new Testcase(
        'Delete a Bucket',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->deleteBucket([
              'Bucket' => 'testcreatebucket-1252569596'
            ]),
          ];
        },
        TRUE
      ),
//      'createPresignedUrl' => new Testcase(
//        'Create a Presigned Url',
//        function () use ($s3Client) {
//          $command = $s3Client->getCommand('GetObject', [
//            'Bucket' => 'test-1252569596',
//            'Key' => 'result.txt',
//          ]);
//          return [
//            'url' => $command->createPresignedUrl('+10 minutes'),
//          ];
//        },
//        TRUE
//      ),
      'getObjectUrl' => new Testcase(
        'Get an Object URL',
        function () use ($s3Client) {
          return [
            'url' => $s3Client->getObjectUrl('test-1252569596', 'result.txt', '+10 minutes'),
          ];
        },
        TRUE
      ),
      'getBucket' => new Testcase(
        'Get a Bucket',
        function () use ($s3Client) {
          return [
            'url' => $s3Client->getBucket([
              'Bucket' => 'test-1252569596'
            ]),
          ];
        },
        FALSE,
        'Expect standard AWS S3 method'
      ),
      'putObject' => new Testcase(
        'Put an Object',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->putObject([
              'Bucket' => 'test-1252569596',
              'Key' => 'test-create-object.txt',
              'Body' => '123456789012345678901234567890',
            ]),
          ];
        },
        FALSE,
        'Expect exception or error messages when put the duplicated file'
      ),
      'upload-1kb' => new Testcase(
        'Upload <1 KB',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->upload(
              'test-1252569596',
              'test-upload-1kb.txt',
              file_get_contents('data/test.txt')
            ),
          ];
        },
        FALSE,
        'Expect error message when uploading duplicated filename'
      ),
      'upload-2mb' => new Testcase(
        'Upload <2 MB',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->upload(
              'test-1252569596',
              'test-upload-2mb.jpg',
              file_get_contents('data/test.jpg')
            ),
          ];
        },
        FALSE,
        'Expect error message when uploading duplicated filename'
      ),
      'upload-8mb' => new Testcase(
        'Upload <8 MB',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->upload(
              'test-1252569596',
              'test-upload-8mb.png',
              file_get_contents('data/test.png')
            ),
          ];
        },
        FALSE,
        'Expect error message when uploading duplicated filename'
      ),
      'upload-30mb' => new Testcase(
        'Upload <30 MB',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->upload(
              'test-1252569596',
              'test-upload-8mb.mp4',
              file_get_contents('data/test.mp4')
            ),
          ];
        },
        FALSE,
        'Expect error message when uploading duplicated filename'
      ),
      'getObject' => new Testcase(
        'Get an Object',
        function () use ($s3Client) {
          $result = $s3Client->getObject([
            'Bucket' => 'test-1252569596',
            'Key' => 'test-create-object.txt'
          ]);
          /** @var \Guzzle\Http\EntityBody $body */
          $body = $result->get('Body');
          $body->rewind();
          $content = $body->read($body->getContentLength());
          return [
            'content' =>  $content
          ];
        },
        TRUE
      ),
      'deleteObject' => new Testcase(
        'Delete an Object',
        function () use ($s3Client) {
          return [
            'results' => $s3Client->deleteObject([
              'Bucket' => 'test-1252569596',
              'Key' => 'test-create-object.txt',
            ]),
          ];
        },
        FALSE,
        'Expect exception or error messages when put the duplicated file'
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
