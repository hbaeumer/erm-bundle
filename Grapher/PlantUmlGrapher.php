<?php declare(strict_types=1);

/**
 * MIT License
 *
 * Copyright (c)  Heiner Baeumer
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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
