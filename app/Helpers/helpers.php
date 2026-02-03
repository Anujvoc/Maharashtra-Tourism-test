<?php
use Illuminate\Support\Facades\DB;
use App\Models\Admin\GeneralSetting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// if (!function_exists('generateBadgeColor')) {

//     function generateBadgeColor(?string $text): string
//     {
//         if (empty($text)) {
//             return '#607d8b';
//         }

//         $hash = crc32(Str::lower($text));
//         $hue = $hash % 360;

//         return "hsl({$hue}, 70%, 50%)";
//     }
// }
if (!function_exists('cleanHtml')) {
 function cleanHtml($html)
{
    if (empty($html)) {
        return '';
    }

    // 1) Remove <script> and <style> blocks
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);

    // 2) Remove on* attributes (onclick, onerror, etc.)
    $html = preg_replace('/(<[^>]+)\s+on\w+\s*=\s*(["\']).*?\2/iu', '$1', $html);

    // 3) Neutralize javascript: in href/src
    $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/iu', '$1=$2#$2', $html);

    // 4) Allow only these tags (adjust as needed)
    $allowed = '<p><a><br><strong><b><em><i><ul><ol><li><img><h1><h2><h3><h4><h5><small><span>';
    $html = strip_tags($html, $allowed);

    // 5) Remove dangerous attributes from remaining tags (like style, data-*, js events left)
    // Keep basic href/src and alt, title, width, height — remove `style` attributes for extra safety
    $html = preg_replace('/(<[a-z][^>]*?)\sstyle=("[^"]*"|\'[^\']*\')/iu', '$1', $html);
    $html = preg_replace('/(<[a-z][^>]*?)\s(on\w+|data-[\w-]+)=("[^"]*"|\'[^\']*\')/iu', '$1', $html);

    return $html;
}
}

if (!function_exists('truncateWords')) {
    function truncateWords($text, $limit = 10, $end = '...')
    {
        return Str::words(strip_tags($text), $limit, $end);
    }
}
if (!function_exists('generateBadgeColor')) {


    function generateBadgeColor(?string $text): string
    {
        if (empty($text)) {
            return '#343a40';
        }
        $hash = crc32(Str::lower($text));
        $hue = $hash % 360;
        $saturation = 80;
        $lightness  = 35;

        return "hsl({$hue}, {$saturation}%, {$lightness}%)";
    }
}
if (!function_exists('short_number')) {

    function short_number($number, $precision = 1)
    {
        if ($number < 900) {
            // 0 - 900
            $n_format = number_format($number, 0);
            $suffix = '';
        } elseif ($number < 900000) {
            // 0.9k - 850k
            $n_format = number_format($number / 1000, $precision);
            $suffix = 'K';
        } elseif ($number < 900000000) {
            // 0.9m - 850m
            $n_format = number_format($number / 1000000, $precision);
            $suffix = 'M';
        } elseif ($number < 900000000000) {
            // 0.9b - 850b
            $n_format = number_format($number / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($number / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unnecessary .0 decimals
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return $n_format . $suffix;
    }
}

if (!function_exists('format_number')) {
    function format_number($number, $decimal_places = 0) {
        return number_format((float) $number, $decimal_places, '.', ',');
    }
}


if (!function_exists('check_visibility')) {
    function check_visibility($val)
    {
        if($val==1){
            $str='<span class="btn btn-success btn-sm"><i class="fas fa-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="Visible"></i></span>';
        }else{
            $str='<span class="btn btn-danger btn-sm"><i class="fas fa-eye-slash" data-bs-toggle="tooltip" data-bs-placement="top" title="Not Visible"></i></span>';
        }

        return $str;
    }

    if (!function_exists('site_logo')) {
        function site_logo()
        {
            $setting = GeneralSetting::first();
            if ($setting && $setting->logo && File::exists(public_path("site_data_images/{$setting->logo}"))) {
                return asset("site_data_images/{$setting->logo}");
            }

            // fallback logo
            return asset('dashboard_assets/logo-dark.png');
        }
    }

    if (!function_exists('site_favicon')) {
        function site_favicon()
        {
            $setting = GeneralSetting::first();
            if ($setting && $setting->favicon && File::exists(public_path("site_data_images/{$setting->favicon}"))) {
                return asset("site_data_images/{$setting->favicon}");
            }

            // fallback favicon
            return asset('dashboard_assets/favicon.ico');
        }
    }

    if (!function_exists('site_setting')) {
        function site_setting($key)
        {
            $setting = GeneralSetting::first();
            return $setting->$key ?? null;
        }
}
}
