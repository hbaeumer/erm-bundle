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


namespace Hbaeumer\ErmBundle\Parser;

class PlantUmlClassMarkup
{
    const VISIBILITY_PUBLIC = '+';
    const VISIBILITY_PROTECTED = '#';
    const VISIBILITY_PRIVATE = '-';
    const VISIBILITY_PACKAGE_PRIVATE = '-';

    /**
     * @var string
     */
    private $markup = 'set namespaceSeparator /' . PHP_EOL;

    public function addClass(string $fqcn): void
    {
        $this->addLine('class ' . $this->getClassName($fqcn));
    }

    private function addLine($string): void
    {
        $this->markup .= $string . PHP_EOL;
    }

    private function getClassName(string $fqcn): string
    {
        return str_replace('\\', '/', $fqcn);
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
    public function addAssociation(string $source, string $destination, ?string $sMultipicity = null, ?string $dMultipicity = null)
    {
        $pattern = '"%s" %s -- %s "%s" : >';
        $string = vsprintf(
            $pattern,
            [
                $this->getClassName($source),
                ($sMultipicity) ? '"' . $sMultipicity . '"' : '',
                $this->getClassName($destination),
                ($dMultipicity) ? '"' . $dMultipicity . '"' : '',
            ]
        );
        $this->addLine($string);

    }

    public function __toString()
    {
        return $this->getMarkup();
    }

    public function getMarkup(): string
    {
        return $this->markup;
    }
}
