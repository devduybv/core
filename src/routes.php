<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->post('file/upload', 'VCComponent\Laravel\Vicoders\Core\Controllers\FileController@upload');
    $api->post('file/upload/s3', 'VCComponent\Laravel\Vicoders\Core\Controllers\FileController@uploadS3');

    //v2
    $api->post('v2/file/upload', 'VCComponent\Laravel\Vicoders\Core\Controllers\v2\FileController@upload');
    $api->post('v2/file/upload/s3', 'VCComponent\Laravel\Vicoders\Core\Controllers\v2\FileController@uploadS3');
});
