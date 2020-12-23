<?php

namespace App\Services;

use App\Contracts\WeatherInterface;
use Illuminate\Support\Facades\Http;

class YahooWeather implements WeatherInterface
{
    public function __construct(
        private string $appId,
        private string $baseUrl,
        private string $consumerKey,
        private string $consumerSecret,
        private string $temperatureUnit,
        private string $weatherLocation
    ) {}

    public function fetch(): array
    {
        [$url, $headers] = $this->prepareRequest();

        $response = Http::withHeaders($headers)->get($url);

        return $response->json();
    }

    private function prepareRequest(): array
    {
        $parameters = [
            'format' => 'json',
            'location' => $this->weatherLocation,
            'u' => $this->temperatureUnit,
        ];

        $headers = $this->buildHeaders($parameters);
        $url = $this->baseUrl . '?' . http_build_query($parameters);

        return [$url, $headers];
    }

    private function buildHeaders(array $parameters): array
    {
        return [
            'Authorization' => $this->buildAuthorizationHeader($parameters),
            'Yahoo-App-Id' => $this->appId,
        ];
    }

    private function buildAuthorizationHeader(array $parameters): string
    {
        $prefix = 'OAuth ';

        $oauth = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => uniqid(mt_rand(1, 1000)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0',
        ];

        $oauth['oauth_signature'] = $this->buildOauthSignature($oauth, $parameters);

        $encodedValues = collect($oauth)->flatMap(function ($value, $key) {
            return [$key . '="' . rawurlencode($value) . '"'];
        })->toArray();

        return $prefix . implode(' ,', $encodedValues);
    }

    private function buildOauthSignature(array $oauth, array $parameters): string
    {
        $encodedRequestUrl = $this->buildEncodedRequestUrl($oauth, $parameters);
        $compositeKey = $this->buildCompositeKey();

        return $this->buildSignature($encodedRequestUrl, $compositeKey);
    }

    private function buildEncodedRequestUrl(array $oauth, array $parameters): string
    {
        $method = 'GET';
        $baseUrl = rawurlencode($this->baseUrl);
        $parameters = collect(array_merge($parameters, $oauth))->sortKeys()->toArray();

        $urlComponents = [
            $method,
            $baseUrl,
            rawurlencode(http_build_query($parameters)),
        ];

        return implode('&', $urlComponents);
    }

    private function buildCompositeKey(): string
    {
        return rawurlencode($this->consumerSecret) . '&';
    }

    private function buildSignature(string $encodedRequestUrl, string $compositeKey): string
    {
        return base64_encode(
            hash_hmac('sha1', $encodedRequestUrl, $compositeKey, true)
        );
    }
}