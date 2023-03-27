<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    private HttpClientInterface $httpClient;
    private CacheInterface $cache;
    public function __construct(HttpClientInterface $httpClient, CacheInterface $cache){
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }
    public function findAll(): array{
        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter(5);
            $response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });
 }
}