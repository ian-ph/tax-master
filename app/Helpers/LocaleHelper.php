<?php

namespace App\Helpers;

use Propaganistas\LaravelIntl\Facades\Currency as Currency;
use Request;

/**
 * This helper focuses on localization of the common numeric values appear in the.
 * By simply changing the app_locale (or in future, per country localization is implemented), numeric value will be automatically formatted.
 */
class LocaleHelper
{
    public static function currency($value)
    {
        $currency = Request::session()->get('current.country.currency', 'USD');
        return Currency::format($value, $currency);
    }
}
