<?php

namespace App\Support;

use App\Models\Cards;
use Illuminate\Support\Str;

class CardIdGenerator
{
    /**
     * Generate a unique card ID.
     */
    public static function generate(): string
    {
        do {
            $cardId = 'CARD-' . strtoupper(Str::random(12));
        } while (Cards::where('card_number', $cardId)->exists());

        return $cardId;
    }

    /**
     * Generate a unique company-based card ID using secure alphanumeric content.
     */
    public static function generateForCompany(string $companyName): string
    {
        $companyPrefix = self::companyPrefix($companyName);

        do {
            $suffix = strtoupper(Str::random(10));
            $cardId = $companyPrefix.'-'.$suffix;
        } while (Cards::where('card_number', $cardId)->exists() || \App\Models\User::where('card_id', $cardId)->exists());

        return $cardId;
    }

    /**
     * Generate multiple company-based unique card IDs.
     *
     * @param int $count
     * @return array<string>
     */
    public static function generateMultipleForCompany(string $companyName, int $count): array
    {
        $cardIds = [];

        for ($i = 0; $i < $count; $i++) {
            $cardIds[] = self::generateForCompany($companyName);
        }

        return $cardIds;
    }

    /**
     * Generate multiple unique card IDs
     *
     * @param int $count
     * @return array<string>
     */
    public static function generateMultiple(int $count): array
    {
        $cardIds = [];

        for ($i = 0; $i < $count; $i++) {
            $cardIds[] = self::generate();
        }

        return $cardIds;
    }

    private static function companyPrefix(string $companyName): string
    {
        $slug = Str::of($companyName)
            ->trim()
            ->replaceMatches('/[^A-Za-z0-9]+/', '-')
            ->replaceMatches('/-+/', '-')
            ->trim('-')
            ->upper();

        $prefix = (string) $slug;

        if ($prefix === '') {
            $prefix = 'CORPORATE';
        }

        return substr($prefix, 0, 18);
    }
}
