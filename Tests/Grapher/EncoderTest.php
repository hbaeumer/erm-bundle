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


namespace Hbaeumer\ErmBundle\Tests\Grapher;

use Hbaeumer\ErmBundle\Grapher\Encoder;
use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{

    /**
     * @var Encoder
     */
    private $encoder;

    /**
     * @param string $given
     * @param string $excpected
     * @dataProvider getEndocerExamples
     */
    public function testEncode(string $given, string $excpected): void
    {
        $this->assertEquals($excpected, $this->encoder->encode($given));
    }

    public function getEndocerExamples()
    {
        return [
            ['foo', 'IylF1m00'],
            ['Ä€1231!', 'Ezpog6cDeP6neI80'],
        ];
    }

    protected function setUp(): void
    {
        $this->encoder = new Encoder();
    }
}
