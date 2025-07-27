<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AttributeUsageTest extends AbstractWebTestCase
{
    public function testAdminAccessWithCorrectRole(): void
    {
        $client = $this->createClient(['test_case' => 'AttributeUsage', 'root_config' => 'config.yml']);

        $client->request('GET', '/admin', [], [], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSame('OK', $client->getResponse()->getContent());
    }
}