<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\SecurityBundle\Attribute\Generator;

/**
 * Generates PHP attribute classes based on security roles.
 *
 * This generator creates attribute classes named `For[Role]` that extends IsGranted
 * from role strings, e.g. ROLE_ADMIN => ForAdmin.
 * These attributes can then be used for access control in controllers.
 *
 * @author Mathieu Corne <math.corne@gmail.com>
 */
class AttributeClassGenerator
{
    private string $targetDir;
    private string $templatePath;
    private string $namespace;

    /**
     * Constructor.
     *
     * @param string      $targetDir    Directory where attribute classes are generated
     * @param string      $namespace    Namespace for the generated attribute classes
     * @param string|null $templatePath Optional path to the attribute stub template
     */
    public function __construct(
        string $targetDir,
        string $namespace = 'Symfony\\Bundle\\SecurityBundle\\GeneratedAttribute',
        string $templatePath = null
    ) {
        $this->targetDir = rtrim($targetDir, '/');
        $this->namespace = $namespace;
        $this->templatePath = $templatePath ?? __DIR__ . '/../../Resources/stubs/attribute_class.tpl.php';

        if (!is_dir($this->targetDir)) {
            mkdir($this->targetDir, 0775, true);
        }
    }

    /**
     * Generates an attribute class PHP file from the given security role.
     *
     * Does nothing if the file already exists.
     *
     * @param string $role Security role (e.g. "ROLE_ADMIN")
     */
    public function generateFromRole(string $role): void
    {
        $className = self::classNameFromRole($role);
        $filePath = "{$this->targetDir}/{$className}.php";

        if (file_exists($filePath)) {
            return;
        }

        $code = $this->renderStub($className, $role);
        file_put_contents($filePath, $code);
    }

    /**
     * Converts a role string into a PHP class name.
     *
     * Example: "ROLE_ADMIN" => "ForAdmin"
     *
     * @param string $role Security role string
     *
     * @return string Corresponding attribute class name
     */
    public static function classNameFromRole(string $role): string
    {
        return 'For' . str_replace(' ', '', ucwords(strtolower(str_replace(['ROLE_', '_'], ['', ' '], $role))));
    }

    /**
     * Renders the PHP attribute class code by including the stub template.
     *
     * @param string $className Attribute class name (e.g. "ForAdmin")
     * @param string $role      Security role (e.g. "ROLE_ADMIN")
     *
     * @return string PHP code of the generated class
     */
    private function renderStub(string $className, string $role): string
    {
        ob_start();
        extract([
            'namespace' => $this->namespace,
            'className' => $className,
            'role' => $role,
        ]);
        include $this->templatePath;
        return ob_get_clean();
    }
}