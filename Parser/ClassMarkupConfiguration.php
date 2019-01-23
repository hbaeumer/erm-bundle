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


namespace Hbaeumer\ErmBundle\Parser;

/**
 * @internal
 */
class ClassMarkupConfiguration
{

    /**
     * @var string
     */
    private $config = '';

    public function setNamespaceSeparator(?string $separator = null): void
    {
        if ($separator) {
            $this->config .= 'set namespaceSeparator ' . $separator . PHP_EOL;
        }
    }

    public function __toString()
    {
        return $this->config;
    }
}
