<?php

namespace Nasa;

use GuzzleHttp\Client;

class NasaAPI
{
    public function __construct(
        public string $apiKey,
        public string $apiEndpoint,
    ) {
    }

    protected function request(string $route, string $method, ?FilterCriteria $queryFilter = null)
    {
        $client = new Client([
            'base_uri' => $this->apiEndpoint,
            'timeout'  => 15,
        ]);

        $route = '/' . $route . '?api_key=' . $this->apiKey;
        if ($queryFilter != null) {
            $queryFilter = $queryFilter->getQueryParameters();
            $route .= '&' . $queryFilter;
        }

        $response = $client->request($method, $route);

        $body = $response->getBody();

        return json_decode($body, true);
    }

    public function planetary(?FilterCriteria $filterCriteria = null): array
    {
        return $this->request('planetary/apod', 'GET', $filterCriteria);
    }

    public function asteroid(?FilterCriteria $filterCriteria = null): array
    {
        return $this->request('neo/rest/v1/neo/browse', 'GET', $filterCriteria);
    }

    public function asteroidByID(string $id): array
    {
        return $this->request('neo/rest/v1/neo/' . $id, 'GET');
    }

    public function earth(?FilterCriteria $filterCriteria = null): array
    {
        return $this->request('planetary/earth/imagery', 'GET', $filterCriteria);
    }

    public function mars(?FilterCriteria $filterCriteria = null): array
    {
        return $this->request('mars-photos/api/v1/rovers/curiosity/photos', 'GET', $filterCriteria);
    }
}
