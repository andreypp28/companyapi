<?php

namespace WA\Helpers;

use DB;

/**
 * Class Helper.
 */
class Helper
{
    /**
     * @param $string
     *
     * @return mixed
     */
    public static function stripNonNumeric($string)
    {
        return preg_replace('~[a-zA-Z./-]+~', '', $string);
    }

    /**
     * Create a random hash.
     *
     * @param int $length
     *
     * @return string
     */
    public static function generateSalt($length = 16)
    {
        return substr(md5(microtime()), rand(0, 26), $length);
    }

    /**
     * Using the Kint package, dump and die in a nice way.
     *
     * @param null $data
     */
    public static function dd($data = null)
    {
        if (\Kint::enabled()) {
            \Kint::dump($data);
            die;
        } else {
            var_dump($data);
            die;
        }
    }

    /**
     * Extract values from an array.
     *
     * @param       $array
     * @param array $keys
     * @param null  $default
     *
     * @return array
     */
    public static function arrayExtract($array, array $keys, $default = null)
    {
        $found = [];
        foreach ($keys as $key) {
            $found[$key] = isset($array[$key]) ? $array[$key] : $default;
        }

        return $found;
    }

    /**
     * Format number to locale.
     *
     * @param      $number
     * @param      $places
     * @param bool $monetary
     * @param bool $currency
     *
     * @return string
     */
    public static function formatNumber($number, $places, $monetary = true, $currency = true)
    {
        $info = localeconv();

        if (!$monetary) {
            $decimal = $info['mon_decimal_point'];
            $thousands = $info['mon_thousands_sep'];
        } else {
            $decimal = $info['decimal_point'];
            $thousands = $info['thousands_sep'];
        }

        if (!$currency) {
            return number_format($number, $places, $decimal, $thousands);
        }

        return $info['currency_symbol'].' '.number_format($number, $places, $decimal, $thousands);
    }

    /**
     * Format most phone numbers
     * (copying ATT formatting).
     *
     * @param $phoneNumber
     *
     * @return mixed
     */
    public static function formatPhoneNumber($phoneNumber)
    {
        $numbers_only = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strlen($numbers_only) > 10) {
            $countryCode = substr($numbers_only, 0, strlen($numbers_only) - 10);
            $areaCode = substr($numbers_only, -10, 3);
            $nextThree = substr($numbers_only, -7, 3);
            $lastFour = substr($numbers_only, -4, 4);

            $numbers_only = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
        } elseif (strlen($numbers_only) == 10) {
            $areaCode = substr($numbers_only, 0, 3);
            $nextThree = substr($numbers_only, 3, 3);
            $lastFour = substr($numbers_only, 6, 4);

            $numbers_only = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
        } elseif (strlen($numbers_only) == 7) {
            $nextThree = substr($numbers_only, 0, 3);
            $lastFour = substr($numbers_only, 3, 4);

            $numbers_only = $nextThree.'-'.$lastFour;
        }

        return $numbers_only;
    }

    /**
     * Take a number in a variety of formats and return a proper E164 number.
     *
     * @param $dialedNumber
     *
     * @return bool|string
     */
    public static function normalizeNumber($dialedNumber)
    {
        $NumberStack['number'] = $dialedNumber;
        if (preg_match('/^1([1-9][0-9]{8,})$/', $NumberStack['number'], $m)) {
            $NumberStack['E164'] = $m[1];
            $NumberStack['E164'] = '1'.$NumberStack['E164'];
            $NumberStack['numberCorrected'] = $NumberStack['E164'];
        } elseif (preg_match("/^\*1([1-9][0-9]{8,})$/", $NumberStack['number'], $m)) {
            $NumberStack['E164'] = $m[1];
            $NumberStack['E164'] = '1'.$NumberStack['E164'];
            $NumberStack['numberCorrected'] = $NumberStack['E164'];
        } elseif (preg_match("/^\+([1-9][0-9]{8,})$/", $NumberStack['number'], $m)) {
            $NumberStack['E164'] = $m[1];
            $NumberStack['numberCorrected'] = $NumberStack['E164'];
        } elseif (preg_match('/^([1-9][0-9]{8,})$/', $NumberStack['number'], $m)) {
            $NumberStack['E164'] = $m[1];
            $NumberStack['E164'] = '1'.$NumberStack['E164'];
            $NumberStack['numberCorrected'] = $NumberStack['E164'];
        } elseif (preg_match('/^011([1-9][0-9]{8,})$/', $NumberStack['number'], $m)) {
            $NumberStack['E164'] = $m[1];
            $NumberStack['numberCorrected'] = $NumberStack['E164'];
        } elseif (preg_match('/^0([1-9][0-9][0-9]{2,15})$/', $NumberStack['number'], $m)) {
            $NumberStack['E164'] = '44'.$m[1];
            $NumberStack['numberCorrected'] = $NumberStack['E164'];
        } elseif (preg_match('/^00([1-9][0-9]{8,})$/', $NumberStack['number'], $m)) {
            $NumberStack['E164'] = $m[1];
            $NumberStack['numberCorrected'] = $NumberStack['E164'];
        } else {
            $NumberStack['numberCorrected'] = false;
        }

        return $NumberStack['numberCorrected'];
    }

    /**
     * Look for the first string position in an array of strings.
     *
     * Mimics strpos, but operates on an array
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool|int
     */
    public static function strPosArray($haystack, $needle)
    {
        if (!is_array($needle)) {
            $needle = [$needle];
        }
        foreach ($needle as $what) {
            if (($pos = stripos($haystack, $what)) !== false) {
                return $pos;
            }
        }

        return false;
    }

    /**
     * @param bool $all
     */
    public static function ddq($all = true)
    {
        dd(self::q($all));
    }

    /**
     * Grab the query log and return either ALL queries or just the last executed query.
     *
     * @param bool $all
     *
     * @return array|mixed
     */
    public static function q($all = true)
    {
        $queries = DB::getQueryLog();
        if ($all == false) {
            $last_query = end($queries);

            return $last_query;
        }

        return $queries;
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return substr($haystack, 0, $length) === $needle;
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }

    /**
     * @param     $haystack
     * @param     $needle
     * @param int $offset
     *
     * @return bool
     */
    public static function strPosA($haystack, $needle, $offset = 0)
    {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        foreach ($needle as $query) {
            if (strpos($haystack, $query, $offset) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param     $haystack
     * @param     $needle
     * @param int $offset
     *
     * @return bool
     */
    public static function striPosA($haystack, $needle, $offset = 0)
    {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        foreach ($needle as $query) {
            if (strpos($haystack, $query, $offset) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function getBranch()
    {
        $version_prefix = 'v';

        if (!file_exists(app_path().'/../.version')) {
            return;
        }

        $version = file_get_contents(app_path().'/../.version');

        return $version_prefix.trim($version);
    }

    public static function getVersion()
    {
        $version_prefix = 'v';

        if (!file_exists(app_path().'/../.version')) {
            return;
        }

        $version = file_get_contents(app_path().'/../.version');
        $v = $version_prefix.trim($version);

        return str_replace('v4.0.0-', '', $v);
    }
}
