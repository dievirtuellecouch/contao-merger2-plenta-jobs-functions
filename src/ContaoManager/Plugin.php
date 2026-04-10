<?php

declare(strict_types=1);

namespace DVC\MergerJobsFunctions\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use ContaoCommunityAlliance\Merger2\CcaMerger2Bundle;
use DVC\MergerJobsFunctions\MergerJobsFunctionsBundle;
use Plenta\ContaoJobsBasic\PlentaContaoJobsBasicBundle;

final class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(MergerJobsFunctionsBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    CcaMerger2Bundle::class,
                    PlentaContaoJobsBasicBundle::class,
                ]),
        ];
    }
}
