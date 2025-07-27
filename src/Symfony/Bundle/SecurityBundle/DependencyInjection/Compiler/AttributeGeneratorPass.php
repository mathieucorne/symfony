<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\DependencyInjection\Compiler;

use Symfony\Bundle\SecurityBundle\Attribute\Generator\AttributeClassGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass that generates attribute classes from role hierarchy.
 *
 * Scans the configured roles in the security.role_hierarchy.roles parameter,
 * then uses AttributeClassGenerator to generate PHP attribute classes.
 *
 * @author Your Name <your.email@example.com>
 */
class AttributeGeneratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('security.role_hierarchy.roles')) {
            return;
        }

        $rolesHierarchy = $container->getParameter('security.role_hierarchy.roles');
        $dir = __DIR__ . '/../../GeneratedAttribute';

        // Extraire toutes les clés (rôles supérieurs)
        $rolesKeys = array_keys($rolesHierarchy);

        // Extraire toutes les valeurs (rôles hérités)
        $rolesValues = [];
        foreach ($rolesHierarchy as $children) {
            // Chaque enfant peut être une string ou un tableau
            if (is_array($children)) {
                $rolesValues = array_merge($rolesValues, $children);
            } elseif (is_string($children)) {
                $rolesValues[] = $children;
            }
        }

        // Fusionner et dédupliquer tous les rôles
        $allRoles = array_unique(array_merge($rolesKeys, $rolesValues));

        if (is_dir($dir)) {
            $files = glob($dir . '/*.php');

            $expectedClasses = array_map(
                fn(string $role) => AttributeClassGenerator::classNameFromRole($role) . '.php',
                $allRoles
            );

            foreach ($files as $file) {
                $fileName = basename($file);
                if (!in_array($fileName, $expectedClasses, true)) {
                    unlink($file);
                }
            }
        }

        $generator = new AttributeClassGenerator($dir);

        foreach ($allRoles as $role) {
            $generator->generateFromRole($role);
        }
    }
}