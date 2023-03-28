<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MixRepository
{

    //dependency injection :
    public function __construct(
        private HttpClientInterface $githubContentClient,
        private CacheInterface $cache,
        #[Autowire('%kernel.debug%')]
        private bool $isDebug, //in services.yaml we bind the 'kernel.debug' parameter value to it
        #[Autowire(service: 'twig.command.debug')]
        private DebugCommand $twigDebugCommand
    ){

    }
    public function findAll(): array{

        /*
         *
         * $output = new BufferedOutput();
         * $this->twigDebugCommand->tun(new ArrayInput([]), $output);
         * dd($output);
         * */


        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter(5);
            $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });
 }
}