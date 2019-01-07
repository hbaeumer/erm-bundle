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


namespace Hbaeumer\ErmBundle\Tests;

use Hbaeumer\ErmBundle\Parser\PlantUmlClassMarkup;
use PHPUnit\Framework\TestCase;

class PlantUmlClassMarkupTest extends TestCase
{

    /**
     * @var PlantUmlClassMarkup
     */
    private $markup;

    public function testAddClass(): void
    {
        $this->markup->addClass('Entity\\Foo\\Bar');

        $this->assertEquals('class Entity/Foo/Bar' . PHP_EOL, $this->markup->__toString());
    }

    /**
     * @param string $fqcn
     * @param string $property
     * @dataProvider getAddPropertyExamples
     */
    public function testAddProperty(string $excpected, string $fqcn, string $property, ?string $type = null, ?string $visibility = null, ?string $defaultValue = null, ?string $multiplicity = null): void
    {
        $this->markup->addAttribute($fqcn, $property, $type, $visibility, $defaultValue, $multiplicity);
        $this->assertEquals($excpected, $this->markup->__toString());
    }

    public function getAddPropertyExamples()
    {
        $fqcn = 'Entity\\Foo\\Bar';
        return [
            ['"Entity/Foo/Bar" : foo' . PHP_EOL, $fqcn, 'foo'],
            ['"Entity/Foo/Bar" : foo: string' . PHP_EOL, $fqcn, 'foo', 'string'],
            ['"Entity/Foo/Bar" : +foo: string' . PHP_EOL, $fqcn, 'foo', 'string', '+'],
            ['"Entity/Foo/Bar" : -foo' . PHP_EOL, $fqcn, 'foo', null, '-'],
            ['"Entity/Foo/Bar" : -foo: string ="Hugo"' . PHP_EOL, $fqcn, 'foo', 'string', '-', '"Hugo"'],
            ['"Entity/Foo/Bar" : -foo: string[1..2]' . PHP_EOL, $fqcn, 'foo', 'string', '-', null, '1..2'],
        ];
    }

    protected function setUp(): void
    {
        $this->markup = new PlantUmlClassMarkup();
    }
}
