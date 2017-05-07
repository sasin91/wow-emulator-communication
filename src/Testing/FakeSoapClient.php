<?php

namespace Sasin91\WoWEmulatorCommunication\Testing;

class FakeSoapClient
{
    public function handle($command)
    {
        return (string)$command;
    }

    public function configure($client, array $options = [])
    {
        // do nothing
    }
}
