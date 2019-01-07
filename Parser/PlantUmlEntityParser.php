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

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

class PlantUmlEntityParser
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PlantUmlClassMarkup
     */
    private $markup;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMarkup(): string
    {
        $this->markup = new PlantUmlClassMarkup();
        $this->getFromMetaDataFactory($this->entityManager->getMetadataFactory());
        return $this->markup->getMarkup();
    }

    private function getFromMetaDataFactory(ClassMetadataFactory $metadataFactory): void
    {
        $metadatas = $metadataFactory->getAllMetadata();
        foreach ($metadatas as $metadata) {
            $this->createMarkupFromMetaData($metadata);
        }
    }

    public function createMarkupFromMetaData(ClassMetadata $classMetadata): void
    {
        $this->markup->addClass($classMetadata->getName());
        $multimap = [
            1 => ['1', '1'], // oneToOne
            2 => ['*', '1'],  // ManyToOne
            4 => ['1', '*'], // OneToMany
            8 => ['*', '*'], // ManyToMany
        ];


        foreach ($classMetadata->associationMappings as $associationMapping) {
            $multiplicity = $multimap[$associationMapping['type']];
            $this->markup->addAssociation(
                $classMetadata->getName(),
                $associationMapping['targetEntity'],
                $multiplicity[0],
                $multiplicity[1],
            );
        }
    }
}
