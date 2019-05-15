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
class PlantUmlClassMarkup
{
    const VISIBILITY_PUBLIC          = '+';
    const VISIBILITY_PROTECTED       = '#';
    const VISIBILITY_PRIVATE         = '-';
    const VISIBILITY_PACKAGE_PRIVATE = '-';

    /**
     * @var string
     */
    private $markup = '';

    /**
     * @var ClassMarkupConfiguration
     */
    private $config;

    /**
     *
     * @param ClassMarkupConfiguration $config
     */
    public function __construct()
    {
        $this->config = new ClassMarkupConfiguration();
    }

    public function addClass(string $fqcn, string $type, string $tableName): void
    {
        $this->addLine($type . ' ' . $this->getClassName($fqcn) . ' <' . $tableName . '> <<Entity>>');
    }

    private function addLine($string): void
    {
        $this->markup .= $string . PHP_EOL;
    }

    private function getClassName(string $fqcn): string
    {
        return str_replace('\\', '/', $fqcn);
    }

    public function addParent(string $source, string $destination): void
    {
        $pattern = '"%s" --|> "%s"';
        $string  = vsprintf(
            $pattern,
            [
             $this->getClassName($source),
             $this->getClassName($destination),

            ]
        );
        $this->addLine($string);
    }

    public function addAttribute(string $fqcn, string $attribute, ?string $type = null, ?string $visibility = null, ?string $defaultValue = null, ?string $multiplicity = null): void
    {
        $pattern = '"%s" : %s%s';
        if ($visibility) {
            $this->assertVisibility($visibility);
        }
        $string = vsprintf(
            $pattern,
            [
             $this->getClassName($fqcn),
             (string)$visibility,
             $attribute,
            ]
        );

        if ($type) {
            $string .= ': ' . $type;
        }

        if ($multiplicity) {
            $string .= '[' . $multiplicity . ']';
        }

        if ($defaultValue) {
            $string .= ' =' . $defaultValue;
        }

        $this->addLine($string);
    }

    private function assertVisibility(string $visibility): void
    {
        if (!in_array(
            $visibility,
            [
             self::VISIBILITY_PUBLIC,
             self::VISIBILITY_PROTECTED,
             self::VISIBILITY_PRIVATE,
             self::VISIBILITY_PACKAGE_PRIVATE,
            ],
            true
        )) {
            throw new \InvalidArgumentException(
                'Visibility must be one of PlantUmlClassMarkup::VISIBILITY_*'
            );
        }
    }

    /**
     * @param string $source FQCN
     * @param string $destination FQCN
     * @param string|null $sMultipicity
     * @param string|null $dMultipicity
     */
    public function addAssociation(string $source, string $destination, ?string $sMultipicity = null, ?string $dMultipicity = null): void
    {
        $pattern = '"%s" %s --> %s "%s"';
        $string  = vsprintf(
            $pattern,
            [
             $this->getClassName($source),
             ($sMultipicity) ? '"' . $sMultipicity . '"' : '',
             ($dMultipicity) ? '"' . $dMultipicity . '"' : '',
             $this->getClassName($destination),

            ]
        );
        $this->addLine($string);
    }

    public function __toString()
    {
        return $this->getMarkup();
    }

    public function getConfig(): ClassMarkupConfiguration
    {
        return $this->config;
    }

    public function getMarkup(): string
    {
        $string = (string)$this->config;
        $string .=$this->markup;
        return $string;
    }
}
