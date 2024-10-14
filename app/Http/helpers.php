<?php
/**
 * Created by Ahmed Zidan.
 * email: php.ahmedzidan@gmail.com
 * Project: Alrabeh LMS
 * Date: 3/7/19
 * Time: 4:47 PM
 */
/**
 * @param $string
 * @param string $separator
 * @return bool|false|mixed|string|string[]|null
 */
function make_slug($string, $separator = '-')
{
    $string = trim($string);
    $string = mb_strtolower($string, 'UTF-8');

// Make alphanumeric (removes all other characters)
// this makes the string safe especially when used as a part of a URL
// this keeps latin characters and Persian characters as well
    $string = preg_replace("/[^a-z0-9_\s-۰۱۲۳۴۵۶۷۸۹ءاآؤئبپتثجچحخدذرزژسشصضطظعغفقکكگگلمنهويةىأ]/u", '', $string);

// Remove multiple dashes or whitespaces or underscores
    $string = preg_replace("/[\s-_]+/", ' ', $string);

// Convert whitespaces and underscore to the given separator
    $string = preg_replace("/[\s_]/", $separator, $string);

    return $string;
}

function youtubeUrlToEmbed($url) {
    preg_match('%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))([\w-]{11})(?:.+)?$%x', $url, $match);
    $videoId =  isset($match[1]) ? $match[1] : null;
    if($videoId){
        $url ="https://www.youtube.com/embed/{$videoId}";
    }
    return $url;
}

// app/helpers.php

if (!function_exists('convertToArabicNumbers')) {
    function convertToArabicNumbers($html) {
        $englishToArabic = [
            '0' => '٠',
            '1' => '١',
            '2' => '٢',
            '3' => '٣',
            '4' => '٤',
            '5' => '٥',
            '6' => '٦',
            '7' => '٧',
            '8' => '٨',
            '9' => '٩'
        ];
        
        // Use a regex to replace numbers while preserving HTML
        return preg_replace_callback('/>([^<]+)</', function ($matches) use ($englishToArabic) {
            // Only replace numbers in the text
            return '>' . str_replace(array_keys($englishToArabic), array_values($englishToArabic), $matches[1]) . '<';
        }, $html);
    }
}


