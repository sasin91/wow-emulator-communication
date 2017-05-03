<?php

namespace Sasin91\WoWEmulatorCommunication\Communication\Concerns;

use Illuminate\Support\Collection;

trait ParsesResponse
{
    /**
     * Parse the SOAP response.
     *
     * @param $response
     * @return Collection|string
     */
    protected function parseResponse($response)
    {
        if (is_string($response)) {
            return trim(str_replace(PHP_EOL, '', $response));
        }

        return Collection::make($response);
    }
}