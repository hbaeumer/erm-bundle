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
use PHPUnit\Framework\TestCase;

class ClassMarkupConfigurationTest extends TestCase
{
    public function testSetNamespaceSeparator(): void
    {
        $config = new ClassMarkupConfiguration();
        $config->setNamespaceSeparator('.');
        $this->assertEquals('set namespaceSeparator .' . PHP_EOL, $config->__toString());
    }
}
