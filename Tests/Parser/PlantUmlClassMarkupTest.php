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


namespace Hbaeumer\ErmBundle\Tests\Parser;

use Hbaeumer\ErmBundle\Parser\ClassMarkupConfiguration;
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
        $this->markup->addClass('Entity\\Foo\\Bar', 'class', 'foo');

        $this->assertEquals('class Entity/Foo/Bar <foo> <<Entity>>' . PHP_EOL, $this->markup->__toString());
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

    public function testAddPropertyWithWrongVisibility(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->markup->addAttribute('Entity\\Foo\\Bar', 'hugo', 'string', 'z');
    }

    public function testAddAssociation(): void
    {
        $this->markup->addAssociation('Foo', 'Bar');
        $this->assertEquals('"Foo"  -->  "Bar"' . PHP_EOL, $this->markup->__toString());
    }

    public function testGetConfig(): void
    {
        $this->assertInstanceOf(ClassMarkupConfiguration::class, $this->markup->getConfig());
    }

    public function testAddParent(): void
    {
        $this->markup->addParent('Entity\\Foo\\Bar', 'Entity\\Foo\\Hugo');
        $this->assertEquals('"Entity/Foo/Bar" --|> "Entity/Foo/Hugo"' . PHP_EOL, $this->markup->__toString());
    }

    protected function setUp(): void
    {
        $this->markup = new PlantUmlClassMarkup();
    }
}
