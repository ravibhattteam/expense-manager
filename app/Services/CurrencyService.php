<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{
    /**
     * Convert $amount from $fromCurrency to $toCurrency using exchangerate-api.
     * Returns rounded value or null on failure.
     */
    public function convertToCompanyCurrency($amount, $fromCurrency, $toCurrency)
    {
        if (!$fromCurrency || !$toCurrency) return null;
        if (strtoupper($fromCurrency) === strtoupper($toCurrency)) return $amount;

        try {
            $res = Http::get("https://api.exchangerate-api.com/v4/latest/" . strtoupper($fromCurrency));
            $json = $res->json();
            $rates = $json['rates'] ?? null;
            if (!$rates || !isset($rates[strtoupper($toCurrency)])) return null;
            return round($amount * $rates[strtoupper($toCurrency)], 2);
        } catch (\Exception $e) {
            return null;
        }
    }
}
