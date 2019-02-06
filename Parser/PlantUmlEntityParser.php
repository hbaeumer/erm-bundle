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

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @internal
 */
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
        $this->markup->getConfig()->setNamespaceSeparator('/');
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

    private function getType(ClassMetadata $classMetadata)
    {
        $classReflection = $classMetadata->getReflectionClass();
        if ($classReflection->isAbstract()) {
            return 'abstract class';
        }
        if ($classReflection->isInterface()) {
            return 'interface';
        }
        return 'class';
    }

    public function createMarkupFromMetaData(ClassMetadata $classMetadata): void
    {
        $this->markup->addClass($classMetadata->getName(), $this->getType($classMetadata), $classMetadata->getTableName());
        $multimap = [
            1 => ['1', '1'], // oneToOne
            2 => ['*', '1'],  // ManyToOne
            4 => ['1', '*'], // OneToMany
            8 => ['*', '*'], // ManyToMany
        ];
        foreach ($classMetadata->associationMappings as $key => $associationMapping) {
            $multiplicity = $multimap[$associationMapping['type']];
            $this->markup->addAssociation(
                $classMetadata->getName(),
                $associationMapping['targetEntity'],
                $multiplicity[0],
                $multiplicity[1]
            );
            $fieldName = $associationMapping['fieldName'];
            $mv        = null;
            if ($multiplicity[1] !== '1') {
                $mv = $multiplicity[1];
            }

            $this->getField($classMetadata, $fieldName, $associationMapping['targetEntity'], $mv);
        }

        $this->getFields($classMetadata);
        $this->getParent($classMetadata);
    }

    private function getParent(ClassMetadata $classMetadata): void
    {
        $reflection = $classMetadata->getReflectionClass();
        $parent     = $reflection->getParentClass();
        if (!$parent) {
            return;
        }
        $this->markup->addParent($classMetadata->getName(), $parent->getName());
    }

    private function getFields(ClassMetadata $classMetadata): void
    {
        foreach ($classMetadata->fieldNames as $fieldName) {
            $type = $classMetadata->getFieldMapping($fieldName)['type'];
            $this->getField($classMetadata, $fieldName, $type);
        }
    }

    private function getField(ClassMetadata $classMetadata, string $fieldName, string $type, ?string $multiplicity = null): void
    {
        $classReflection = $classMetadata->getReflectionClass();
        $defaults        = $classReflection->getDefaultProperties();
        $default         = null;
        if (array_key_exists($fieldName, $defaults)) {
            $default = $defaults[$fieldName];
            if (is_bool($default)) {
                $default = ($default) ? 'true' : 'false';
            }

            if (is_array($default)) {
                $default = '[]';
            }
        }

        $reflection = $classMetadata->getReflectionProperty($fieldName);
        $visibility = $this->parseModifierProperty($reflection);

        $this->markup->addAttribute(
            $classMetadata->getName(),
            $fieldName,
            $type,
            $visibility,
            (string)$default,
            $multiplicity
        );
    }

    private function parseModifierProperty(\ReflectionProperty $property): string
    {
        $string = '';
        if ($property->isPublic()) {
            $string = PlantUmlClassMarkup::VISIBILITY_PUBLIC;
        }
        if ($property->isProtected()) {
            $string = PlantUmlClassMarkup::VISIBILITY_PROTECTED;
        }
        if ($property->isPrivate()) {
            $string = PlantUmlClassMarkup::VISIBILITY_PRIVATE;
        }

        return $string;
    }
}
