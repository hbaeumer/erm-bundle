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
