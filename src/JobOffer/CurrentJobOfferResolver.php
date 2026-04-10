<?php

declare(strict_types=1);

namespace DVC\MergerJobsFunctions\JobOffer;

use Plenta\ContaoJobsBasic\Contao\Model\PlentaJobsBasicOfferModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CurrentJobOfferResolver
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function resolve(): ?PlentaJobsBasicOfferModel
    {
        $request = $this->requestStack->getCurrentRequest() ?? $this->requestStack->getMainRequest();

        if (!$request instanceof Request) {
            return null;
        }

        $jobAlias = $request->get('auto_item');

        if (!\is_scalar($jobAlias)) {
            return null;
        }

        $jobAlias = trim((string) $jobAlias);

        if ('' === $jobAlias) {
            return null;
        }

        return PlentaJobsBasicOfferModel::findPublishedByIdOrAlias($jobAlias);
    }
}
