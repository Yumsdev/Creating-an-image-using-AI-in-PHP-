<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$apiKey = 'YOUR_OPENAI_API_KEY';
$client = new Client([
    'base_uri' => 'https://api.openai.com/v1/',
]);

try {
    $response = $client->request('POST', 'images/generations', [
        'headers' => [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'prompt' => 'a beautiful landscape with mountains and a river',
            'n' => 1,
            'size' => '1024x1024',
        ],
    ]);

    $body = $response->getBody();
    $data = json_decode($body, true);

    if (isset($data['data'][0]['url'])) {
        $imageUrl = $data['data'][0]['url'];
        echo "Image URL: " . $imageUrl;
        // Save the image locally
        $imageContents = file_get_contents($imageUrl);
        file_put_contents('generated_image.png', $imageContents);
        echo "Image saved as generated_image.png";
    } else {
        echo "No image URL found in the response.";
    }
} catch (RequestException $e) {
    echo "Request failed: " . $e->getMessage();
}

?>
