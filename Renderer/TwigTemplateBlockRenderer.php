<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\UiBundle\Renderer;

use Sylius\Bundle\UiBundle\ContextProvider\ContextProviderInterface;
use Sylius\Bundle\UiBundle\Registry\TemplateBlock;
use Twig\Environment;

trigger_deprecation(
    'sylius/ui-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0',
    TwigTemplateBlockRenderer::class,
);
final class TwigTemplateBlockRenderer implements TemplateBlockRendererInterface
{
    public function __construct(private Environment $twig, private iterable $contextProviders)
    {
    }

    public function render(TemplateBlock $templateBlock, array $context = []): string
    {
        foreach ($this->contextProviders as $contextProvider) {
            if (!$contextProvider instanceof ContextProviderInterface || !$contextProvider->supports($templateBlock)) {
                continue;
            }

            $context = $contextProvider->provide($context, $templateBlock);
        }

        return $this->twig->render($templateBlock->getTemplate(), $context);
    }
}
