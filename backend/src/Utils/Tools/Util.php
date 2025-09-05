<?php

namespace App\Utils\Tools;

/**
 * util.php
 *
 * util.php is a library of helper functions for common tasks such as
 * formatting bytes as a string or displaying a date in terms of how long ago
 * it was in human readable terms (E.g. 4 minutes ago). The library is entirely
 * contained within a single file and hosts no dependencies. The library is
 * designed to avoid any possible conflicts.
 *
 * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
 * @link   http://github.com/brandonwamboldt/utilphp/ Official Documentation
 */
class Util
{

    /**
     * A constant representing the number of seconds in a minute, for
     * making code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_MINUTE = 60;

    /**
     * A constant representing the number of seconds in an hour, for making
     * code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_HOUR = 3600;
    const SECONDS_IN_AN_HOUR = 3600;

    /**
     * A constant representing the number of seconds in a day, for making
     * code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_DAY = 86400;

    /**
     * A constant representing the number of seconds in a week, for making
     * code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_WEEK = 604800;

    /**
     * A constant representing the number of seconds in a month (30 days),
     * for making code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_MONTH = 2592000;

    /**
     * A constant representing the number of seconds in a year (365 days),
     * for making code more verbose
     *
     * @var integer
     */
    const SECONDS_IN_A_YEAR = 31536000;

    /**
     * URL constants as defined in the PHP Manual under "Constants usable with
     * http_build_url()".
     *
     * @see http://us2.php.net/manual/en/http.constants.php#http.constants.url
     */
    const HTTP_URL_REPLACE = 1;
    const HTTP_URL_JOIN_PATH = 2;
    const HTTP_URL_JOIN_QUERY = 4;
    const HTTP_URL_STRIP_USER = 8;
    const HTTP_URL_STRIP_PASS = 16;
    const HTTP_URL_STRIP_AUTH = 32;
    const HTTP_URL_STRIP_PORT = 64;
    const HTTP_URL_STRIP_PATH = 128;
    const HTTP_URL_STRIP_QUERY = 256;
    const HTTP_URL_STRIP_FRAGMENT = 512;
    const HTTP_URL_STRIP_ALL = 1024;

    const MONTH_MAPPING = [
        'ENERO' => 1,
        'FEBRERO' => 2,
        'MARZO' => 3,
        'ABRIL' => 4,
        'MAYO' => 5,
        'JUNIO' => 6,
        'JULIO' => 7,
        'AGOSTO' => 8,
        'SEPTIEMBRE' => 9,
        'OCTUBRE' => 10,
        'NOVIEMBRE' => 11,
        'DICIEMBRE' => 12
    ];


    const APP_SECRET_KEY = "DimeSiConmigoQuiereHacerTravesuras";

    /**
     * A collapse icon, using in the dump_var function to allow collapsing
     * an array or object
     *
     * @var string
     */
    public static $icon_collapse = 'iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAMAAADXT/YiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo3MjlFRjQ2NkM5QzJFMTExOTA0MzkwRkI0M0ZCODY4RCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFNzFDNDQyNEMyQzkxMUUxOTU4MEM4M0UxRDA0MUVGNSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpFNzFDNDQyM0MyQzkxMUUxOTU4MEM4M0UxRDA0MUVGNSIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3NDlFRjQ2NkM5QzJFMTExOTA0MzkwRkI0M0ZCODY4RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3MjlFRjQ2NkM5QzJFMTExOTA0MzkwRkI0M0ZCODY4RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PuF4AWkAAAA2UExURU9t2DBStczM/1h16DNmzHiW7iNFrypMvrnD52yJ4ezs7Onp6ejo6P///+Tk5GSG7D9h5SRGq0Q2K74AAAA/SURBVHjaLMhZDsAgDANRY3ZISnP/y1ZWeV+jAeuRSky6cKL4ryDdSggP8UC7r6GvR1YHxjazPQDmVzI/AQYAnFQDdVSJ80EAAAAASUVORK5CYII=';

    /**
     * A collapse icon, using in the dump_var function to allow collapsing
     * an array or object
     *
     * @var string
     */
    public static $icon_expand = 'iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAMAAADXT/YiAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo3MTlFRjQ2NkM5QzJFMTExOTA0MzkwRkI0M0ZCODY4RCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpFQzZERTJDNEMyQzkxMUUxODRCQzgyRUNDMzZEQkZFQiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpFQzZERTJDM0MyQzkxMUUxODRCQzgyRUNDMzZEQkZFQiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3MzlFRjQ2NkM5QzJFMTExOTA0MzkwRkI0M0ZCODY4RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3MTlFRjQ2NkM5QzJFMTExOTA0MzkwRkI0M0ZCODY4RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PkmDvWIAAABIUExURU9t2MzM/3iW7ubm59/f5urq85mZzOvr6////9ra38zMzObm5rfB8FZz5myJ4SNFrypMvjBStTNmzOvr+mSG7OXl8T9h5SRGq/OfqCEAAABKSURBVHjaFMlbEoAwCEPRULXF2jdW9r9T4czcyUdA4XWB0IgdNSybxU9amMzHzDlPKKu7Fd1e6+wY195jW0ARYZECxPq5Gn8BBgCr0gQmxpjKAwAAAABJRU5ErkJggg==';

    private static $hasArray = false;

    /**
     * Map of special non-ASCII characters and suitable ASCII replacement
     * characters.
     *
     * Part of the URLify.php Project <https://github.com/jbroadway/urlify/>
     *
     * @see https://github.com/jbroadway/urlify/blob/master/URLify.php
     */
    public static $maps = array(
        'de'            => array(/* German */
                                 'Ä' => 'Ae', 'Ö' => 'Oe', 'Ü' => 'Ue', 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
                                 'ẞ' => 'SS'
        ),
        'latin'         => array(
            'À'          => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Ă' => 'A', 'Æ' => 'AE', 'Ç' =>
                'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï'          => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' =>
                'O', 'Ő' => 'O', 'Ø' => 'O', 'Ș' => 'S', 'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U',
            'Ý'          => 'Y', 'Þ' => 'TH', 'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' =>
                'a', 'å' => 'a', 'ă' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì'          => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' =>
                'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 'ø' => 'o', 'ș' => 's', 'ț' => 't', 'ù' => 'u', 'ú' => 'u',
            'û'          => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y'
        ),
        'latin_symbols' => array(
            '©' => '(c)'
        ),
        'el'            => array(/* Greek */
                                 'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
                                 'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
                                 'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
                                 'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
                                 'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
                                 'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
                                 'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
                                 'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
                                 'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
                                 'Ϋ' => 'Y'
        ),
        'tr'            => array(/* Turkish */
                                 'ş' => 's', 'Ş' => 'S', 'ı' => 'i', 'İ' => 'I', 'ç' => 'c', 'Ç' => 'C', 'ü' => 'u', 'Ü' => 'U',
                                 'ö' => 'o', 'Ö' => 'O', 'ğ' => 'g', 'Ğ' => 'G'
        ),
        'ru'            => array(/* Russian */
                                 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
                                 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
                                 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
                                 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
                                 'я' => 'ya',
                                 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
                                 'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
                                 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
                                 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
                                 'Я' => 'Ya',
                                 '№' => ''
        ),
        'uk'            => array(/* Ukrainian */
                                 'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G', 'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g'
        ),
        'cs'            => array(/* Czech */
                                 'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
                                 'ž' => 'z', 'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T',
                                 'Ů' => 'U', 'Ž' => 'Z'
        ),
        'pl'            => array(/* Polish */
                                 'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
                                 'ż' => 'z', 'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S',
                                 'Ź' => 'Z', 'Ż' => 'Z'
        ),
        'ro'            => array(/* Romanian */
                                 'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ț' => 't', 'Ţ' => 'T', 'ţ' => 't'
        ),
        'lv'            => array(/* Latvian */
                                 'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
                                 'š' => 's', 'ū' => 'u', 'ž' => 'z', 'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i',
                                 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z'
        ),
        'lt'            => array(/* Lithuanian */
                                 'ą' => 'a', 'č' => 'c', 'ę' => 'e', 'ė' => 'e', 'į' => 'i', 'š' => 's', 'ų' => 'u', 'ū' => 'u', 'ž' => 'z',
                                 'Ą' => 'A', 'Č' => 'C', 'Ę' => 'E', 'Ė' => 'E', 'Į' => 'I', 'Š' => 'S', 'Ų' => 'U', 'Ū' => 'U', 'Ž' => 'Z'
        ),
        'vn'            => array(/* Vietnamese */
                                 'Á' => 'A', 'À' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A', 'Ă' => 'A', 'Ắ' => 'A', 'Ằ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A', 'Â' => 'A', 'Ấ' => 'A', 'Ầ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
                                 'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a', 'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
                                 'É' => 'E', 'È' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E', 'Ê' => 'E', 'Ế' => 'E', 'Ề' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
                                 'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e', 'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
                                 'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I', 'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
                                 'Ó' => 'O', 'Ò' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O', 'Ô' => 'O', 'Ố' => 'O', 'Ồ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O', 'Ơ' => 'O', 'Ớ' => 'O', 'Ờ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
                                 'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o', 'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
                                 'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U', 'Ư' => 'U', 'Ứ' => 'U', 'Ừ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
                                 'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u', 'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
                                 'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y', 'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
                                 'Đ' => 'D', 'đ' => 'd'
        ),
        'ar'            => array(/* Arabic */
                                 'أ' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'g', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd',
                                 'ذ' => 'th', 'ر' => 'r', 'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd', 'ط' => 't',
                                 'ظ' => 'th', 'ع' => 'aa', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'k', 'ك' => 'k', 'ل' => 'l', 'م' => 'm',
                                 'ن' => 'n', 'ه' => 'h', 'و' => 'o', 'ي' => 'y'
        ),
        'sr'            => array(/* Serbian */
                                 'ђ' => 'dj', 'ј' => 'j', 'љ' => 'lj', 'њ' => 'nj', 'ћ' => 'c', 'џ' => 'dz', 'đ' => 'dj',
                                 'Ђ' => 'Dj', 'Ј' => 'j', 'Љ' => 'Lj', 'Њ' => 'Nj', 'Ћ' => 'C', 'Џ' => 'Dz', 'Đ' => 'Dj'
        ),
        'az'            => array(/* Azerbaijani */
                                 'ç' => 'c', 'ə' => 'e', 'ğ' => 'g', 'ı' => 'i', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
                                 'Ç' => 'C', 'Ə' => 'E', 'Ğ' => 'G', 'İ' => 'I', 'Ö' => 'O', 'Ş' => 'S', 'Ü' => 'U'
        ),
    );

    /**
     * The character map for the designated language
     *
     * @see https://github.com/jbroadway/urlify/blob/master/URLify.php
     */
    private static $map = array();

    /**
     * The character list as a string.
     *
     * @see https://github.com/jbroadway/urlify/blob/master/URLify.php
     */
    private static $chars = '';

    /**
     * The character list as a regular expression.
     *
     * @see https://github.com/jbroadway/urlify/blob/master/URLify.php
     */
    private static $regex = '';

    /**
     * The current language
     *
     * @see https://github.com/jbroadway/urlify/blob/master/URLify.php
     */
    private static $language = '';

    /**
     * Initializes the character map.
     *
     * Part of the URLify.php Project <https://github.com/jbroadway/urlify/>
     *
     * @see https://github.com/jbroadway/urlify/blob/master/URLify.php
     */
    private static function initLanguageMap($language = '')
    {
        if (count(self::$map) > 0 && (($language == '') || ($language == self::$language))) {
            return;
        }

        // Is a specific map associated with $language?
        if (isset(self::$maps[$language]) && is_array(self::$maps[$language])) {
            // Move this map to end. This means it will have priority over others
            $m = self::$maps[$language];
            unset(self::$maps[$language]);
            self::$maps[$language] = $m;
        }

        // Reset static vars
        self::$language = $language;
        self::$map      = array();
        self::$chars    = '';

        foreach (self::$maps as $map) {
            foreach ($map as $orig => $conv) {
                self::$map[$orig] = $conv;
                self::$chars      .= $orig;
            }
        }

        self::$regex = '/[' . self::$chars . ']/u';
    }


    /**
     * Access an array index, retrieving the value stored there if it
     * exists or a default if it does not. This function allows you to
     * concisely access an index which may or may not exist without
     * raising a warning.
     *
     * @param array $var Array value to access
     * @param mixed $default Default value to return if the key is not
     *                         present in the array
     * @return mixed
     */
    public static function array_get(&$var, $default = null)
    {
        if (isset($var)) {
            return $var;
        }

        return $default;
    }

    /**
     * Display a variable's contents using nice HTML formatting and will
     * properly display the value of booleans as true or false
     *
     * @param mixed $var The variable to dump
     * @return string
     * @see var_dump_plain()
     *
     */
    public static function var_dump($var, $return = false, $expandLevel = 1)
    {
        self::$hasArray = false;
        $toggScript     = 'var colToggle = function(toggID) {var img = document.getElementById(toggID);if (document.getElementById(toggID + "-collapsable").style.display == "none") {document.getElementById(toggID + "-collapsable").style.display = "inline";setImg(toggID, 0);var previousSibling = document.getElementById(toggID + "-collapsable").previousSibling;while (previousSibling != null && (previousSibling.nodeType != 1 || previousSibling.tagName.toLowerCase() != "br")) {previousSibling = previousSibling.previousSibling;}} else {document.getElementById(toggID + "-collapsable").style.display = "none";setImg(toggID, 1);var previousSibling = document.getElementById(toggID + "-collapsable").previousSibling; while (previousSibling != null && (previousSibling.nodeType != 1 || previousSibling.tagName.toLowerCase() != "br")) {previousSibling = previousSibling.previousSibling;}}};';
        $imgScript      = 'var setImg = function(objID,imgID,addStyle) {var imgStore = ["data:image/png;base64,' . self::$icon_collapse . '", "data:image/png;base64,' . self::$icon_expand . '"];if (objID) {document.getElementById(objID).setAttribute("src", imgStore[imgID]);if (addStyle){document.getElementById(objID).setAttribute("style", "position:relative;left:-5px;top:-1px;cursor:pointer;");}}};';
        $jsCode         = preg_replace('/ +/', ' ', '<script>' . $toggScript . $imgScript . '</script>');
        $html           = '<pre style="margin-bottom: 18px;' .
            'background: #f7f7f9;' .
            'border: 1px solid #e1e1e8;' .
            'padding: 8px;' .
            'border-radius: 4px;' .
            '-moz-border-radius: 4px;' .
            '-webkit-border radius: 4px;' .
            'display: block;' .
            'font-size: 12.05px;' .
            'white-space: pre-wrap;' .
            'word-wrap: break-word;' .
            'color: #333;' .
            'font-family: Menlo,Monaco,Consolas,\'Courier New\',monospace;">';
        $done           = array();
        $html           .= self::var_dump_plain($var, intval($expandLevel), 0, $done);
        $html           .= '</pre>';

        if (self::$hasArray) {
            $html = $jsCode . $html;
        }

        if (!$return) {
            echo $html;
        }

        return $html;
    }

    /**
     * Display a variable's contents using nice HTML formatting (Without
     * the <pre> tag) and will properly display the values of variables
     * like booleans and resources. Supports collapsable arrays and objects
     * as well.
     *
     * @param mixed $var The variable to dump
     * @return string
     */
    public static function var_dump_plain($var, $expLevel, $depth = 0, $done = array())
    {
        $html = '';

        if ($expLevel > 0) {
            $expLevel--;
            $setImg   = 0;
            $setStyle = 'display:inline;';
        } elseif ($expLevel == 0) {
            $setImg   = 1;
            $setStyle = 'display:none;';
        } elseif ($expLevel < 0) {
            $setImg   = 0;
            $setStyle = 'display:inline;';
        }

        if (is_bool($var)) {
            $html .= '<span style="color:#588bff;">bool</span><span style="color:#999;">(</span><strong>' . (($var) ? 'true' : 'false') . '</strong><span style="color:#999;">)</span>';
        } elseif (is_int($var)) {
            $html .= '<span style="color:#588bff;">int</span><span style="color:#999;">(</span><strong>' . $var . '</strong><span style="color:#999;">)</span>';
        } elseif (is_float($var)) {
            $html .= '<span style="color:#588bff;">float</span><span style="color:#999;">(</span><strong>' . $var . '</strong><span style="color:#999;">)</span>';
        } elseif (is_string($var)) {
            $html .= '<span style="color:#588bff;">string</span><span style="color:#999;">(</span>' . strlen($var) . '<span style="color:#999;">)</span> <strong>"' . self::htmlentities($var) . '"</strong>';
        } elseif (is_null($var)) {
            $html .= '<strong>NULL</strong>';
        } elseif (is_resource($var)) {
            $html .= '<span style="color:#588bff;">resource</span>("' . get_resource_type($var) . '") <strong>"' . $var . '"</strong>';
        } elseif (is_array($var)) {
            // Check for recursion
            if ($depth > 0) {
                foreach ($done as $prev) {
                    if ($prev === $var) {
                        $html .= '<span style="color:#588bff;">array</span>(' . count($var) . ') *RECURSION DETECTED*';
                        return $html;
                    }
                }

                // Keep track of variables we have already processed to detect recursion
                $done[] = &$var;
            }

            self::$hasArray = true;
            $uuid           = 'include-php-' . uniqid() . mt_rand(1, 1000000);

            $html .= (!empty($var) ? ' <img id="' . $uuid . '" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" onclick="javascript:colToggle(this.id);" /><script>setImg("' . $uuid . '",' . $setImg . ',1);</script>' : '') . '<span style="color:#588bff;">array</span>(' . count($var) . ')';
            if (!empty($var)) {
                $html .= ' <span id="' . $uuid . '-collapsable" style="' . $setStyle . '"><br />[<br />';

                $indent      = 4;
                $longest_key = 0;

                foreach ($var as $key => $value) {
                    if (is_string($key)) {
                        $longest_key = max($longest_key, strlen($key) + 2);
                    } else {
                        $longest_key = max($longest_key, strlen($key));
                    }
                }

                foreach ($var as $key => $value) {
                    if (is_numeric($key)) {
                        $html .= str_repeat(' ', $indent) . str_pad($key, $longest_key, ' ');
                    } else {
                        $html .= str_repeat(' ', $indent) . str_pad('"' . self::htmlentities($key) . '"', $longest_key, ' ');
                    }

                    $html .= ' => ';

                    $value = explode('<br />', self::var_dump_plain($value, $expLevel, $depth + 1, $done));

                    foreach ($value as $line => $val) {
                        if ($line != 0) {
                            $value[$line] = str_repeat(' ', $indent * 2) . $val;
                        }
                    }

                    $html .= implode('<br />', $value) . '<br />';
                }

                $html .= ']</span>';
            }
        } elseif (is_object($var)) {
            // Check for recursion
            foreach ($done as $prev) {
                if ($prev === $var) {
                    $html .= '<span style="color:#588bff;">object</span>(' . get_class($var) . ') *RECURSION DETECTED*';
                    return $html;
                }
            }

            // Keep track of variables we have already processed to detect recursion
            $done[] = &$var;

            self::$hasArray = true;
            $uuid           = 'include-php-' . uniqid() . mt_rand(1, 1000000);

            $html .= ' <img id="' . $uuid . '" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" onclick="javascript:colToggle(this.id);" /><script>setImg("' . $uuid . '",' . $setImg . ',1);</script><span style="color:#588bff;">object</span>(' . get_class($var) . ') <span id="' . $uuid . '-collapsable" style="' . $setStyle . '"><br />[<br />';

            $varArray = (array)$var;

            $indent      = 4;
            $longest_key = 0;

            foreach ($varArray as $key => $value) {
                if (substr($key, 0, 2) == "\0*") {
                    unset($varArray[$key]);
                    $key            = 'protected:' . substr($key, 3);
                    $varArray[$key] = $value;
                } elseif (substr($key, 0, 1) == "\0") {
                    unset($varArray[$key]);
                    $key            = 'private:' . substr($key, 1, strpos(substr($key, 1), "\0")) . ':' . substr($key, strpos(substr($key, 1), "\0") + 2);
                    $varArray[$key] = $value;
                }

                if (is_string($key)) {
                    $longest_key = max($longest_key, strlen($key) + 2);
                } else {
                    $longest_key = max($longest_key, strlen($key));
                }
            }

            foreach ($varArray as $key => $value) {
                if (is_numeric($key)) {
                    $html .= str_repeat(' ', $indent) . str_pad($key, $longest_key, ' ');
                } else {
                    $html .= str_repeat(' ', $indent) . str_pad('"' . self::htmlentities($key) . '"', $longest_key, ' ');
                }

                $html .= ' => ';

                $value = explode('<br />', self::var_dump_plain($value, $expLevel, $depth + 1, $done));

                foreach ($value as $line => $val) {
                    if ($line != 0) {
                        $value[$line] = str_repeat(' ', $indent * 2) . $val;
                    }
                }

                $html .= implode('<br />', $value) . '<br />';
            }

            $html .= ']</span>';
        }

        return $html;
    }

    /**
     * Converts any accent characters to their equivalent normal characters
     * and converts any other non-alphanumeric characters to dashes, then
     * converts any sequence of two or more dashes to a single dash. This
     * function generates slugs safe for use as URLs, and if you pass true
     * as the second parameter, it will create strings safe for use as CSS
     * classes or IDs.
     *
     * @param string $string A string to convert to a slug
     * @param string $separator The string to separate words with
     * @param boolean $css_mode Whether or not to generate strings safe for
     *                             CSS classes/IDs (Default to false)
     * @return  string
     */
    public static function slugify($string, $separator = '-', $css_mode = false)
    {
        // Compatibility with 1.0.* parameter ordering for semvar
        if ($separator === true || $separator === false) {
            $css_mode  = $separator;
            $separator = '-';

            // Raise deprecation error
            trigger_error(
                'util::slugify() now takes $css_mode as the third parameter, please update your code',
                E_USER_DEPRECATED
            );
        }

        $slug = preg_replace('/([^a-z0-9]+)/', $separator, strtolower(self::remove_accents($string)));

        if ($css_mode) {
            $digits = array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');

            if (is_numeric(substr($slug, 0, 1))) {
                $slug = $digits[substr($slug, 0, 1)] . substr($slug, 1);
            }
        }

        return $slug;
    }

    /**
     * Checks to see if a string is utf8 encoded.
     *
     * NOTE: This function checks for 5-Byte sequences, UTF8
     *       has Bytes Sequences with a maximum length of 4.
     *
     * Written by Tony Ferrara <http://blog.ircmaxwell.com>
     *
     * @param string $string The string to be checked
     * @return boolean
     */
    public static function seems_utf8($string)
    {
        if (function_exists('mb_check_encoding')) {
            // If mbstring is available, this is significantly faster than
            // using PHP regexps.
            return mb_check_encoding($string, 'UTF-8');
        }

        // @codeCoverageIgnoreStart
        return self::seemsUtf8Regex($string);
        // @codeCoverageIgnoreEnd
    }

    /**
     * A non-Mbstring UTF-8 checker.
     *
     * @param $string
     * @return bool
     */
    protected static function seemsUtf8Regex($string)
    {
        // Obtained from http://stackoverflow.com/a/11709412/430062 with permission.
        $regex = '/(
    [\xC0-\xC1] # Invalid UTF-8 Bytes
    | [\xF5-\xFF] # Invalid UTF-8 Bytes
    | \xE0[\x80-\x9F] # Overlong encoding of prior code point
    | \xF0[\x80-\x8F] # Overlong encoding of prior code point
    | [\xC2-\xDF](?![\x80-\xBF]) # Invalid UTF-8 Sequence Start
    | [\xE0-\xEF](?![\x80-\xBF]{2}) # Invalid UTF-8 Sequence Start
    | [\xF0-\xF4](?![\x80-\xBF]{3}) # Invalid UTF-8 Sequence Start
    | (?<=[\x0-\x7F\xF5-\xFF])[\x80-\xBF] # Invalid UTF-8 Sequence Middle
    | (?<![\xC2-\xDF]|[\xE0-\xEF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4]|[\xF0-\xF4][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF]{2})[\x80-\xBF] # Overlong Sequence
    | (?<=[\xE0-\xEF])[\x80-\xBF](?![\x80-\xBF]) # Short 3 byte sequence
    | (?<=[\xF0-\xF4])[\x80-\xBF](?![\x80-\xBF]{2}) # Short 4 byte sequence
    | (?<=[\xF0-\xF4][\x80-\xBF])[\x80-\xBF](?![\x80-\xBF]) # Short 4 byte sequence (2)
)/x';

        return !preg_match($regex, $string);
    }

    /**
     * Nice formatting for computer sizes (Bytes).
     *
     * @param integer $bytes The number in bytes to format
     * @param integer $decimals The number of decimal points to include
     * @return  string
     */
    public static function size_format($bytes, $decimals = 0)
    {
        $bytes = floatval($bytes);

        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < pow(1024, 2)) {
            return number_format($bytes / 1024, $decimals, '.', '') . ' KiB';
        } elseif ($bytes < pow(1024, 3)) {
            return number_format($bytes / pow(1024, 2), $decimals, '.', '') . ' MiB';
        } elseif ($bytes < pow(1024, 4)) {
            return number_format($bytes / pow(1024, 3), $decimals, '.', '') . ' GiB';
        } elseif ($bytes < pow(1024, 5)) {
            return number_format($bytes / pow(1024, 4), $decimals, '.', '') . ' TiB';
        } elseif ($bytes < pow(1024, 6)) {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PiB';
        } else {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PiB';
        }
    }

    /**
     * Serialize data, if needed.
     *
     * @param mixed $data Data that might need to be serialized
     * @return mixed
     */
    public static function maybe_serialize($data)
    {
        if (is_array($data) || is_object($data)) {
            return serialize($data);
        }

        return $data;
    }

    /**
     * Unserialize value only if it is serialized.
     *
     * @param string $data A variable that may or may not be serialized
     * @return mixed
     */
    public static function maybe_unserialize($data)
    {
        // If it isn't a string, it isn't serialized
        if (!is_string($data)) {
            return $data;
        }

        $data = trim($data);

        // Is it the serialized NULL value?
        if ($data === 'N;') {
            return null;
        }

        $length = strlen($data);

        // Check some basic requirements of all serialized strings
        if ($length < 4 || $data[1] !== ':' || ($data[$length - 1] !== ';' && $data[$length - 1] !== '}')) {
            return $data;
        }

        // $data is the serialized false value
        if ($data === 'b:0;') {
            return false;
        }

        // Don't attempt to unserialize data that isn't serialized
        $uns = @unserialize($data);

        // Data failed to unserialize?
        if ($uns === false) {
            $uns = @unserialize(self::fix_broken_serialization($data));

            if ($uns === false) {
                return $data;
            } else {
                return $uns;
            }
        } else {
            return $uns;
        }
    }

    /**
     * Check value to find if it was serialized.
     *
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @param mixed $data Value to check to see if was serialized
     * @return boolean
     */
    public static function is_serialized($data)
    {
        // If it isn't a string, it isn't serialized
        if (!is_string($data)) {
            return false;
        }

        $data = trim($data);

        // Is it the serialized NULL value?
        if ($data === 'N;') {
            return true;
        } // Is it a serialized boolean?
        elseif ($data === 'b:0;' || $data === 'b:1;') {
            return true;
        }

        $length = strlen($data);

        // Check some basic requirements of all serialized strings
        if ($length < 4 || $data[1] !== ':' || ($data[$length - 1] !== ';' && $data[$length - 1] !== '}')) {
            return false;
        }

        return @unserialize($data) !== false;
    }

    /**
     * Unserializes partially-corrupted arrays that occur sometimes. Addresses
     * specifically the `unserialize(): Error at offset xxx of yyy bytes` error.
     *
     * NOTE: This error can *frequently* occur with mismatched character sets
     * and higher-than-ASCII characters.
     *
     * Contributed by Theodore R. Smith of PHP Experts, Inc. <http://www.phpexperts.pro/>
     *
     * @param string $brokenSerializedData
     * @return string
     */
    public static function fix_broken_serialization($brokenSerializedData)
    {
        $fixdSerializedData = preg_replace_callback('!s:(\d+):"(.*?)";!', function ($matches) {
            $snip = $matches[2];
            return 's:' . strlen($snip) . ':"' . $snip . '";';
        }, $brokenSerializedData);

        return $fixdSerializedData;
    }

    /**
     * Checks to see if the page is being server over SSL or not
     *
     * @return boolean
     */
    public static function is_https()
    {
        return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
    }

    /**
     * Add or remove query arguments to the URL.
     *
     * @param mixed $newKey Either newkey or an associative array
     * @param mixed $newValue Either newvalue or oldquery or uri
     * @param mixed $uri URI or URL to append the queru/queries to.
     * @return string
     */
    public static function add_query_arg($newKey, $newValue = null, $uri = null)
    {
        // Was an associative array of key => value pairs passed?
        if (is_array($newKey)) {
            $newParams = $newKey;

            // Was the URL passed as an argument?
            if (!is_null($newValue)) {
                $uri = $newValue;
            } elseif (!is_null($uri)) {
                $uri = $uri;
            } else {
                $uri = self::array_get($_SERVER['REQUEST_URI'], '');
            }
        } else {
            $newParams = array($newKey => $newValue);

            // Was the URL passed as an argument?
            $uri = is_null($uri) ? self::array_get($_SERVER['REQUEST_URI'], '') : $uri;
        }

        // Parse the URI into it's components
        $puri = parse_url($uri);

        if (isset($puri['query'])) {
            parse_str($puri['query'], $queryParams);
            $queryParams = array_merge($queryParams, $newParams);
        } elseif (isset($puri['path']) && strstr($puri['path'], '=') !== false) {
            $puri['query'] = $puri['path'];
            unset($puri['path']);
            parse_str($puri['query'], $queryParams);
            $queryParams = array_merge($queryParams, $newParams);
        } else {
            $queryParams = $newParams;
        }

        // Strip out any query params that are set to false
        foreach ($queryParams as $param => $value) {
            if ($value === false) {
                unset($queryParams[$param]);
            }
        }

        // Re-construct the query string
        $puri['query'] = http_build_query($queryParams);

        // Re-construct the entire URL
        $nuri = self::http_build_url($puri);

        // Make the URI consistent with our input
        if ($nuri[0] === '/' && strstr($uri, '/') === false) {
            $nuri = substr($nuri, 1);
        }

        if ($nuri[0] === '?' && strstr($uri, '?') === false) {
            $nuri = substr($nuri, 1);
        }

        return rtrim($nuri, '?');
    }

    /**
     * Removes an item or list from the query string.
     *
     * @param string|array $keys Query key or keys to remove.
     * @param bool $uri When false uses the $_SERVER value
     * @return string
     */
    public static function remove_query_arg($keys, $uri = null)
    {
        if (is_array($keys)) {
            return self::add_query_arg(array_combine($keys, array_fill(0, count($keys), null)), $uri);
        }

        return self::add_query_arg(array($keys => null), $uri);
    }

    /**
     * Build a URL.
     *
     * The parts of the second URL will be merged into the first according to
     * the flags argument.
     *
     * @param mixed $url (part(s) of) an URL in form of a string or
     *                       associative array like parse_url() returns
     * @param mixed $parts same as the first argument
     * @param int $flags a bitmask of binary or'ed HTTP_URL constants;
     *                       HTTP_URL_REPLACE is the default
     * @param array $new_url if set, it will be filled with the parts of the
     *                       composed url like parse_url() would return
     * @return string
     * @see https://github.com/jakeasmith/http_build_url/
     *
     * @author Jake Smith <theman@jakeasmith.com>
     */
    public static function http_build_url($url, $parts = array(), $flags = self::HTTP_URL_REPLACE, &$new_url = array())
    {
        is_array($url) || $url = parse_url($url);
        is_array($parts) || $parts = parse_url($parts);

        isset($url['query']) && is_string($url['query']) || $url['query'] = null;
        isset($parts['query']) && is_string($parts['query']) || $parts['query'] = null;

        $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');

        // HTTP_URL_STRIP_ALL and HTTP_URL_STRIP_AUTH cover several other flags.
        if ($flags & self::HTTP_URL_STRIP_ALL) {
            $flags |= self::HTTP_URL_STRIP_USER | self::HTTP_URL_STRIP_PASS
                | self::HTTP_URL_STRIP_PORT | self::HTTP_URL_STRIP_PATH
                | self::HTTP_URL_STRIP_QUERY | self::HTTP_URL_STRIP_FRAGMENT;
        } elseif ($flags & self::HTTP_URL_STRIP_AUTH) {
            $flags |= self::HTTP_URL_STRIP_USER | self::HTTP_URL_STRIP_PASS;
        }

        // Schema and host are alwasy replaced
        foreach (array('scheme', 'host') as $part) {
            if (isset($parts[$part])) {
                $url[$part] = $parts[$part];
            }
        }

        if ($flags & self::HTTP_URL_REPLACE) {
            foreach ($keys as $key) {
                if (isset($parts[$key])) {
                    $url[$key] = $parts[$key];
                }
            }
        } else {
            if (isset($parts['path']) && ($flags & self::HTTP_URL_JOIN_PATH)) {
                if (isset($url['path']) && substr($parts['path'], 0, 1) !== '/') {
                    $url['path'] = rtrim(
                            str_replace(basename($url['path']), '', $url['path']),
                            '/'
                        ) . '/' . ltrim($parts['path'], '/');
                } else {
                    $url['path'] = $parts['path'];
                }
            }

            if (isset($parts['query']) && ($flags & self::HTTP_URL_JOIN_QUERY)) {
                if (isset($url['query'])) {
                    parse_str($url['query'], $url_query);
                    parse_str($parts['query'], $parts_query);

                    $url['query'] = http_build_query(
                        array_replace_recursive(
                            $url_query,
                            $parts_query
                        )
                    );
                } else {
                    $url['query'] = $parts['query'];
                }
            }
        }

        if (isset($url['path']) && substr($url['path'], 0, 1) !== '/') {
            $url['path'] = '/' . $url['path'];
        }

        foreach ($keys as $key) {
            $strip = 'HTTP_URL_STRIP_' . strtoupper($key);
            if ($flags & constant('utilphp\\util::' . $strip)) {
                unset($url[$key]);
            }
        }

        $parsed_string = '';

        if (isset($url['scheme'])) {
            $parsed_string .= $url['scheme'] . '://';
        }

        if (isset($url['user'])) {
            $parsed_string .= $url['user'];

            if (isset($url['pass'])) {
                $parsed_string .= ':' . $url['pass'];
            }

            $parsed_string .= '@';
        }

        if (isset($url['host'])) {
            $parsed_string .= $url['host'];
        }

        if (isset($url['port'])) {
            $parsed_string .= ':' . $url['port'];
        }

        if (!empty($url['path'])) {
            $parsed_string .= $url['path'];
        } else {
            $parsed_string .= '/';
        }

        if (isset($url['query'])) {
            $parsed_string .= '?' . $url['query'];
        }

        if (isset($url['fragment'])) {
            $parsed_string .= '#' . $url['fragment'];
        }

        $new_url = $url;

        return $parsed_string;
    }

    /**
     * Converts many english words that equate to true or false to boolean.
     *
     * Supports 'y', 'n', 'yes', 'no' and a few other variations.
     *
     * @param string $string The string to convert to boolean
     * @param bool $default The value to return if we can't match any
     *                          yes/no words
     * @return boolean
     */
    public static function str_to_bool($string, $default = false)
    {
        $yes_words = 'affirmative|all right|aye|indubitably|most assuredly|ok|of course|okay|sure thing|y|yes+|yea|yep|sure|yeah|true|t|on|1|oui|vrai';
        $no_words  = 'no*|no way|nope|nah|na|never|absolutely not|by no means|negative|never ever|false|f|off|0|non|faux';

        if (preg_match('/^(' . $yes_words . ')$/i', $string)) {
            return true;
        } elseif (preg_match('/^(' . $no_words . ')$/i', $string)) {
            return false;
        }

        return $default;
    }

    /**
     * Check if a string starts with the given string.
     *
     * @param string $string
     * @param string $starts_with
     * @return boolean
     */
    public static function starts_with($string, $starts_with)
    {
        return strpos($string, $starts_with) === 0;
    }

    /**
     * Check if a string ends with the given string.
     *
     * @param string $string
     * @param string $starts_with
     * @return boolean
     */
    public static function ends_with($string, $ends_with)
    {
        return substr($string, -strlen($ends_with)) === $ends_with;
    }

    /**
     * Check if a string contains another string.
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public static function str_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }

    /**
     * Check if a string contains another string. This version is case
     * insensitive.
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public static function str_icontains($haystack, $needle)
    {
        return stripos($haystack, $needle) !== false;
    }

    /**
     * Return the file extension of the given filename.
     *
     * @param string $filename
     * @return string
     */
    public static function get_file_ext($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * Removes a directory (and its contents) recursively.
     *
     * Contributed by Askar (ARACOOL) <https://github.com/ARACOOOL>
     *
     * @param string $dir The directory to be deleted recursively
     * @param bool $traverseSymlinks Delete contents of symlinks recursively
     * @return bool
     * @throws \RuntimeException
     */
    public static function rmdir($dir, $traverseSymlinks = false)
    {
        if (!file_exists($dir)) {
            return true;
        } elseif (!is_dir($dir)) {
            throw new \RuntimeException('Given path is not a directory');
        }

        if (!is_link($dir) || $traverseSymlinks) {
            foreach (scandir($dir) as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $currentPath = $dir . '/' . $file;

                if (is_dir($currentPath)) {
                    self::rmdir($currentPath, $traverseSymlinks);
                } elseif (!unlink($currentPath)) {
                    // @codeCoverageIgnoreStart
                    throw new \RuntimeException('Unable to delete ' . $currentPath);
                    // @codeCoverageIgnoreEnd
                }
            }
        }

        // Windows treats removing directory symlinks identically to removing directories.
        if (is_link($dir) && !defined('PHP_WINDOWS_VERSION_MAJOR')) {
            if (!unlink($dir)) {
                // @codeCoverageIgnoreStart
                throw new \RuntimeException('Unable to delete ' . $dir);
                // @codeCoverageIgnoreEnd
            }
        } else {
            if (!rmdir($dir)) {
                // @codeCoverageIgnoreStart
                throw new \RuntimeException('Unable to delete ' . $dir);
                // @codeCoverageIgnoreEnd
            }
        }

        return true;
    }

    /**
     * Convert entities, while preserving already-encoded entities.
     *
     * @param string $string The text to be converted
     * @return string
     */
    public static function htmlentities($string, $preserve_encoded_entities = false)
    {
        if ($preserve_encoded_entities) {
            // @codeCoverageIgnoreStart
            if (defined('HHVM_VERSION')) {
                $translation_table = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
            } else {
                $translation_table = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES, self::mbInternalEncoding());
            }
            // @codeCoverageIgnoreEnd

            $translation_table[chr(38)] = '&';
            return preg_replace('/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/', '&amp;', strtr($string, $translation_table));
        }

        return htmlentities($string, ENT_QUOTES, self::mbInternalEncoding());
    }

    /**
     * Convert >, <, ', " and & to html entities, but preserves entities that
     * are already encoded.
     *
     * @param string $string The text to be converted
     * @return  string
     */
    public static function htmlspecialchars($string, $preserve_encoded_entities = false)
    {
        if ($preserve_encoded_entities) {
            // @codeCoverageIgnoreStart
            if (defined('HHVM_VERSION')) {
                $translation_table = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES);
            } else {
                $translation_table = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES, self::mbInternalEncoding());
            }
            // @codeCoverageIgnoreEnd

            $translation_table[chr(38)] = '&';

            return preg_replace('/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/', '&amp;', strtr($string, $translation_table));
        }

        return htmlentities($string, ENT_QUOTES, self::mbInternalEncoding());
    }

    /**
     * Transliterates characters to their ASCII equivalents.
     *
     * Part of the URLify.php Project <https://github.com/jbroadway/urlify/>
     *
     * @see https://github.com/jbroadway/urlify/blob/master/URLify.php
     *
     * @param string $text Text that might have not-ASCII characters
     * @param string $language Specifies a priority for a specific language.
     * @return string Filtered string with replaced "nice" characters
     */
    public static function downcode($text, $language = '')
    {
        self::initLanguageMap($language);

        if (self::seems_utf8($text)) {
            if (preg_match_all(self::$regex, $text, $matches)) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $char = $matches[0][$i];
                    if (isset(self::$map[$char])) {
                        $text = str_replace($char, self::$map[$char], $text);
                    }
                }
            }
        } else {
            // Not a UTF-8 string so we assume its ISO-8859-1
            $search = "\x80\x83\x8a\x8e\x9a\x9e\x9f\xa2\xa5\xb5\xc0\xc1\xc2\xc3\xc4\xc5\xc7\xc8\xc9\xca\xcb\xcc\xcd";
            $search .= "\xce\xcf\xd1\xd2\xd3\xd4\xd5\xd6\xd8\xd9\xda\xdb\xdc\xdd\xe0\xe1\xe2\xe3\xe4\xe5\xe7\xe8\xe9";
            $search .= "\xea\xeb\xec\xed\xee\xef\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xff";
            $text   = strtr($text, $search, 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');

            // These latin characters should be represented by two characters so
            // we can't use strtr
            $complexSearch  = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
            $complexReplace = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $text           = str_replace($complexSearch, $complexReplace, $text);
        }

        return $text;
    }

    /**
     * Converts all accent characters to ASCII characters.
     *
     * If there are no accent characters, then the string given is just
     * returned.
     *
     * @param string $string Text that might have accent characters
     * @param string $language Specifies a priority for a specific language.
     * @return string Filtered  string with replaced "nice" characters
     */
    public static function remove_accents($string, $language = '')
    {
        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        return self::downcode($string, $language);
    }

    /**
     * Strip all witespaces from the given string.
     *
     * @param string $string The string to strip
     * @return string
     */
    public static function strip_space($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * Sanitize a string by performing the following operation :
     * - Remove accents
     * - Lower the string
     * - Remove punctuation characters
     * - Strip whitespaces
     *
     * @param string $string the string to sanitize
     * @return string
     */
    public static function sanitize_string($string)
    {
        $string = self::remove_accents($string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-zA-Z 0-9]+/', '', $string);
        $string = self::strip_space($string);

        return $string;
    }

    /**
     * Pads a given string with zeroes on the left.
     *
     * @param int $number The number to pad
     * @param int $length The total length of the desired string
     * @return string
     */
    public static function zero_pad($number, $length)
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Converts a unix timestamp to a relative time string, such as "3 days ago"
     * or "2 weeks ago".
     *
     * @param int $from The date to use as a starting point
     * @param string $to The date to compare to, defaults to now
     * @param bool $as_text
     * @param string $suffix The string to add to the end, defaults to " ago"
     * @param string $prefix
     * @return string
     * @throws \Exception
     */
    public static function human_time_diff($from, $to = '', $as_text = false, $suffix = ' ', $prefix = '')
    {
        if ($to == '') {
            $to = time();
        }

        $from = new \DateTime(date('Y-m-d H:i:s', $from));
        $to   = new \DateTime(date('Y-m-d H:i:s', $to));
        $diff = $from->diff($to);


        if ($diff->y > 1) {
            $text = $diff->y . ' años';
        } elseif ($diff->y == 1) {
            $text = '1 año';
        } elseif ($diff->m > 1) {
            $text = $diff->m . ' meses';
        } elseif ($diff->m == 1) {
            $text = '1 mes';
        } elseif ($diff->d > 7) {
            $text = ceil($diff->d / 7) . ' semanas';
        } elseif ($diff->d == 7) {
            $text = '1 semana';
        } elseif ($diff->d > 1) {
            $text = $diff->d . ' días';
        } elseif ($diff->d == 1) {
            $text = '1 día';
        } elseif ($diff->h > 1) {
            $text = $diff->h . ' horas';
        } elseif ($diff->h == 1) {
            $text = ' 1 hora';
        } elseif ($diff->i > 1) {
            $text = $diff->i . ' minutos';
        } elseif ($diff->i == 1) {
            $text = '1 minuto';
        } elseif ($diff->s > 1) {
            $text = $diff->s . ' segundos';
        } else {
            $text = '1 segundo';
        }

        if ($as_text) {
            $text = explode(' ', $text, 2);
            $text = self::number_to_word($text[0]) . ' ' . $text[1];
        }

        return $prefix . trim($text) . $suffix;
    }

    /**
     * Converts a number into the text equivalent. For example, 456 becomes four
     * hundred and fifty-six.
     *
     * Part of the IntToWords Project.
     *
     * @param int|float $number The number to convert into text
     * @return string
     */
    public static function number_to_word($number)
    {
        $number = (string)$number;

        if (strpos($number, '.') !== false) {
            list($number, $decimal) = explode('.', $number);
        } else {
            $decimal = false;
        }

        $output = '';

        if ($number[0] == '-') {
            $output = 'negative ';
            $number = ltrim($number, '-');
        } elseif ($number[0] == '+') {
            $output = 'positive ';
            $number = ltrim($number, '+');
        }

        if ($number[0] == '0') {
            $output .= 'zero';
        } else {
            $length  = 19;
            $number  = str_pad($number, 60, '0', STR_PAD_LEFT);
            $group   = rtrim(chunk_split($number, 3, ' '), ' ');
            $groups  = explode(' ', $group);
            $groups2 = array();

            foreach ($groups as $group) {
                $group[1]  = isset($group[1]) ? $group[1] : null;
                $group[2]  = isset($group[2]) ? $group[2] : null;
                $groups2[] = self::numberToWordThreeDigits($group[0], $group[1], $group[2]);
            }

            for ($z = 0; $z < count($groups2); $z++) {
                if ($groups2[$z] != '') {
                    $output .= $groups2[$z] . self::numberToWordConvertGroup($length - $z);
                    $output .= ($z < $length && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[$length] != '' && $groups[$length][0] == '0' ? ' and ' : ', ');
                }
            }

            $output = rtrim($output, ', ');
        }

        if ($decimal > 0) {
            $output .= ' point';

            for ($i = 0; $i < strlen($decimal); $i++) {
                $output .= ' ' . self::numberToWordConvertDigit($decimal[$i]);
            }
        }

        return $output;
    }

    protected static function numberToWordConvertGroup($index)
    {
        switch ($index) {
            case 11:
                return ' decillion';
            case 10:
                return ' nonillion';
            case 9:
                return ' octillion';
            case 8:
                return ' septillion';
            case 7:
                return ' sextillion';
            case 6:
                return ' quintrillion';
            case 5:
                return ' quadrillion';
            case 4:
                return ' trillion';
            case 3:
                return ' billion';
            case 2:
                return ' million';
            case 1:
                return ' thousand';
            case 0:
                return '';
        }

        return '';
    }

    protected static function numberToWordThreeDigits($digit1, $digit2, $digit3)
    {
        $output = '';

        if ($digit1 == '0' && $digit2 == '0' && $digit3 == '0') {
            return '';
        }

        if ($digit1 != '0') {
            $output .= self::numberToWordConvertDigit($digit1) . ' hundred';

            if ($digit2 != '0' || $digit3 != '0') {
                $output .= ' and ';
            }
        }
        if ($digit2 != '0') {
            $output .= self::numberToWordTwoDigits($digit2, $digit3);
        } elseif ($digit3 != '0') {
            $output .= self::numberToWordConvertDigit($digit3);
        }

        return $output;
    }

    protected static function numberToWordTwoDigits($digit1, $digit2)
    {
        if ($digit2 == '0') {
            switch ($digit1) {
                case '1':
                    return 'ten';
                case '2':
                    return 'twenty';
                case '3':
                    return 'thirty';
                case '4':
                    return 'forty';
                case '5':
                    return 'fifty';
                case '6':
                    return 'sixty';
                case '7':
                    return 'seventy';
                case '8':
                    return 'eighty';
                case '9':
                    return 'ninety';
            }
        } elseif ($digit1 == '1') {
            switch ($digit2) {
                case '1':
                    return 'eleven';
                case '2':
                    return 'twelve';
                case '3':
                    return 'thirteen';
                case '4':
                    return 'fourteen';
                case '5':
                    return 'fifteen';
                case '6':
                    return 'sixteen';
                case '7':
                    return 'seventeen';
                case '8':
                    return 'eighteen';
                case '9':
                    return 'nineteen';
            }
        } else {
            $second_digit = self::numberToWordConvertDigit($digit2);

            switch ($digit1) {
                case '2':
                    return "twenty-{$second_digit}";
                case '3':
                    return "thirty-{$second_digit}";
                case '4':
                    return "forty-{$second_digit}";
                case '5':
                    return "fifty-{$second_digit}";
                case '6':
                    return "sixty-{$second_digit}";
                case '7':
                    return "seventy-{$second_digit}";
                case '8':
                    return "eighty-{$second_digit}";
                case '9':
                    return "ninety-{$second_digit}";
            }
        }
    }

    /**
     * @param $digit
     * @return string
     * @throws \LogicException
     */
    protected static function numberToWordConvertDigit($digit)
    {
        switch ($digit) {
            case '0':
                return 'zero';
            case '1':
                return 'one';
            case '2':
                return 'two';
            case '3':
                return 'three';
            case '4':
                return 'four';
            case '5':
                return 'five';
            case '6':
                return 'six';
            case '7':
                return 'seven';
            case '8':
                return 'eight';
            case '9':
                return 'nine';
            default:
                throw new \LogicException('Not a number');
        }
    }

    /**
     * Transmit UTF-8 content headers if the headers haven't already been sent.
     *
     * @param string $content_type The content type to send out
     * @return boolean
     */
    public static function utf8_headers($content_type = 'text/html')
    {
        // @codeCoverageIgnoreStart
        if (!headers_sent()) {
            header('Content-type: ' . $content_type . '; charset=utf-8');

            return true;
        }

        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Transmit headers that force a browser to display the download file
     * dialog. Cross browser compatible. Only fires if headers have not
     * already been sent.
     *
     * @param string $filename The name of the filename to display to
     *                         browsers
     * @param string $content The content to output for the download.
     *                         If you don't specify this, just the
     *                         headers will be sent
     * @return boolean
     */
    public static function force_download($filename, $content = false)
    {
        // @codeCoverageIgnoreStart
        if (!headers_sent()) {
            // Required for some browsers
            if (ini_get('zlib.output_compression')) {
                @ini_set('zlib.output_compression', 'Off');
            }

            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            // Required for certain browsers
            header('Cache-Control: private', false);

            header('Content-Disposition: attachment; filename="' . basename(str_replace('"', '', $filename)) . '";');
            header('Content-Type: application/force-download');
            header('Content-Transfer-Encoding: binary');

            if ($content) {
                header('Content-Length: ' . strlen($content));
            }

            ob_clean();
            flush();

            if ($content) {
                echo $content;
            }

            return true;
        }

        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Sets the headers to prevent caching for the different browsers.
     *
     * Different browsers support different nocache headers, so several
     * headers must be sent so that all of them get the point that no
     * caching should occur
     *
     * @return boolean
     */
    public static function nocache_headers()
    {
        // @codeCoverageIgnoreStart
        if (!headers_sent()) {
            header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');

            return true;
        }

        return false;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Generates a string of random characters.
     *
     * @param integer $length The length of the string to
     *                                      generate
     * @param boolean $human_friendly Whether or not to make the
     *                                      string human friendly by
     *                                      removing characters that can be
     *                                      confused with other characters (
     *                                      O and 0, l and 1, etc)
     * @param boolean $include_symbols Whether or not to include
     *                                      symbols in the string. Can not
     *                                      be enabled if $human_friendly is
     *                                      true
     * @param boolean $no_duplicate_chars Whether or not to only use
     *                                      characters once in the string.
     * @return  string
     * @throws  LengthException  If $length is bigger than the available
     *                           character pool and $no_duplicate_chars is
     *                           enabled
     *
     */
    public static function random_string($length = 16, $human_friendly = true, $include_symbols = false, $no_duplicate_chars = false)
    {
        $nice_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefhjkmnprstuvwxyz23456789';
        $all_an     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $symbols    = '!@#$%^&*()~_-=+{}[]|:;<>,.?/"\'\\`';
        $string     = '';

        // Determine the pool of available characters based on the given parameters
        if ($human_friendly) {
            $pool = $nice_chars;
        } else {
            $pool = $all_an;

            if ($include_symbols) {
                $pool .= $symbols;
            }
        }

        if (!$no_duplicate_chars) {
            return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
        }

        // Don't allow duplicate letters to be disabled if the length is
        // longer than the available characters
        if ($no_duplicate_chars && strlen($pool) < $length) {
            throw new \LengthException('$length exceeds the size of the pool and $no_duplicate_chars is enabled');
        }

        // Convert the pool of characters into an array of characters and
        // shuffle the array
        $pool       = str_split($pool);
        $poolLength = count($pool);
        $rand       = mt_rand(0, $poolLength - 1);

        // Generate our string
        for ($i = 0; $i < $length; $i++) {
            $string .= $pool[$rand];

            // Remove the character from the array to avoid duplicates
            array_splice($pool, $rand, 1);

            // Generate a new number
            if (($poolLength - 2 - $i) > 0) {
                $rand = mt_rand(0, $poolLength - 2 - $i);
            } else {
                $rand = 0;
            }
        }

        return $string;
    }

    /**
     * Generate secure random string of given length
     * If 'openssl_random_pseudo_bytes' is not available
     * then generate random string using default function
     *
     * Part of the Laravel Project <https://github.com/laravel/laravel>
     *
     * @param int $length length of string
     * @return bool
     */
    public static function secure_random_string($length = 16)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length * 2);

            if ($bytes === false) {
                throw new \LengthException('$length is not accurate, unable to generate random string');
            }

            return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
        }

        // @codeCoverageIgnoreStart
        return static::random_string($length);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Check if a given string matches a given pattern.
     *
     * Contributed by Abhimanyu Sharma <https://github.com/abhimanyusharma003>
     *
     * @param string $pattern Parttern of string exptected
     * @param string $string String that need to be matched
     * @return bool
     */
    public static function match_string($pattern, $string, $caseSensitive = true)
    {
        if ($pattern == $string) {
            return true;
        }

        // Preg flags
        $flags = $caseSensitive ? '' : 'i';

        // Escape any regex special characters
        $pattern = preg_quote($pattern, '#');

        // Unescape * which is our wildcard character and change it to .*
        $pattern = str_replace('\*', '.*', $pattern);

        return (bool)preg_match('#^' . $pattern . '$#' . $flags, $string);
    }

    /**
     * Validate an email address.
     *
     * @param string $possible_email An email address to validate
     * @return bool
     */
    public static function validate_email($possible_email)
    {
        return (bool)filter_var($possible_email, FILTER_VALIDATE_EMAIL);
    }

    public static function validate_extension($possible_file)
    {
        if(pathinfo($possible_file, PATHINFO_EXTENSION)=="csv") {
            return true;
        } else {
            return false;
        }
    }

    public static function validate_file($possible_file)
    {
        if (file_exists($possible_file)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the URL to a user's gravatar.
     *
     * @param string $email The email of the user
     * @param integer $size The size of the gravatar
     * @return  string
     */
    public static function get_gravatar($email, $size = 32)
    {
        if (self::is_https()) {
            $url = 'https://secure.gravatar.com/';
        } else {
            $url = 'http://www.gravatar.com/';
        }

        $url .= 'avatar/' . md5($email) . '?s=' . (int)abs($size);

        return $url;
    }

    /**
     * Turns all of the links in a string into HTML links.
     *
     * Part of the LinkifyURL Project <https://github.com/jmrware/LinkifyURL>
     *
     * @param string $text The string to parse
     * @return string
     */
    public static function linkify($text)
    {
        $text                 = preg_replace('/&apos;/', '&#39;', $text); // IE does not handle &apos; entity!
        $section_html_pattern = '%# Rev:20100913_0900 github.com/jmrware/LinkifyURL
            # Section text into HTML <A> tags  and everything else.
              (                             # $1: Everything not HTML <A> tag.
                [^<]+(?:(?!<a\b)<[^<]*)*     # non A tag stuff starting with non-"<".
              |      (?:(?!<a\b)<[^<]*)+     # non A tag stuff starting with "<".
             )                              # End $1.
            | (                             # $2: HTML <A...>...</A> tag.
                <a\b[^>]*>                   # <A...> opening tag.
                [^<]*(?:(?!</a\b)<[^<]*)*    # A tag contents.
                </a\s*>                      # </A> closing tag.
             )                              # End $2:
            %ix';

        return preg_replace_callback($section_html_pattern, array(__CLASS__, 'linkifyCallback'), $text);
    }

    /**
     * Callback for the preg_replace in the linkify() method.
     *
     * Part of the LinkifyURL Project <https://github.com/jmrware/LinkifyURL>
     *
     * @param array $matches Matches from the preg_ function
     * @return string
     */
    protected static function linkifyRegex($text)
    {
        $url_pattern = '/# Rev:20100913_0900 github.com\/jmrware\/LinkifyURL
            # Match http & ftp URL that is not already linkified.
            # Alternative 1: URL delimited by (parentheses).
            (\() # $1 "(" start delimiter.
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $2: URL.
            (\)) # $3: ")" end delimiter.
            | # Alternative 2: URL delimited by [square brackets].
            (\[) # $4: "[" start delimiter.
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $5: URL.
            (\]) # $6: "]" end delimiter.
            | # Alternative 3: URL delimited by {curly braces}.
            (\{) # $7: "{" start delimiter.
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $8: URL.
            (\}) # $9: "}" end delimiter.
            | # Alternative 4: URL delimited by <angle brackets>.
            (<|&(?:lt|\#60|\#x3c);) # $10: "<" start delimiter (or HTML entity).
            ((?:ht|f)tps?:\/\/[a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]+) # $11: URL.
            (>|&(?:gt|\#62|\#x3e);) # $12: ">" end delimiter (or HTML entity).
            | # Alternative 5: URL not delimited by (), [], {} or <>.
            (# $13: Prefix proving URL not already linked.
            (?: ^ # Can be a beginning of line or string, or
            | [^=\s\'"\]] # a non-"=", non-quote, non-"]", followed by
           ) \s*[\'"]? # optional whitespace and optional quote;
            | [^=\s]\s+ # or... a non-equals sign followed by whitespace.
           ) # End $13. Non-prelinkified-proof prefix.
            (\b # $14: Other non-delimited URL.
            (?:ht|f)tps?:\/\/ # Required literal http, https, ftp or ftps prefix.
            [a-z0-9\-._~!$\'()*+,;=:\/?#[\]@%]+ # All URI chars except "&" (normal*).
            (?: # Either on a "&" or at the end of URI.
            (?! # Allow a "&" char only if not start of an...
            &(?:gt|\#0*62|\#x0*3e); # HTML ">" entity, or
            | &(?:amp|apos|quot|\#0*3[49]|\#x0*2[27]); # a [&\'"] entity if
            [.!&\',:?;]? # followed by optional punctuation then
            (?:[^a-z0-9\-._~!$&\'()*+,;=:\/?#[\]@%]|$) # a non-URI char or EOS.
           ) & # If neg-assertion true, match "&" (special).
            [a-z0-9\-._~!$\'()*+,;=:\/?#[\]@%]* # More non-& URI chars (normal*).
           )* # Unroll-the-loop (special normal*)*.
            [a-z0-9\-_~$()*+=\/#[\]@%] # Last char can\'t be [.!&\',;:?]
           ) # End $14. Other non-delimited URL.
            /imx';

        $url_replace = '$1$4$7$10$13<a href="$2$5$8$11$14">$2$5$8$11$14</a>$3$6$9$12';

        return preg_replace($url_pattern, $url_replace, $text);
    }

    /**
     * Callback for the preg_replace in the linkify() method.
     *
     * Part of the LinkifyURL Project <https://github.com/jmrware/LinkifyURL>
     *
     * @param array $matches Matches from the preg_ function
     * @return string
     */
    protected static function linkifyCallback($matches)
    {
        if (isset($matches[2])) {
            return $matches[2];
        }

        return self::linkifyRegex($matches[1]);
    }

    /**
     * Return the current URL.
     *
     * @return string
     */
    public static function get_current_url()
    {
        $url = '';

        // Check to see if it's over https
        $is_https = self::is_https();
        if ($is_https) {
            $url .= 'https://';
        } else {
            $url .= 'http://';
        }

        // Was a username or password passed?
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $url .= $_SERVER['PHP_AUTH_USER'];

            if (isset($_SERVER['PHP_AUTH_PW'])) {
                $url .= ':' . $_SERVER['PHP_AUTH_PW'];
            }

            $url .= '@';
        }


        // We want the user to stay on the same host they are currently on,
        // but beware of security issues
        // see http://shiflett.org/blog/2006/mar/server-name-versus-http-host
        $url .= $_SERVER['HTTP_HOST'];

        $port = $_SERVER['SERVER_PORT'];

        // Is it on a non standard port?
        if ($is_https && ($port != 443)) {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        } elseif (!$is_https && ($port != 80)) {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }

        // Get the rest of the URL
        if (!isset($_SERVER['REQUEST_URI'])) {
            // Microsoft IIS doesn't set REQUEST_URI by default
            $url .= $_SERVER['PHP_SELF'];

            if (isset($_SERVER['QUERY_STRING'])) {
                $url .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            $url .= $_SERVER['REQUEST_URI'];
        }

        return $url;
    }

    /**
     * Returns the IP address of the client.
     *
     * @param boolean $trust_proxy_headers Whether or not to trust the
     *                                       proxy headers HTTP_CLIENT_IP
     *                                       and HTTP_X_FORWARDED_FOR. ONLY
     *                                       use if your server is behind a
     *                                       proxy that sets these values
     * @return  string
     */
    public static function get_client_ip($trust_proxy_headers = false)
    {
        if (!$trust_proxy_headers) {
            return $_SERVER['REMOTE_ADDR'];
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Truncate a string to a specified length without cutting a word off.
     *
     * @param string $string The string to truncate
     * @param integer $length The length to truncate the string to
     * @param string $append Text to append to the string IF it gets
     *                           truncated, defaults to '...'
     * @return  string
     */
    public static function safe_truncate($string, $length, $append = '...')
    {
        $ret        = substr($string, 0, $length);
        $last_space = strrpos($ret, ' ');

        if ($last_space !== false && $string != $ret) {
            $ret = substr($ret, 0, $last_space);
        }

        if ($ret != $string) {
            $ret .= $append;
        }

        return $ret;
    }


    /**
     * Truncate the string to given length of charactes.
     *
     * @param $string
     * @param $limit
     * @param string $append
     * @return string
     */
    public static function limit_characters($string, $limit = 100, $append = '...')
    {
        if (mb_strlen($string) <= $limit) {
            return $string;
        }

        return rtrim(mb_substr($string, 0, $limit, 'UTF-8')) . $append;
    }

    /**
     * Truncate the string to given length of words.
     *
     * @param $string
     * @param $limit
     * @param string $append
     * @return string
     */
    public static function limit_words($string, $limit = 100, $append = '...')
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $string, $matches);

        if (!isset($matches[0]) || strlen($string) === strlen($matches[0])) {
            return $string;
        }

        return rtrim($matches[0]) . $append;
    }

    /**
     * Returns the ordinal version of a number (appends th, st, nd, rd).
     *
     * @param string $number The number to append an ordinal suffix to
     * @return string
     */
    public static function ordinal($number)
    {
        $test_c = abs($number) % 10;
        $ext    = ((abs($number) % 100 < 21 && abs($number) % 100 > 4) ? 'th' : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));

        return $number . $ext;
    }

    /**
     * Returns the file permissions as a nice string, like -rw-r--r-- or false
     * if the file is not found.
     *
     * @param string $file The name of the file to get permissions form
     * @param int $perms Numerical value of permissions to display as text.
     * @return  string
     */
    public static function full_permissions($file, $perms = null)
    {
        if (is_null($perms)) {
            if (!file_exists($file)) {
                return false;
            }
            $perms = fileperms($file);
        }

        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x') :
            (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x') :
            (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x') :
            (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }

    /**
     * Returns the first element in an array.
     *
     * @param array $array
     * @return mixed
     */
    public static function array_first(array $array)
    {
        return reset($array);
    }

    /**
     * Returns the last element in an array.
     *
     * @param array $array
     * @return mixed
     */
    public static function array_last(array $array)
    {
        return end($array);
    }

    /**
     * Returns the first key in an array.
     *
     * @param array $array
     * @return int|string
     */
    public static function array_first_key(array $array)
    {
        reset($array);

        return key($array);
    }

    /**
     * Returns the last key in an array.
     *
     * @param array $array
     * @return int|string
     */
    public static function array_last_key(array $array)
    {
        end($array);

        return key($array);
    }

    /**
     * Flatten a multi-dimensional array into a one dimensional array.
     *
     * Contributed by Theodore R. Smith of PHP Experts, Inc. <http://www.phpexperts.pro/>
     *
     * @param array $array The array to flatten
     * @param boolean $preserve_keys Whether or not to preserve array keys.
     *                                Keys from deeply nested arrays will
     *                                overwrite keys from shallowy nested arrays
     * @return array
     */
    public static function array_flatten(array $array, $preserve_keys = true)
    {
        $flattened = array();

        array_walk_recursive($array, function ($value, $key) use (&$flattened, $preserve_keys) {
            if ($preserve_keys && !is_int($key)) {
                $flattened[$key] = $value;
            } else {
                $flattened[] = $value;
            }
        });

        return $flattened;
    }

    /**
     * Accepts an array, and returns an array of values from that array as
     * specified by $field. For example, if the array is full of objects
     * and you call util::array_pluck($array, 'name'), the function will
     * return an array of values from $array[]->name.
     *
     * @param array $array An array
     * @param string $field The field to get values from
     * @param boolean $preserve_keys Whether or not to preserve the
     *                                   array keys
     * @param boolean $remove_nomatches If the field doesn't appear to be set,
     *                                   remove it from the array
     * @return array
     */
    public static function array_pluck(array $array, $field, $preserve_keys = true, $remove_nomatches = true)
    {
        $new_list = array();

        foreach ($array as $key => $value) {
            if (is_object($value)) {
                if (isset($value->{$field})) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value->{$field};
                    } else {
                        $new_list[] = $value->{$field};
                    }
                } elseif (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            } else {
                if (isset($value[$field])) {
                    if ($preserve_keys) {
                        $new_list[$key] = $value[$field];
                    } else {
                        $new_list[] = $value[$field];
                    }
                } elseif (!$remove_nomatches) {
                    $new_list[$key] = $value;
                }
            }
        }

        return $new_list;
    }

    /**
     * Searches for a given value in an array of arrays, objects and scalar
     * values. You can optionally specify a field of the nested arrays and
     * objects to search in.
     *
     * @param array $array The array to search
     * @param scalar $search The value to search for
     * @param string $field The field to search in, if not specified
     *                         all fields will be searched
     * @return boolean|scalar  False on failure or the array key on success
     */
    public static function array_search_deep(array $array, $search, $field = false)
    {
        // *grumbles* stupid PHP type system
        $search = (string)$search;

        foreach ($array as $key => $elem) {
            // *grumbles* stupid PHP type system
            $key = (string)$key;

            if ($field) {
                if (is_object($elem) && $elem->{$field} === $search) {
                    return $key;
                } elseif (is_array($elem) && $elem[$field] === $search) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            } else {
                if (is_object($elem)) {
                    $elem = (array)$elem;

                    if (in_array($search, $elem)) {
                        return $key;
                    }
                } elseif (is_array($elem) && in_array($search, $elem)) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            }
        }

        return false;
    }

    /**
     * Returns an array containing all the elements of arr1 after applying
     * the callback function to each one.
     *
     * @param string $callback Callback function to run for each
     *                               element in each array
     * @param array $array An array to run through the callback
     *                               function
     * @param boolean $on_nonscalar Whether or not to call the callback
     *                               function on nonscalar values
     *                               (Objects, resources, etc)
     * @return array
     */
    public static function array_map_deep(array $array, $callback, $on_nonscalar = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $args        = array($value, $callback, $on_nonscalar);
                $array[$key] = call_user_func_array(array(__CLASS__, __FUNCTION__), $args);
            } elseif (is_scalar($value) || $on_nonscalar) {
                $array[$key] = call_user_func($callback, $value);
            }
        }

        return $array;
    }

    public static function array_clean(array $array)
    {
        return array_filter($array);
    }

    /**
     * Wrapper to prevent errors if the user doesn't have the mbstring
     * extension installed.
     *
     * @param string $encoding
     * @return string
     */
    protected static function mbInternalEncoding($encoding = null)
    {
        if (function_exists('mb_internal_encoding')) {
            return $encoding ? mb_internal_encoding($encoding) : mb_internal_encoding();
        }

        // @codeCoverageIgnoreStart
        return 'UTF-8';
        // @codeCoverageIgnoreEnd
    }

    /**
     * Set the writable bit on a file to the minimum value that allows the user
     * running PHP to write to it.
     *
     * @param string $filename The filename to set the writable bit on
     * @param boolean $writable Whether to make the file writable or not
     * @return boolean
     */
    public static function set_writable($filename, $writable = true)
    {
        $stat = @stat($filename);

        if ($stat === false) {
            return false;
        }

        // We're on Windows
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            return true;
        }

        list($myuid, $mygid) = array(posix_geteuid(), posix_getgid());

        if ($writable) {
            // Set only the user writable bit (file is owned by us)
            if ($stat['uid'] == $myuid) {
                return chmod($filename, fileperms($filename) | 0200);
            }

            // Set only the group writable bit (file group is the same as us)
            if ($stat['gid'] == $mygid) {
                return chmod($filename, fileperms($filename) | 0220);
            }

            // Set the world writable bit (file isn't owned or grouped by us)
            return chmod($filename, fileperms($filename) | 0222);
        } else {
            // Set only the user writable bit (file is owned by us)
            if ($stat['uid'] == $myuid) {
                return chmod($filename, (fileperms($filename) | 0222) ^ 0222);
            }

            // Set only the group writable bit (file group is the same as us)
            if ($stat['gid'] == $mygid) {
                return chmod($filename, (fileperms($filename) | 0222) ^ 0022);
            }

            // Set the world writable bit (file isn't owned or grouped by us)
            return chmod($filename, (fileperms($filename) | 0222) ^ 0002);
        }
    }

    /**
     * Set the readable bit on a file to the minimum value that allows the user
     * running PHP to read to it.
     *
     * @param string $filename The filename to set the readable bit on
     * @param boolean $readable Whether to make the file readable or not
     * @return boolean
     */
    public static function set_readable($filename, $readable = true)
    {
        $stat = @stat($filename);

        if ($stat === false) {
            return false;
        }

        // We're on Windows
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            return true;
        }

        list($myuid, $mygid) = array(posix_geteuid(), posix_getgid());

        if ($readable) {
            // Set only the user readable bit (file is owned by us)
            if ($stat['uid'] == $myuid) {
                return chmod($filename, fileperms($filename) | 0400);
            }

            // Set only the group readable bit (file group is the same as us)
            if ($stat['gid'] == $mygid) {
                return chmod($filename, fileperms($filename) | 0440);
            }

            // Set the world readable bit (file isn't owned or grouped by us)
            return chmod($filename, fileperms($filename) | 0444);
        } else {
            // Set only the user readable bit (file is owned by us)
            if ($stat['uid'] == $myuid) {
                return chmod($filename, (fileperms($filename) | 0444) ^ 0444);
            }

            // Set only the group readable bit (file group is the same as us)
            if ($stat['gid'] == $mygid) {
                return chmod($filename, (fileperms($filename) | 0444) ^ 0044);
            }

            // Set the world readable bit (file isn't owned or grouped by us)
            return chmod($filename, (fileperms($filename) | 0444) ^ 0004);
        }
    }

    /**
     * Set the executable bit on a file to the minimum value that allows the
     * user running PHP to read to it.
     *
     * @param string $filename The filename to set the executable bit on
     * @param boolean $executable Whether to make the file executable or not
     * @return boolean
     */
    public static function set_executable($filename, $executable = true)
    {
        $stat = @stat($filename);

        if ($stat === false) {
            return false;
        }

        // We're on Windows
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            return true;
        }

        list($myuid, $mygid) = array(posix_geteuid(), posix_getgid());

        if ($executable) {
            // Set only the user readable bit (file is owned by us)
            if ($stat['uid'] == $myuid) {
                return chmod($filename, fileperms($filename) | 0100);
            }

            // Set only the group readable bit (file group is the same as us)
            if ($stat['gid'] == $mygid) {
                return chmod($filename, fileperms($filename) | 0110);
            }

            // Set the world readable bit (file isn't owned or grouped by us)
            return chmod($filename, fileperms($filename) | 0111);
        } else {
            // Set only the user readable bit (file is owned by us)
            if ($stat['uid'] == $myuid) {
                return chmod($filename, (fileperms($filename) | 0111) ^ 0111);
            }

            // Set only the group readable bit (file group is the same as us)
            if ($stat['gid'] == $mygid) {
                return chmod($filename, (fileperms($filename) | 0111) ^ 0011);
            }

            // Set the world readable bit (file isn't owned or grouped by us)
            return chmod($filename, (fileperms($filename) | 0111) ^ 0001);
        }
    }

    /**
     * Returns size of a given directory in bytes.
     *
     * @param string $dir
     * @return integer
     */
    public static function directory_size($dir)
    {
        $size = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS)) as $file => $key) {
            if ($key->isFile()) {
                $size += $key->getSize();
            }
        }
        return $size;
    }

    /**
     * Returns a home directory of current user.
     *
     * @return string
     */
    public static function get_user_directory()
    {
        if (isset($_SERVER['HOMEDRIVE'])) return $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'];
        else return $_SERVER['HOME'];
    }

    /**
     * Returns all paths inside a directory.
     *
     * @param string $dir
     * @return array
     */
    public static function directory_contents($dir)
    {
        $contents = array();
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS)) as $pathname => $fi) {
            $contents[] = $pathname;
        }
        natsort($contents);
        return $contents;
    }


    /**
     * Recupera un array con fecha de inicio y fin a partir de un string con formato :
     *
     *  d/m/Y - d/m/Y
     *
     * @param string $dateFormated
     *
     * @return array
     */
    public static function recoveryDateFromFilter($dateFormated, $withTime = false)
    {
        $dates     = explode(' - ', $dateFormated);
        $startDate = \DateTime::createFromFormat('d/m/Y', $dates[0]);
        $endDate   = \DateTime::createFromFormat('d/m/Y', $dates[1]);

        if ($withTime) {
            $startDate->setTime(0, 0, 0);
            $endDate->setTime(23, 59, 59);
        }

        return [$startDate, $endDate];
    }


    /**
     * Array de días entre dos fechas
     */
    public static function daysBetween(\DateTime $dateFrom, \DateTime $dateTo, $onlyWorkingDays = false)
    {

        $daysArray = [];


        for ($date = $dateFrom->format('Y-m-d'); $date <= $dateTo->format('Y-m-d'); $date = date("Y-m-d", strtotime($date . "+ 1 days"))) {
            $currentDate = \DateTime::createFromFormat('Y-m-d', $date);
            if ($onlyWorkingDays && ($currentDate->format('D') == "Sat" or $currentDate->format('D') == "Sun")) continue;
            $daysArray[$date]['from'] = \DateTime::createFromFormat('Y-m-d', $date)->setTime(0, 0, 0);
            $daysArray[$date]['to']   = \DateTime::createFromFormat('Y-m-d', $date)->setTime(23, 59, 59);
        }

        return $daysArray;

    }

    /**
     * Array de semanas entre dos fechas
     */
    public static function weeksBetween(\DateTime $dateFrom, \DateTime $dateTo)
    {

        $daysArray = [];


        for ($date = $dateFrom->format('Y-m-d'); $date <= $dateTo->format('Y-m-d'); $date = date("Y-m-d", strtotime($date . "+ 1 weeks"))) {

            // Lunes de la semana
            $mondayOfWeek = date("Y-m-d", strtotime('Monday this week ' . $date));
            // Domingo de la semana
            $sundayOfWeek = date("Y-m-d", strtotime('Sunday this week ' . $date));

            // Primera fecha
            if ($date == $dateFrom->format('Y-m-d')) {
                $dateFromSelected = $dateFrom->format('Y-m-d');
            } else {
                $dateFromSelected = $mondayOfWeek;
            }

            if ($sundayOfWeek <= $dateTo->format('Y-m-d')) {
                $dateToSelected = $sundayOfWeek;
            } else {
                $dateToSelected = $dateTo->format('Y-m-d');
            }


            $daysArray[$date]['from'] = \DateTime::createFromFormat('Y-m-d', $dateFromSelected)->setTime(0, 0, 0);
            $daysArray[$date]['to']   = \DateTime::createFromFormat('Y-m-d', $dateToSelected)->setTime(23, 59, 59);
        }

        return $daysArray;

    }


    /**
     * Array de meses entre dos fechas
     */
    public static function monthsBetween(\DateTime $dateFrom, \DateTime $dateTo)
    {

        $daysArray = [];


        for ($date = $dateFrom->format('Y-m-d'); $date < $dateTo->format('Y-m-d'); $date = date("Y-m-d", strtotime($date . "+ 1 months"))) {

            // primer día del mes
            $firstOfMonth = date("Y-m-d", strtotime('first day of this month ' . $date));
            // último dia del mes
            $lastOfMonth = date("Y-m-d", strtotime('last day of this month ' . $date));

            // Primera fecha
            if ($date == $dateFrom->format('Y-m-d')) {
                $dateFromSelected = $dateFrom->format('Y-m-d');
            } else {
                $dateFromSelected = $firstOfMonth;
            }

            if ($lastOfMonth <= $dateTo->format('Y-m-d')) {
                $dateToSelected = $lastOfMonth;
            } else {
                $dateToSelected = $dateTo->format('Y-m-d');
            }


            $daysArray[$date]['from'] = \DateTime::createFromFormat('Y-m-d', $dateFromSelected)->setTime(0, 0, 0);
            $daysArray[$date]['to']   = \DateTime::createFromFormat('Y-m-d', $dateToSelected)->setTime(23, 59, 59);
        }

        return $daysArray;

    }


    /**
     * Array de años entre dos fechas
     */
    public static function yearsBetween(\DateTime $dateFrom, \DateTime $dateTo)
    {

        $daysArray = [];


        for ($date = $dateFrom->format('Y-m-d'); $date < $dateTo->format('Y-m-d'); $date = date("Y-m-d", strtotime($date . "+ 1 years"))) {

            // primer día del mes
            $firstOfYear = date("Y-m-d", strtotime('first day of January ' . $date));
            // último dia del mes
            $lastOfYear = date("Y-m-d", strtotime('last day of December ' . $date));

            // Primera fecha
            if ($date == $dateFrom->format('Y-m-d')) {
                $dateFromSelected = $dateFrom->format('Y-m-d');
            } else {
                $dateFromSelected = $firstOfYear;
            }

            if ($lastOfYear <= $dateTo->format('Y-m-d')) {
                $dateToSelected = $lastOfYear;
            } else {
                $dateToSelected = $dateTo->format('Y-m-d');
            }


            $daysArray[$date]['from'] = \DateTime::createFromFormat('Y-m-d', $dateFromSelected)->setTime(0, 0, 0);
            $daysArray[$date]['to']   = \DateTime::createFromFormat('Y-m-d', $dateToSelected)->setTime(23, 59, 59);
        }

        return $daysArray;

    }

    /**
     * Calcula el intervalo de fechas según el modo que se necesite
     *
     * @param $dateFrom
     * @param $dateTo
     * @param $mode
     *
     * @return array
     */
    public static function getIntervalDates($dateFrom, $dateTo, $mode = "days")
    {
        switch ($mode) {
            case "days":
                $daysBetween = Util::daysBetween($dateFrom, $dateTo);
                break;
            case "weeks":
                $daysBetween = Util::weeksBetween($dateFrom, $dateTo);
                break;
            case "months":
                $daysBetween = Util::monthsBetween($dateFrom, $dateTo);
                break;
            case "years":
                $daysBetween = Util::yearsBetween($dateFrom, $dateTo);
                break;
            default:
                $daysBetween = Util::daysBetween($dateFrom, $dateTo);
                break;
        }

        return $daysBetween;
    }


    /**
     * recibe una imagen en base64 y devuelve un array con el nombre de la
     * imagen y los datos decodificados.
     */
    public static function base64_decode($base64Image)
    {

        if (!$base64Image) {
            return false;
        }

        try {

            list($type, $base64Image) = explode(';', $base64Image);
            list(, $base64Image) = explode(',', $base64Image);

            list(, $mimetype) = explode(':', $type);
            list(, $extension) = explode('/', $mimetype);

            $image = base64_decode($base64Image);

            return array(
                "extension" => $extension,
                "mimetype"  => $mimetype,
                "data"      => $image
            );

        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * Calcula la fecha de fin de Jornada
     *
     * @param $startDate
     * @param $matchDays
     *
     * @return \DateTime|boolean
     */
    public static function endMatchDayDate($startDate, array $matchDays)
    {

        $start = new \DateTime($startDate);

        for ($i = 0; $i < 100; $i++) {

            if ($start->format('w') != $matchDays[count($matchDays) - 1])
                $start = $start->add(new \DateInterval('P1D'));
            else
                return $start;

        }

        return false;

    }


    /**
     * Calcula la fecha de fin de Jornada
     *
     * @param $startDate
     * @param $matchDays
     *
     * @return \DateTime|boolean
     */
    public static function startMatchDayMatchDate($startDate, array $matchDays)
    {

        $start = new \DateTime($startDate);

        for ($i = 0; $i < 100; $i++) {

            if ($start->format('w') != $matchDays[0])
                $start = $start->add(new \DateInterval('P1D'));
            else
                return $start;

        }

        return false;

    }


    public function conversorSegundosHoras($tiempo_en_segundos)
    {
        $horas    = floor($tiempo_en_segundos / 3600);
        $minutos  = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
        $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

        return [
            "hours" => $horas,
            "min"   => $minutos,
            "sec"   => $segundos
        ];
    }

    /**
     * Cálculo de fechas estimadas:
     * Calcula la fecha estimada de un partido de la jornada teniendo en cuenta los partidos de la jornada
     *
     * @param           $totalMatchs
     * @param string $startDate
     * @param string $endDate
     * @param           $matchIndex
     *
     * @return \DateTime
     */
    public static function calculateEstimatedDate($totalMatchs, $startDate, $endDate, $matchIndex)
    {

        //var_dump($startDate);
        //var_dump($endDate);

        $startDate = new \DateTime($startDate);
        $endDate   = new \DateTime($endDate);

        $multiplier = $matchIndex;

        $hoursBetweenDates = ($endDate->getTimestamp() - $startDate->getTimestamp()) / 60 / 60;
        $hoursBetweenMatch = $hoursBetweenDates / $totalMatchs;

        $hoursToAdd = $hoursBetweenMatch * $multiplier;


        $startDate->add(new \DateInterval('PT' . (integer)$hoursToAdd . 'H'));
        return $startDate;
    }


    /**
     * Obtiene el objeto datetime de un string de fecha del tipo Y-m-d H:i:s - Y-m-d H:i:s
     *
     * @param $type
     * @param $string
     *      must be from|to
     *
     * @return bool|\DateTime
     */
    public static function getRangeDate($type, $string)
    {

        $extract = explode(" - ", $string);

        if (count($extract) != 2) return false;

        switch ($type) {
            case "from":
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $extract[0]);
                if (!$date) {
                    $date = \DateTime::createFromFormat('d/m/Y', $extract[0]);
                }
                return $date;
            case "to":
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $extract[1]);
                if (!$date) {
                    $date = \DateTime::createFromFormat('d/m/Y', $extract[1]);
                }
                return $date;
        }

    }


    /**
     * Calcula fecha siguiente en la que debe empezar la siguiente jornada. Ejemplo:
     *
     * 17 viernes - 19 domingo
     *
     * devuelve el siguiente día después del fin que sea igual al comienzo, en el caso anterior devolvería:
     * 24 viernes - 26 domingo
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array
     */
    public static function nextMatchDay(\DateTime $from, \DateTime $to)
    {
        $secondsBetween = $to->getTimestamp() - $from->getTimestamp();
        $fromDay        = $from->format('w');
        if ($secondsBetween < 0) return false;
        $startToNextMatchDay = $from;
        while ($startToNextMatchDay->format('w') != $fromDay or $startToNextMatchDay < $to) {
            $startToNextMatchDay->add(new \DateInterval('P1D'));
        }
        $nextFrom = \DateTime::createFromFormat('Y-m-d H:i:s', $startToNextMatchDay->format('Y-m-d H:i:s'));
        $nextTo   = self::addSecondsToDateTime($startToNextMatchDay, $secondsBetween);
        return [
            "from" => $nextFrom,
            "to"   => $nextTo
        ];
    }


    /**
     * Para un array de partidos de ronda, ubicamos los horarios de los partidos con la diferencia con respecto a la fecha de inicion de la jornada
     *
     * @param \DateTime $start_date
     * @param array $round_hours
     *
     * @return array
     */
    public static function getMatchHoursInterval(\DateTime $start_date, array $round_hours)
    {


        $dateTimestamp = $start_date->getTimestamp();
        $resultRounds  = [];

        foreach ($round_hours as $index => $round_hour) {
            $resultRounds[] = [
                "diff_start" => \DateTime::createFromFormat('Y-m-d H:i:s', $round_hour['start_date'])->getTimestamp() - $dateTimestamp,
                "diff_end"   => @$round_hour['end_date'] ? \DateTime::createFromFormat('Y-m-d H:i:s', $round_hour['end_date'])->getTimestamp() - $dateTimestamp : null,
            ];
        }

        return $resultRounds;

    }


    /**
     * Añade segundos a una fecha determinada.
     *
     * @param \DateTime $startDate
     * @param           $seconds
     *
     * @return mixed
     * @throws \Exception
     */
    public static function addSecondsToDateTime(\DateTime $startDate, $seconds)
    {

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $startDate->format('Y-m-d H:i:s'));
        return $date->add(new \DateInterval('PT' . $seconds . 'S'));
    }


    /**
     * Genera un array random para extraer las rondas de un torneo.
     *
     * @param $seed
     *
     * @return array
     */
    public static function generateTeamsArray($seed)
    {

        $teams = [];
        for ($i = 1; $i < $seed; $i++) {
            $teams[] = "team" . $i;
        }
        $tournament  = new Tournament($seed, $teams, 10);
        $knockout    = $tournament->getKnokout();
        $matchRounds = $knockout->roundsInfo;

        return $matchRounds;
    }


    /**
     * recupera los años de diferencia con respecto a hoy para comprobar la edad el usuario
     */
    public static function getYearsOld(\DateTime $date)
    {
        $cumpleanos = $date;
        $hoy        = new \DateTime();
        $annos      = $hoy->diff($cumpleanos);
        return $annos->y;
    }


    public static function getPageFirstResult($page, $limitResult)
    {

        $page        = $page <= 0 ? 1 : $page;
        $firstResult = $page == 1 ? 0 : ($page - 1) * $limitResult;

        return $firstResult;
    }

    /**
     * Ordena un array multi-nivel en funcion de los parámetros indicados
     * @param $array
     * @param $on
     * @param int $order
     * @return array
     *
     * Ejemplo: array_sort($array, "age", SORT_ASC)
     */
    public static function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array      = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    /**
     * Recupera las fechas a partir de un literal,
     * today -> devuelve la fecha de hoy en from con la hora 00:00 y en to con la hora 23:59
     * this_week -> devuelve la fecha del lunes de la semana al igual que toda con los tiempos agregados
     * this_month -> devuelve el primero y el último día del mes
     * last_month -> devuelve el primer día y el último del mes anterior al actual.
     */
    public static function getDateFromLiteral($rangeSelected = 'today', $type = 'from')
    {
        $from = (new \DateTime('now'))->setTime(0, 0, 0);
        $to   = (new \DateTime('now'))->setTime(23, 59, 59);
        if ($rangeSelected === 'this_month') {
            $from = (new \DateTime('first day of this month'))->setTime(0, 0, 0);
            $to   = (new \DateTime('last day of this month'))->setTime(23, 59, 59);
        } elseif ($rangeSelected === 'last_month') {
            $from = (new \DateTime('first day of last month'))->setTime(0, 0, 0);
            $to   = (new \DateTime('last day of last month'))->setTime(23, 59, 59);
        } elseif ($rangeSelected === 'this_week') {
            $from = (new \DateTime('monday this week'))->setTime(0, 0, 0);
            $to   = (new \DateTime('sunday this week'))->setTime(23, 59, 59);
        }

        if ($type === 'from') return $from;
        if ($type === 'to') return $to;
    }


    /**
     * Retorna un formato al que darle a un objeto datetime dependiendo de el modo en el que se quiera representar.
     * Si el modo es:
     *      day -> muestra 29-08 -> día y mes
     *      week -> muestra 56-09 ->semana y mes
     *      month -> muestra el mes en string Enero, Febrero, etc. con letras cortas
     * @param $mode
     * @return string
     */
    public static function getDateFormatByMode($mode)
    {

        switch ($mode) {
            case "days":
                return "d-m";
            case "weeks":
                return "W-d";
            case "months":
                return "M";
            case "years":
                return "Y";
        }

    }


    /**
     * Recupera las fechas a partir de la opción seleccionada por el usuario,
     * es util para calcular el ratio de conversión de los agentes.
     *
     * @param $option
     * @param null $dates
     * @param string $recovery
     * @return \DateTime|null
     * @throws \Exception
     */
    public static function getDatesFromOption($option, $dates = null, $recovery = "from")
    {
        $firstDay = null;
        $lastDay  = null;

        // Dependiendo de la opción seleccionada, mostrará los resultados por mes, semana o día
        if ($option == "month" || $option == "monthly") {
            $firstDay = (new \DateTime('first day of this month'))->setTime(0, 0, 0);
            $lastDay  = (new \DateTime('now'))->setTime(23, 59, 59);
        }

        if ($option == "week" || $option == "weekly") {
            $firstDay = (new \DateTime('monday this week'))->setTime(0, 0, 0);
            $lastDay  = (new \DateTime('now'))->setTime(23, 59, 59);
        }

        if ($option == "today" || $option == "daily") {
            $firstDay = (new \DateTime('now'))->setTime(0, 0, 0);
            $lastDay  = (new \DateTime('now'))->setTime(23, 59, 59);
        }

        if ($option == "custom") {
            $firstDay = (Util::getRangeDate('from', $dates))->setTime(0, 0, 0);
            $lastDay  = (Util::getRangeDate('to', $dates))->setTime(23, 59, 59);
        }

        return $recovery == "from" ? $firstDay : $lastDay;
    }


    public static function isMobile(string $telephone): bool
    {
        return preg_match("/^(\+34|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}$/", $telephone);

    }

    public static function sanitizeTelephone(?string $unformattedPhone): ?string
    {
        if(!$unformattedPhone)
            return null;

        $withoutSymbols = Util::sanitize_string($unformattedPhone);
        $withoutCountryCode = preg_replace("/^(whatsapp)?(\+34|0034|34)?/i", "", $withoutSymbols);

        return $withoutCountryCode;
    }


    /**
     * Detect if string has emojis.
    */
    public static function has_emojis( $string ): bool {

        // Array of emoji (v12, 2019) unicodes from https://unicode.org/emoji/charts/emoji-list.html

        $unicodes = array( '1F600','1F603','1F604','1F601','1F606','1F605','1F923','1F602','1F642','1F643','1F609','1F60A','1F607','1F970','1F60D','1F929','1F618','1F617','263A','1F61A','1F619','1F60B','1F61B','1F61C','1F92A','1F61D','1F911','1F917','1F92D','1F92B','1F914','1F910','1F928','1F610','1F611','1F636','1F60F','1F612','1F644','1F62C','1F925','1F60C','1F614','1F62A','1F924','1F634','1F637','1F912','1F915','1F922','1F92E','1F927','1F975','1F976','1F974','1F635','1F92F','1F920','1F973','1F60E','1F913','1F9D0','1F615','1F61F','1F641','2639','1F62E','1F62F','1F632','1F633','1F97A','1F626','1F627','1F628','1F630','1F625','1F622','1F62D','1F631','1F616','1F623','1F61E','1F613','1F629','1F62B','1F971','1F624','1F621','1F620','1F92C','1F608','1F47F','1F480','2620','1F4A9','1F921','1F479','1F47A','1F47B','1F47D','1F47E','1F916','1F63A','1F638','1F639','1F63B','1F63C','1F63D','1F640','1F63F','1F63E','1F648','1F649','1F64A','1F48B','1F48C','1F498','1F49D','1F496','1F497','1F493','1F49E','1F495','1F49F','2763','1F494','2764','1F9E1','1F49B','1F49A','1F499','1F49C','1F90E','1F5A4','1F90D','1F4AF','1F4A2','1F4A5','1F4AB','1F4A6','1F4A8','1F573','1F4A3','1F4AC','1F441','FE0F','200D','1F5E8','FE0F','1F5E8','1F5EF','1F4AD','1F4A4','1F44B','1F91A','1F590','270B','1F596','1F44C','1F90F','270C','1F91E','1F91F','1F918','1F919','1F448','1F449','1F446','1F595','1F447','261D','1F44D','1F44E','270A','1F44A','1F91B','1F91C','1F44F','1F64C','1F450','1F932','1F91D','1F64F','270D','1F485','1F933','1F4AA','1F9BE','1F9BF','1F9B5','1F9B6','1F442','1F9BB','1F443','1F9E0','1F9B7','1F9B4','1F440','1F441','1F445','1F444','1F476','1F9D2','1F466','1F467','1F9D1','1F471','1F468','1F9D4','1F471','200D','2642','FE0F','1F468','200D','1F9B0','1F468','200D','1F9B1','1F468','200D','1F9B3','1F468','200D','1F9B2','1F469','1F471','200D','2640','FE0F','1F469','200D','1F9B0','1F469','200D','1F9B1','1F469','200D','1F9B3','1F469','200D','1F9B2','1F9D3','1F474','1F475','1F64D','1F64D','200D','2642','FE0F','1F64D','200D','2640','FE0F','1F64E','1F64E','200D','2642','FE0F','1F64E','200D','2640','FE0F','1F645','1F645','200D','2642','FE0F','1F645','200D','2640','FE0F','1F646','1F646','200D','2642','FE0F','1F646','200D','2640','FE0F','1F481','1F481','200D','2642','FE0F','1F481','200D','2640','FE0F','1F64B','1F64B','200D','2642','FE0F','1F64B','200D','2640','FE0F','1F9CF','1F9CF','200D','2642','FE0F','1F9CF','200D','2640','FE0F','1F647','1F647','200D','2642','FE0F','1F647','200D','2640','FE0F','1F926','1F926','200D','2642','FE0F','1F926','200D','2640','FE0F','1F937','1F937','200D','2642','FE0F','1F937','200D','2640','FE0F','1F468','200D','2695','FE0F','1F469','200D','2695','FE0F','1F468','200D','1F393','1F469','200D','1F393','1F468','200D','1F3EB','1F469','200D','1F3EB','1F468','200D','2696','FE0F','1F469','200D','2696','FE0F','1F468','200D','1F33E','1F469','200D','1F33E','1F468','200D','1F373','1F469','200D','1F373','1F468','200D','1F527','1F469','200D','1F527','1F468','200D','1F3ED','1F469','200D','1F3ED','1F468','200D','1F4BC','1F469','200D','1F4BC','1F468','200D','1F52C','1F469','200D','1F52C','1F468','200D','1F4BB','1F469','200D','1F4BB','1F468','200D','1F3A4','1F469','200D','1F3A4','1F468','200D','1F3A8','1F469','200D','1F3A8','1F468','200D','2708','FE0F','1F469','200D','2708','FE0F','1F468','200D','1F680','1F469','200D','1F680','1F468','200D','1F692','1F469','200D','1F692','1F46E','1F46E','200D','2642','FE0F','1F46E','200D','2640','FE0F','1F575','1F575','FE0F','200D','2642','FE0F','1F575','FE0F','200D','2640','FE0F','1F482','1F482','200D','2642','FE0F','1F482','200D','2640','FE0F','1F477','1F477','200D','2642','FE0F','1F477','200D','2640','FE0F','1F934','1F478','1F473','1F473','200D','2642','FE0F','1F473','200D','2640','FE0F','1F472','1F9D5','1F935','1F470','1F930','1F931','1F47C','1F385','1F936','1F9B8','1F9B8','200D','2642','FE0F','1F9B8','200D','2640','FE0F','1F9B9','1F9B9','200D','2642','FE0F','1F9B9','200D','2640','FE0F','1F9D9','1F9D9','200D','2642','FE0F','1F9D9','200D','2640','FE0F','1F9DA','1F9DA','200D','2642','FE0F','1F9DA','200D','2640','FE0F','1F9DB','1F9DB','200D','2642','FE0F','1F9DB','200D','2640','FE0F','1F9DC','1F9DC','200D','2642','FE0F','1F9DC','200D','2640','FE0F','1F9DD','1F9DD','200D','2642','FE0F','1F9DD','200D','2640','FE0F','1F9DE','1F9DE','200D','2642','FE0F','1F9DE','200D','2640','FE0F','1F9DF','1F9DF','200D','2642','FE0F','1F9DF','200D','2640','FE0F','1F486','1F486','200D','2642','FE0F','1F486','200D','2640','FE0F','1F487','1F487','200D','2642','FE0F','1F487','200D','2640','FE0F','1F6B6','1F6B6','200D','2642','FE0F','1F6B6','200D','2640','FE0F','1F9CD','1F9CD','200D','2642','FE0F','1F9CD','200D','2640','FE0F','1F9CE','1F9CE','200D','2642','FE0F','1F9CE','200D','2640','FE0F','1F468','200D','1F9AF','1F469','200D','1F9AF','1F468','200D','1F9BC','1F469','200D','1F9BC','1F468','200D','1F9BD','1F469','200D','1F9BD','1F3C3','1F3C3','200D','2642','FE0F','1F3C3','200D','2640','FE0F','1F483','1F57A','1F574','1F46F','1F46F','200D','2642','FE0F','1F46F','200D','2640','FE0F','1F9D6','1F9D6','200D','2642','FE0F','1F9D6','200D','2640','FE0F','1F9D7','1F9D7','200D','2642','FE0F','1F9D7','200D','2640','FE0F','1F93A','1F3C7','26F7','1F3C2','1F3CC','1F3CC','FE0F','200D','2642','FE0F','1F3CC','FE0F','200D','2640','FE0F','1F3C4','1F3C4','200D','2642','FE0F','1F3C4','200D','2640','FE0F','1F6A3','1F6A3','200D','2642','FE0F','1F6A3','200D','2640','FE0F','1F3CA','1F3CA','200D','2642','FE0F','1F3CA','200D','2640','FE0F','26F9','26F9','FE0F','200D','2642','FE0F','26F9','FE0F','200D','2640','FE0F','1F3CB','1F3CB','FE0F','200D','2642','FE0F','1F3CB','FE0F','200D','2640','FE0F','1F6B4','1F6B4','200D','2642','FE0F','1F6B4','200D','2640','FE0F','1F6B5','1F6B5','200D','2642','FE0F','1F6B5','200D','2640','FE0F','1F938','1F938','200D','2642','FE0F','1F938','200D','2640','FE0F','1F93C','1F93C','200D','2642','FE0F','1F93C','200D','2640','FE0F','1F93D','1F93D','200D','2642','FE0F','1F93D','200D','2640','FE0F','1F93E','1F93E','200D','2642','FE0F','1F93E','200D','2640','FE0F','1F939','1F939','200D','2642','FE0F','1F939','200D','2640','FE0F','1F9D8','1F9D8','200D','2642','FE0F','1F9D8','200D','2640','FE0F','1F6C0','1F6CC','1F9D1','200D','1F91D','200D','1F9D1','1F46D','1F46B','1F46C','1F48F','1F469','200D','2764','FE0F','200D','1F48B','200D','1F468','1F468','200D','2764','FE0F','200D','1F48B','200D','1F468','1F469','200D','2764','FE0F','200D','1F48B','200D','1F469','1F491','1F469','200D','2764','FE0F','200D','1F468','1F468','200D','2764','FE0F','200D','1F468','1F469','200D','2764','FE0F','200D','1F469','1F46A','1F468','200D','1F469','200D','1F466','1F468','200D','1F469','200D','1F467','1F468','200D','1F469','200D','1F467','200D','1F466','1F468','200D','1F469','200D','1F466','200D','1F466','1F468','200D','1F469','200D','1F467','200D','1F467','1F468','200D','1F468','200D','1F466','1F468','200D','1F468','200D','1F467','1F468','200D','1F468','200D','1F467','200D','1F466','1F468','200D','1F468','200D','1F466','200D','1F466','1F468','200D','1F468','200D','1F467','200D','1F467','1F469','200D','1F469','200D','1F466','1F469','200D','1F469','200D','1F467','1F469','200D','1F469','200D','1F467','200D','1F466','1F469','200D','1F469','200D','1F466','200D','1F466','1F469','200D','1F469','200D','1F467','200D','1F467','1F468','200D','1F466','1F468','200D','1F466','200D','1F466','1F468','200D','1F467','1F468','200D','1F467','200D','1F466','1F468','200D','1F467','200D','1F467','1F469','200D','1F466','1F469','200D','1F466','200D','1F466','1F469','200D','1F467','1F469','200D','1F467','200D','1F466','1F469','200D','1F467','200D','1F467','1F5E3','1F464','1F465','1F463','1F9B0','1F9B1','1F9B3','1F9B2','1F435','1F412','1F98D','1F9A7','1F436','1F415','1F9AE','1F415','200D','1F9BA','1F429','1F43A','1F98A','1F99D','1F431','1F408','1F981','1F42F','1F405','1F406','1F434','1F40E','1F984','1F993','1F98C','1F42E','1F402','1F403','1F404','1F437','1F416','1F417','1F43D','1F40F','1F411','1F410','1F42A','1F42B','1F999','1F992','1F418','1F98F','1F99B','1F42D','1F401','1F400','1F439','1F430','1F407','1F43F','1F994','1F987','1F43B','1F428','1F43C','1F9A5','1F9A6','1F9A8','1F998','1F9A1','1F43E','1F983','1F414','1F413','1F423','1F424','1F425','1F426','1F427','1F54A','1F985','1F986','1F9A2','1F989','1F9A9','1F99A','1F99C','1F438','1F40A','1F422','1F98E','1F40D','1F432','1F409','1F995','1F996','1F433','1F40B','1F42C','1F41F','1F420','1F421','1F988','1F419','1F41A','1F40C','1F98B','1F41B','1F41C','1F41D','1F41E','1F997','1F577','1F578','1F982','1F99F','1F9A0','1F490','1F338','1F4AE','1F3F5','1F339','1F940','1F33A','1F33B','1F33C','1F337','1F331','1F332','1F333','1F334','1F335','1F33E','1F33F','2618','1F340','1F341','1F342','1F343','1F347','1F348','1F349','1F34A','1F34B','1F34C','1F34D','1F96D','1F34E','1F34F','1F350','1F351','1F352','1F353','1F95D','1F345','1F965','1F951','1F346','1F954','1F955','1F33D','1F336','1F952','1F96C','1F966','1F9C4','1F9C5','1F344','1F95C','1F330','1F35E','1F950','1F956','1F968','1F96F','1F95E','1F9C7','1F9C0','1F356','1F357','1F969','1F953','1F354','1F35F','1F355','1F32D','1F96A','1F32E','1F32F','1F959','1F9C6','1F95A','1F373','1F958','1F372','1F963','1F957','1F37F','1F9C8','1F9C2','1F96B','1F371','1F358','1F359','1F35A','1F35B','1F35C','1F35D','1F360','1F362','1F363','1F364','1F365','1F96E','1F361','1F95F','1F960','1F961','1F980','1F99E','1F990','1F991','1F9AA','1F366','1F367','1F368','1F369','1F36A','1F382','1F370','1F9C1','1F967','1F36B','1F36C','1F36D','1F36E','1F36F','1F37C','1F95B','2615','1F375','1F376','1F37E','1F377','1F378','1F379','1F37A','1F37B','1F942','1F943','1F964','1F9C3','1F9C9','1F9CA','1F962','1F37D','1F374','1F944','1F52A','1F3FA','1F30D','1F30E','1F30F','1F310','1F5FA','1F5FE','1F9ED','1F3D4','26F0','1F30B','1F5FB','1F3D5','1F3D6','1F3DC','1F3DD','1F3DE','1F3DF','1F3DB','1F3D7','1F9F1','1F3D8','1F3DA','1F3E0','1F3E1','1F3E2','1F3E3','1F3E4','1F3E5','1F3E6','1F3E8','1F3E9','1F3EA','1F3EB','1F3EC','1F3ED','1F3EF','1F3F0','1F492','1F5FC','1F5FD','26EA','1F54C','1F6D5','1F54D','26E9','1F54B','26F2','26FA','1F301','1F303','1F3D9','1F304','1F305','1F306','1F307','1F309','2668','1F3A0','1F3A1','1F3A2','1F488','1F3AA','1F682','1F683','1F684','1F685','1F686','1F687','1F688','1F689','1F68A','1F69D','1F69E','1F68B','1F68C','1F68D','1F68E','1F690','1F691','1F692','1F693','1F694','1F695','1F696','1F697','1F698','1F699','1F69A','1F69B','1F69C','1F3CE','1F3CD','1F6F5','1F9BD','1F9BC','1F6FA','1F6B2','1F6F4','1F6F9','1F68F','1F6E3','1F6E4','1F6E2','26FD','1F6A8','1F6A5','1F6A6','1F6D1','1F6A7','2693','26F5','1F6F6','1F6A4','1F6F3','26F4','1F6E5','1F6A2','2708','1F6E9','1F6EB','1F6EC','1FA82','1F4BA','1F681','1F69F','1F6A0','1F6A1','1F6F0','1F680','1F6F8','1F6CE','1F9F3','231B','23F3','231A','23F0','23F1','23F2','1F570','1F55B','1F567','1F550','1F55C','1F551','1F55D','1F552','1F55E','1F553','1F55F','1F554','1F560','1F555','1F561','1F556','1F562','1F557','1F563','1F558','1F564','1F559','1F565','1F55A','1F566','1F311','1F312','1F313','1F314','1F315','1F316','1F317','1F318','1F319','1F31A','1F31B','1F31C','1F321','2600','1F31D','1F31E','1FA90','2B50','1F31F','1F320','1F30C','2601','26C5','26C8','1F324','1F325','1F326','1F327','1F328','1F329','1F32A','1F32B','1F32C','1F300','1F308','1F302','2602','2614','26F1','26A1','2744','2603','26C4','2604','1F525','1F4A7','1F30A','1F383','1F384','1F386','1F387','1F9E8','2728','1F388','1F389','1F38A','1F38B','1F38D','1F38E','1F38F','1F390','1F391','1F9E7','1F380','1F381','1F397','1F39F','1F3AB','1F396','1F3C6','1F3C5','1F947','1F948','1F949','26BD','26BE','1F94E','1F3C0','1F3D0','1F3C8','1F3C9','1F3BE','1F94F','1F3B3','1F3CF','1F3D1','1F3D2','1F94D','1F3D3','1F3F8','1F94A','1F94B','1F945','26F3','26F8','1F3A3','1F93F','1F3BD','1F3BF','1F6F7','1F94C','1F3AF','1FA80','1FA81','1F3B1','1F52E','1F9FF','1F3AE','1F579','1F3B0','1F3B2','1F9E9','1F9F8','2660','2665','2666','2663','265F','1F0CF','1F004','1F3B4','1F3AD','1F5BC','1F3A8','1F9F5','1F9F6','1F453','1F576','1F97D','1F97C','1F9BA','1F454','1F455','1F456','1F9E3','1F9E4','1F9E5','1F9E6','1F457','1F458','1F97B','1FA71','1FA72','1FA73','1F459','1F45A','1F45B','1F45C','1F45D','1F6CD','1F392','1F45E','1F45F','1F97E','1F97F','1F460','1F461','1FA70','1F462','1F451','1F452','1F3A9','1F393','1F9E2','26D1','1F4FF','1F484','1F48D','1F48E','1F507','1F508','1F509','1F50A','1F4E2','1F4E3','1F4EF','1F514','1F515','1F3BC','1F3B5','1F3B6','1F399','1F39A','1F39B','1F3A4','1F3A7','1F4FB','1F3B7','1F3B8','1F3B9','1F3BA','1F3BB','1FA95','1F941','1F4F1','1F4F2','260E','1F4DE','1F4DF','1F4E0','1F50B','1F50C','1F4BB','1F5A5','1F5A8','2328','1F5B1','1F5B2','1F4BD','1F4BE','1F4BF','1F4C0','1F9EE','1F3A5','1F39E','1F4FD','1F3AC','1F4FA','1F4F7','1F4F8','1F4F9','1F4FC','1F50D','1F50E','1F56F','1F4A1','1F526','1F3EE','1FA94','1F4D4','1F4D5','1F4D6','1F4D7','1F4D8','1F4D9','1F4DA','1F4D3','1F4D2','1F4C3','1F4DC','1F4C4','1F4F0','1F5DE','1F4D1','1F516','1F3F7','1F4B0','1F4B4','1F4B5','1F4B6','1F4B7','1F4B8','1F4B3','1F9FE','1F4B9','1F4B1','1F4B2','2709','1F4E7','1F4E8','1F4E9','1F4E4','1F4E5','1F4E6','1F4EB','1F4EA','1F4EC','1F4ED','1F4EE','1F5F3','270F','2712','1F58B','1F58A','1F58C','1F58D','1F4DD','1F4BC','1F4C1','1F4C2','1F5C2','1F4C5','1F4C6','1F5D2','1F5D3','1F4C7','1F4C8','1F4C9','1F4CA','1F4CB','1F4CC','1F4CD','1F4CE','1F587','1F4CF','1F4D0','2702','1F5C3','1F5C4','1F5D1','1F512','1F513','1F50F','1F510','1F511','1F5DD','1F528','1FA93','26CF','2692','1F6E0','1F5E1','2694','1F52B','1F3F9','1F6E1','1F527','1F529','2699','1F5DC','2696','1F9AF','1F517','26D3','1F9F0','1F9F2','2697','1F9EA','1F9EB','1F9EC','1F52C','1F52D','1F4E1','1F489','1FA78','1F48A','1FA79','1FA7A','1F6AA','1F6CF','1F6CB','1FA91','1F6BD','1F6BF','1F6C1','1FA92','1F9F4','1F9F7','1F9F9','1F9FA','1F9FB','1F9FC','1F9FD','1F9EF','1F6D2','1F6AC','26B0','26B1','1F5FF','1F3E7','1F6AE','1F6B0','267F','1F6B9','1F6BA','1F6BB','1F6BC','1F6BE','1F6C2','1F6C3','1F6C4','1F6C5','26A0','1F6B8','26D4','1F6AB','1F6B3','1F6AD','1F6AF','1F6B1','1F6B7','1F4F5','1F51E','2622','2623','2B06','2197','27A1','2198','2B07','2199','2B05','2196','2195','2194','21A9','21AA','2934','2935','1F503','1F504','1F519','1F51A','1F51B','1F51C','1F51D','1F6D0','269B','1F549','2721','2638','262F','271D','2626','262A','262E','1F54E','1F52F','2648','2649','264A','264B','264C','264D','264E','264F','2650','2651','2652','2653','26CE','1F500','1F501','1F502','25B6','23E9','23ED','23EF','25C0','23EA','23EE','1F53C','23EB','1F53D','23EC','23F8','23F9','23FA','23CF','1F3A6','1F505','1F506','1F4F6','1F4F3','1F4F4','2640','2642','2695','267E','267B','269C','1F531','1F4DB','1F530','2B55','2705','2611','2714','2716','274C','274E','2795','2796','2797','27B0','27BF','303D','2733','2734','2747','203C','2049','2753','2754','2755','2757','3030','00A9','00AE','2122','0023','FE0F','20E3','002A','FE0F','20E3','0030','FE0F','20E3','0031','FE0F','20E3','0032','FE0F','20E3','0033','FE0F','20E3','0034','FE0F','20E3','0035','FE0F','20E3','0036','FE0F','20E3','0037','FE0F','20E3','0038','FE0F','20E3','0039','FE0F','20E3','1F51F','1F520','1F521','1F522','1F523','1F524','1F170','1F18E','1F171','1F191','1F192','1F193','2139','1F194','24C2','1F195','1F196','1F17E','1F197','1F17F','1F198','1F199','1F19A','1F201','1F202','1F237','1F236','1F22F','1F250','1F239','1F21A','1F232','1F251','1F238','1F234','1F233','3297','3299','1F23A','1F235','1F534','1F7E0','1F7E1','1F7E2','1F535','1F7E3','1F7E4','26AB','26AA','1F7E5','1F7E7','1F7E8','1F7E9','1F7E6','1F7EA','1F7EB','2B1B','2B1C','25FC','25FB','25FE','25FD','25AA','25AB','1F536','1F537','1F538','1F539','1F53A','1F53B','1F4A0','1F518','1F533','1F532','1F3C1','1F6A9','1F38C','1F3F4','1F3F3','1F3F3','FE0F','200D','1F308','1F3F4','200D','2620','FE0F','1F1E6','1F1E8','1F1E6','1F1E9','1F1E6','1F1EA','1F1E6','1F1EB','1F1E6','1F1EC','1F1E6','1F1EE','1F1E6','1F1F1','1F1E6','1F1F2','1F1E6','1F1F4','1F1E6','1F1F6','1F1E6','1F1F7','1F1E6','1F1F8','1F1E6','1F1F9','1F1E6','1F1FA','1F1E6','1F1FC','1F1E6','1F1FD','1F1E6','1F1FF','1F1E7','1F1E6','1F1E7','1F1E7','1F1E7','1F1E9','1F1E7','1F1EA','1F1E7','1F1EB','1F1E7','1F1EC','1F1E7','1F1ED','1F1E7','1F1EE','1F1E7','1F1EF','1F1E7','1F1F1','1F1E7','1F1F2','1F1E7','1F1F3','1F1E7','1F1F4','1F1E7','1F1F6','1F1E7','1F1F7','1F1E7','1F1F8','1F1E7','1F1F9','1F1E7','1F1FB','1F1E7','1F1FC','1F1E7','1F1FE','1F1E7','1F1FF','1F1E8','1F1E6','1F1E8','1F1E8','1F1E8','1F1E9','1F1E8','1F1EB','1F1E8','1F1EC','1F1E8','1F1ED','1F1E8','1F1EE','1F1E8','1F1F0','1F1E8','1F1F1','1F1E8','1F1F2','1F1E8','1F1F3','1F1E8','1F1F4','1F1E8','1F1F5','1F1E8','1F1F7','1F1E8','1F1FA','1F1E8','1F1FB','1F1E8','1F1FC','1F1E8','1F1FD','1F1E8','1F1FE','1F1E8','1F1FF','1F1E9','1F1EA','1F1E9','1F1EC','1F1E9','1F1EF','1F1E9','1F1F0','1F1E9','1F1F2','1F1E9','1F1F4','1F1E9','1F1FF','1F1EA','1F1E6','1F1EA','1F1E8','1F1EA','1F1EA','1F1EA','1F1EC','1F1EA','1F1ED','1F1EA','1F1F7','1F1EA','1F1F8','1F1EA','1F1F9','1F1EA','1F1FA','1F1EB','1F1EE','1F1EB','1F1EF','1F1EB','1F1F0','1F1EB','1F1F2','1F1EB','1F1F4','1F1EB','1F1F7','1F1EC','1F1E6','1F1EC','1F1E7','1F1EC','1F1E9','1F1EC','1F1EA','1F1EC','1F1EB','1F1EC','1F1EC','1F1EC','1F1ED','1F1EC','1F1EE','1F1EC','1F1F1','1F1EC','1F1F2','1F1EC','1F1F3','1F1EC','1F1F5','1F1EC','1F1F6','1F1EC','1F1F7','1F1EC','1F1F8','1F1EC','1F1F9','1F1EC','1F1FA','1F1EC','1F1FC','1F1EC','1F1FE','1F1ED','1F1F0','1F1ED','1F1F2','1F1ED','1F1F3','1F1ED','1F1F7','1F1ED','1F1F9','1F1ED','1F1FA','1F1EE','1F1E8','1F1EE','1F1E9','1F1EE','1F1EA','1F1EE','1F1F1','1F1EE','1F1F2','1F1EE','1F1F3','1F1EE','1F1F4','1F1EE','1F1F6','1F1EE','1F1F7','1F1EE','1F1F8','1F1EE','1F1F9','1F1EF','1F1EA','1F1EF','1F1F2','1F1EF','1F1F4','1F1EF','1F1F5','1F1F0','1F1EA','1F1F0','1F1EC','1F1F0','1F1ED','1F1F0','1F1EE','1F1F0','1F1F2','1F1F0','1F1F3','1F1F0','1F1F5','1F1F0','1F1F7','1F1F0','1F1FC','1F1F0','1F1FE','1F1F0','1F1FF','1F1F1','1F1E6','1F1F1','1F1E7','1F1F1','1F1E8','1F1F1','1F1EE','1F1F1','1F1F0','1F1F1','1F1F7','1F1F1','1F1F8','1F1F1','1F1F9','1F1F1','1F1FA','1F1F1','1F1FB','1F1F1','1F1FE','1F1F2','1F1E6','1F1F2','1F1E8','1F1F2','1F1E9','1F1F2','1F1EA','1F1F2','1F1EB','1F1F2','1F1EC','1F1F2','1F1ED','1F1F2','1F1F0','1F1F2','1F1F1','1F1F2','1F1F2','1F1F2','1F1F3','1F1F2','1F1F4','1F1F2','1F1F5','1F1F2','1F1F6','1F1F2','1F1F7','1F1F2','1F1F8','1F1F2','1F1F9','1F1F2','1F1FA','1F1F2','1F1FB','1F1F2','1F1FC','1F1F2','1F1FD','1F1F2','1F1FE','1F1F2','1F1FF','1F1F3','1F1E6','1F1F3','1F1E8','1F1F3','1F1EA','1F1F3','1F1EB','1F1F3','1F1EC','1F1F3','1F1EE','1F1F3','1F1F1','1F1F3','1F1F4','1F1F3','1F1F5','1F1F3','1F1F7','1F1F3','1F1FA','1F1F3','1F1FF','1F1F4','1F1F2','1F1F5','1F1E6','1F1F5','1F1EA','1F1F5','1F1EB','1F1F5','1F1EC','1F1F5','1F1ED','1F1F5','1F1F0','1F1F5','1F1F1','1F1F5','1F1F2','1F1F5','1F1F3','1F1F5','1F1F7','1F1F5','1F1F8','1F1F5','1F1F9','1F1F5','1F1FC','1F1F5','1F1FE','1F1F6','1F1E6','1F1F7','1F1EA','1F1F7','1F1F4','1F1F7','1F1F8','1F1F7','1F1FA','1F1F7','1F1FC','1F1F8','1F1E6','1F1F8','1F1E7','1F1F8','1F1E8','1F1F8','1F1E9','1F1F8','1F1EA','1F1F8','1F1EC','1F1F8','1F1ED','1F1F8','1F1EE','1F1F8','1F1EF','1F1F8','1F1F0','1F1F8','1F1F1','1F1F8','1F1F2','1F1F8','1F1F3','1F1F8','1F1F4','1F1F8','1F1F7','1F1F8','1F1F8','1F1F8','1F1F9','1F1F8','1F1FB','1F1F8','1F1FD','1F1F8','1F1FE','1F1F8','1F1FF','1F1F9','1F1E6','1F1F9','1F1E8','1F1F9','1F1E9','1F1F9','1F1EB','1F1F9','1F1EC','1F1F9','1F1ED','1F1F9','1F1EF','1F1F9','1F1F0','1F1F9','1F1F1','1F1F9','1F1F2','1F1F9','1F1F3','1F1F9','1F1F4','1F1F9','1F1F7','1F1F9','1F1F9','1F1F9','1F1FB','1F1F9','1F1FC','1F1F9','1F1FF','1F1FA','1F1E6','1F1FA','1F1EC','1F1FA','1F1F2','1F1FA','1F1F3','1F1FA','1F1F8','1F1FA','1F1FE','1F1FA','1F1FF','1F1FB','1F1E6','1F1FB','1F1E8','1F1FB','1F1EA','1F1FB','1F1EC','1F1FB','1F1EE','1F1FB','1F1F3','1F1FB','1F1FA','1F1FC','1F1EB','1F1FC','1F1F8','1F1FD','1F1F0','1F1FE','1F1EA','1F1FE','1F1F9','1F1FF','1F1E6','1F1FF','1F1F2','1F1FF','1F1FC','1F3F4','E0067','E0062','E0065','E006E','E0067','E007F','1F3F4','E0067','E0062','E0073','E0063','E0074','E007F','1F3F4','E0067','E0062','E0077','E006C','E0073','E007F' );

        return preg_match( '/[\x{' . implode( '}\x{', $unicodes ) . '}]/u', $string ) ? true : false;

    }


    public static function getCifSum($cif) {
        $sum = $cif[2] + $cif[4] + $cif[6];

        for ($i = 1; $i<8; $i += 2) {
            $tmp = (string) (2 * $cif[$i]);

            $tmp = $tmp[0] + ((strlen ($tmp) == 2) ?  $tmp[1] : 0);

            $sum += $tmp;
        }

        return $sum;
    }

    public static function validateNif($nif) {
        $nif_codes = 'TRWAGMYFPDXBNJZSQVHLCKE';

        $sum = (string) self::getCifSum($nif);
        $n = 10 - substr($sum, -1);

        if (preg_match ('/^[0-9]{8}[A-Z]{1}$/', $nif)) {
            // DNIs
            $num = substr($nif, 0, 8);

            return ($nif[8] == $nif_codes[$num % 23]);
        } elseif (preg_match ('/^[XYZ][0-9]{7}[A-Z]{1}$/', $nif)) {
            // NIEs normales
            $tmp = substr ($nif, 1, 7);
            $tmp = strtr(substr ($nif, 0, 1), 'XYZ', '012') . $tmp;

            return ($nif[8] == $nif_codes[$tmp % 23]);
        } elseif (preg_match ('/^[KLM]{1}/', $nif)) {
            // NIFs especiales
            return ($nif[8] == chr($n + 64));
        } elseif (preg_match ('/^[T]{1}[A-Z0-9]{8}$/', $nif)) {
            // NIE extraño
            return true;
        }

        return false;
    }

    /**
     * Obtiene el dia de hoy, pero del mes seleccionado como parámetro.
     *
     * @param string $month
     * @return \DateTime
     */
    public static function getDatetimeFromMonthNumber(string $month, ?int $day = null):\DateTime {
        $year = date('Y');
        $month =  self::MONTH_MAPPING[strtoupper($month)];
        if(!$day)
            $day = date('d');
        $datetime = new \DateTime();
        $datetime->setDate($year, $month, $day);
        return $datetime;
    }

    /**
     * Convierte una fecha en formato Excel a Timestamp interpretable por sistemas operativos basados en LINUX.
     * @param $excel_time
     * @return float|int
     */
    public static function fromExcelToLinuxTimestamp($excel_time)
    {
        return ($excel_time - 25569) * 86400;
    }

    /**
     * Comprueba si un IBAN pasado es correcto o no
     *
     * @param $iban
     * @return bool
     */
    public static function checkIBAN($iban): bool
    {
        $iban = strtolower(str_replace(' ','',$iban));
        $Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
        $Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);

        if(strlen($iban) == $Countries[substr($iban,0,2)]){

            $MovedChar = substr($iban, 4).substr($iban,0,4);
            $MovedCharArray = str_split($MovedChar);
            $NewString = "";

            foreach($MovedCharArray AS $key => $value){
                if(!is_numeric($MovedCharArray[$key])){
                    $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                }
                $NewString .= $MovedCharArray[$key];
            }

            if(bcmod($NewString, '97') == 1)
            {
                return true;
            }
        }
        return false;
    }


    public static function dd(mixed $variable)
    {
        if(!headers_sent()) {
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Headers:*');
        }
        dd($variable);
    }

    public static function dump(mixed $variable)
    {
        if(!headers_sent()) {
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Headers:*');
        }
        dump($variable);
    }
}