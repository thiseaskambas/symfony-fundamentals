<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{

    //dependency injection :
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache,
        private bool $isDebug //in services.yaml we bind the 'kernel.debug' parameter value to it
    ){

    }
    public function findAll(): array{
        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter(5);
            $response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });
 }
}