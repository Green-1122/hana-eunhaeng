<?php
/**
 * Format Helper - Format currency, dates, numbers
 */

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format currency
     */
    public static function currency(float $amount, string $currency = 'USD'): string
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }

    /**
     * Format number
     */
    public static function number(float $number, int $decimals = 2): string
    {
        return number_format($number, $decimals, '.', ',');
    }

    /**
     * Format percentage
     */
    public static function percentage(float $number, int $decimals = 2): string
    {
        return number_format($number, $decimals) . '%';
    }

    /**
     * Format date
     */
    public static function date(string $date, string $format = 'M d, Y'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Format time
     */
    public static function time(string $time, string $format = 'h:i A'): string
    {
        return date($format, strtotime($time));
    }

    /**
     * Format datetime
     */
    public static function datetime(string $datetime, string $format = 'M d, Y h:i A'): string
    {
        return date($format, strtotime($datetime));
    }

    /**
     * Time ago
     */
    public static function timeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) {
            return 'just now';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' minutes ago';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' hours ago';
        } elseif ($diff < 604800) {
            return floor($diff / 86400) . ' days ago';
        } else {
            return self::date($datetime);
        }
    }

    /**
     * File size
     */
    public static function fileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
