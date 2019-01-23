<?php declare(strict_types=1);

/**
 * This file is part of hbaeumer/erm-bundle
 *
 * Copyright (c) Heiner Baeumer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Hbaeumer\ErmBundle\Grapher;

use GuzzleHttp\Client;

class PlantUmlGrapher
{

    /**
     * @var string
     */
    private $basePath = "http://www.plantuml.com/plantuml/";

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Encoder
     */
    private $encoder;

    public function __construct(Client $client, Encoder $encoder)
    {
        $this->client = $client;
        $this->encoder = $encoder;
    }

    public function getSVG(string $markup): string
    {
        $encoded = $this->encoder->encode($markup);
        $response = $this->client->get($this->basePath . "svg/" . $encoded);
        return $response->getBody()->getContents();
    }

    public function getTXT(string $markup): string
    {
        $encoded = $this->encoder->encode($markup);
        $response = $this->client->get($this->basePath . "txt/" . $encoded);
        return $response->getBody()->getContents();
    }
}
