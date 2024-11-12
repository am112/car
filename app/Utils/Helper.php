<?php

namespace App\Utils;

use Carbon\Carbon;
use Exception;

class Helper
{
    /**
     * Summary of formatMoney
     */
    public static function formatMoney(int $money): string
    {
        return number_format($money / 100, 2, '.', ',');
    }

    /**
     * Summary of monthlyAniversaryDays
     */
    public static function monthlyAniversaryDays(Carbon $today): array
    {
        try {
            $days = [$today->day];
            if ($today->isEndOfMonth()) {
                if ($today->day === 28) {
                    $days = array_merge($days, [29, 30, 31]);
                }
                if ($today->day === 29) {
                    $days = array_merge($days, [30, 31]);
                }
                if ($today->day === 30) {
                    $days = array_merge($days, [31]);
                }
            }

            return $days;
        } catch (Exception $e) {
            return [$today->day];
        }
    }

    /**
     * Summary of referenceNoConvention
     */
    public static function referenceNoConvention(string $prefix, int $runningNo, Carbon $dateAt): string
    {
        return $prefix . '-' . $dateAt->format('ymd') . '-' . str_pad($runningNo, 4, '0', STR_PAD_LEFT);
    }
}
