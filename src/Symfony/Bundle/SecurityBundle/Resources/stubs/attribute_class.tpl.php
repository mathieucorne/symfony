<?php echo "<?php\n"; ?>

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace <?= $namespace ?>;

use Symfony\Component\Security\Http\Attribute\IsGranted;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class <?= $className ?> extends IsGranted
{
    /**
     * Constructor.
     *
     * @param string|null $message Optional access denied message
     */
    public function __construct(string $message = null)
    {
        parent::__construct('<?= $role ?>', message: $message);
    }
}