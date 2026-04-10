<?php

declare(strict_types=1);

namespace DVC\MergerJobsFunctions\JobOffer;

use Contao\StringUtil;

final class EmploymentTypesParser
{
    /**
     * Support both current JSON values and legacy serialized Contao arrays.
     *
     * @return list<string>
     */
    public function parse(mixed $value): array
    {
        if (!\is_string($value) || '' === trim($value)) {
            return [];
        }

        $employmentTypes = $this->decodeJson($value) ?? $this->decodeSerialized($value);

        if (!\is_array($employmentTypes)) {
            return [];
        }

        return array_values(
            array_filter(
                $employmentTypes,
                static fn (mixed $employmentType): bool => \is_string($employmentType) && '' !== $employmentType
            )
        );
    }

    /**
     * @return array<mixed>|null
     */
    private function decodeJson(string $value): ?array
    {
        try {
            $decoded = json_decode($value, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        return \is_array($decoded) ? $decoded : null;
    }

    /**
     * @return array<mixed>|null
     */
    private function decodeSerialized(string $value): ?array
    {
        if (!$this->looksSerialized($value)) {
            return null;
        }

        $decoded = StringUtil::deserialize($value);

        return \is_array($decoded) ? $decoded : null;
    }

    private function looksSerialized(string $value): bool
    {
        return 1 === preg_match('/^(a|i|d|b|s|O|C):/', $value);
    }
}
