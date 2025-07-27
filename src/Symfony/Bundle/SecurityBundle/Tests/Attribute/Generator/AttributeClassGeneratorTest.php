<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\Tests\Attribute\Generator;

use Symfony\Bundle\SecurityBundle\Attribute\Generator\AttributeClassGenerator;
use PHPUnit\Framework\TestCase;

class AttributeClassGeneratorTest extends TestCase
{
    public function testItGeneratesAForAdminClassFromRole(): void
    {
        $tmp = sys_get_temp_dir() . '/generated_attributes_test';
        @mkdir($tmp);

        $generator = new AttributeClassGenerator(
            $tmp
        );

        $generator->generateFromRole('ROLE_ADMIN');

        $classFile = $tmp . '/ForAdmin.php';
        $this->assertFileExists($classFile);
        $content = file_get_contents($classFile);
        $this->assertStringContainsString('class ForAdmin', $content);
        $this->assertStringContainsString('ROLE_ADMIN', $content);
    }
}