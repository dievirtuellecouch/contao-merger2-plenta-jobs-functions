<?php

declare(strict_types=1);

namespace DVC\MergerJobsFunctions\Functions;

use ContaoCommunityAlliance\Merger2\Functions\AbstractPageFunction;
use ContaoCommunityAlliance\Merger2\Functions\Description\Argument;
use ContaoCommunityAlliance\Merger2\Functions\Description\Description;
use ContaoCommunityAlliance\Merger2\PageProvider;
use DVC\MergerJobsFunctions\JobOffer\CurrentJobOfferResolver;
use DVC\MergerJobsFunctions\JobOffer\EmploymentTypesParser;
use Plenta\ContaoJobsBasic\Contao\Model\PlentaJobsBasicOfferModel;
use Plenta\ContaoJobsBasic\Helper\EmploymentType;

final class JobEmploymentTypeFunction extends AbstractPageFunction
{
    public function __construct(
        private readonly EmploymentType $employmentTypeHelper,
        private readonly CurrentJobOfferResolver $currentJobOfferResolver,
        private readonly EmploymentTypesParser $employmentTypesParser,
        PageProvider $pageProvider,
    ) {
        parent::__construct($pageProvider);
    }

    public function __invoke(?string $expectedType = null): bool
    {
        if (null === $expectedType || '' === trim($expectedType)) {
            return false;
        }

        $availableTypes = $this->employmentTypeHelper->getEmploymentTypes();

        if ([] === $availableTypes) {
            return false;
        }

        if (!\in_array($expectedType, $availableTypes, true)) {
            return false;
        }

        if (null === $this->pageProvider->getPage()) {
            return false;
        }

        $jobOffer = $this->getCurrentJobOffer();

        if (null === $jobOffer) {
            return false;
        }

        $typesOfCurrentJob = $this->getEmploymentTypesOfJob($jobOffer);

        if ([] === $typesOfCurrentJob) {
            return false;
        }

        return \in_array($expectedType, $typesOfCurrentJob, true);
    }

    private function getCurrentJobOffer(): ?PlentaJobsBasicOfferModel
    {
        return $this->currentJobOfferResolver->resolve();
    }

    /**
     * @return list<string>
     */
    private function getEmploymentTypesOfJob(PlentaJobsBasicOfferModel $jobOffer): array
    {
        return $this->employmentTypesParser->parse($jobOffer->employmentType);
    }

    public function describe(): Description
    {
        return Description::create(static::getName())
            ->setDescription('Test if the Plenta Job item has given employment type.')
            ->addArgument('type')
                ->setDescription('The id of the employment type as used internally by Plenta Jobs. E. g. use FULL_TIME for full-time job or CUSTOM_1 for custom types where the appended digit is the id of the custom type.')
                ->setType(Argument::TYPE_STRING)
            ->end()
        ;
    }
}
