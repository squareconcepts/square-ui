<?php

namespace Squareconcepts\SquareUi\Helpers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Wireable;

class ScFontAwesome implements Wireable
{
    private const CACHE_KEY = 'square-ui-fontawesome-access-token';

    private ?string $accessToken = null;
    private ?Carbon $expiresAt = null;

    public function __construct()
    {
        if (empty(config('square-ui.fontawesome_api_token'))) {
            throw new \Exception('Please provide font awesome api token');
        }
    }

    private function authenticate(): bool
    {
        $cached = Cache::get(self::CACHE_KEY);

        if (!empty($cached['access_token']) && !empty($cached['expires_at'])) {
            $this->accessToken = $cached['access_token'];
            $this->expiresAt = Carbon::createFromTimestamp($cached['expires_at']);
            return true;
        }

        try {
            $response = Http::withToken(config('square-ui.fontawesome_api_token'))
                ->timeout(10)
                ->connectTimeout(5)
                ->post('https://api.fontawesome.com/token');
        } catch (ConnectionException) {
            return false;
        }

        if ($response->failed()) {
            return false;
        }

        $json = $response->json();

        if (!empty($json['access_token']) && !empty($json['expires_in'])) {
            // Een minuut marge zodat we nooit een net verlopen token uit de cache gebruiken
            $expiresAt = now()->addSeconds(max((int) $json['expires_in'] - 60, 60));

            $this->accessToken = $json['access_token'];
            $this->expiresAt = $expiresAt;
            Cache::put(self::CACHE_KEY, [
                'access_token' => $json['access_token'],
                'expires_at' => $expiresAt->getTimestamp(),
            ], $expiresAt);
            return true;
        }

        return false;
    }

    public function searchIcon(string $search): array
    {
        if (empty($this->accessToken) || empty($this->expiresAt) || now()->isAfter($this->expiresAt)) {
            if (!$this->authenticate()) {
                return ['success' => false, 'message' => 'Something went wrong while trying to authenticate'];
            }
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->timeout(10)
                ->connectTimeout(5)
                ->post('https://api.fontawesome.com', [
                    'query' => 'query ($query: String!) {
                        search(version: "6.0.0", query: $query, first: 500) {
                            id
                            label
                            styles
                        }
                    }',
                    'variables' => ['query' => $search],
                ]);
        } catch (ConnectionException) {
            return ['success' => false, 'message' => 'Something went wrong while trying to search for icons'];
        }

        $json = $response->json();

        if (!empty($json['data']['search'])) {
            $icons = array_map(fn($item) => FontAwesomeIcon::make(
                $item['id'] ?? '',
                $item['label'] ?? '',
                $item['styles'] ?? [],
            ), $json['data']['search']);

            return ['success' => true, 'data' => $icons];
        }

        return ['success' => false, 'message' => 'Something went wrong while trying to search for icons'];
    }

    public static function getStyles(): array
    {
        return [
            ['name' => 'Solid', 'value' => 'fa-solid'],
            ['name' => 'Brands', 'value' => 'fa-brands'],
            ['name' => 'Regular', 'value' => 'fa-regular'],
            ['name' => 'Light', 'value' => 'fa-light'],
            ['name' => 'Thin', 'value' => 'fa-thin'],
            ['name' => 'Duotone', 'value' => 'fa-duotone'],
            ['name' => 'Sharp Solid', 'value' => 'fa-sharp fa-solid'],
        ];
    }

    // Het access token mag nooit in de Livewire snapshot belanden; na hydratie wordt het opnieuw uit de cache gehaald.
    public static function fromLivewire($value): static
    {
        return new static();
    }

    public function toLivewire(): array
    {
        return [];
    }
}
