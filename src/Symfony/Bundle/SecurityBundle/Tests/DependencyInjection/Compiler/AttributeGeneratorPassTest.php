<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\Tests\DependencyInjection\Compiler;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Compiler\AttributeGeneratorPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AttributeGeneratorPassTest extends TestCase
{
    public function testItGeneratesAndCleansClassesFromRoleHierarchy(): void
    {
        $dir = __DIR__ . '/../../../GeneratedAttribute';

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents($dir . '/ForObsolete.php', '<?php // obsolete class');

        $container = new ContainerBuilder();
        $container->setParameter('security.role_hierarchy.roles', [
            'ROLE_EDITOR' => ['ROLE_USER'],
            'ROLE_ADMIN' => ['ROLE_EDITOR'],
            'ROLE_SUPER_ADMIN' => ['ROLE_ADMIN'],
        ]);

        $pass = new AttributeGeneratorPass();
        $pass->process($container);

        $expected = [
            'ForEditor.php' => 'ROLE_EDITOR',
            'ForAdmin.php' => 'ROLE_ADMIN',
            'ForSuperAdmin.php' => 'ROLE_SUPER_ADMIN',
            'ForUser.php' => 'ROLE_USER',
        ];

        foreach ($expected as $file => $role) {
            $path = $dir . '/' . $file;
            $this->assertFileExists($path);
            $this->assertStringContainsString($role, file_get_contents($path));
        }

        $this->assertFileDoesNotExist($dir . '/ForObsolete.php');
    }
}