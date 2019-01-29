<?php
use \GuzzleHttp\Client;

$request_uri = 'https://api.github.com/repos/freeCodeCamp/freeCodeCamp/contents/package.json';

$client = new Client();

$response = $client->request('GET', $request_uri);

$contents = json_decode($response->getBody());
$response['data'] = base64_decode($contents->{'content'});

echo $response;