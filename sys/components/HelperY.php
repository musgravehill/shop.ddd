<?php

namespace app\components;

use Yii;

class HelperY
{
    public static function getPost($name, $defaultValue)
    {
        return \Yii::$app->request->post($name, $defaultValue);
    }

    public static function getGet($name, $defaultValue)
    {
        return \Yii::$app->request->get($name, $defaultValue);
    }

    public static function params($name)
    {
        return \Yii::$app->params[$name];
    }

    public static function getRelativeUrl($url)
    {
        $data = parse_url($url);
        if (!$data['path']) {
            return '/';
        }
        return $data['path'] . ($data['query'] ?? '');
    }

    public static function sanitizeHtml($res, $maxLength = 65535)
    {
        $res = strip_tags($res, '<br><br/><p><a><img><span><div><h1><h2><h3><h4><h5><blockquote><pre><b><strong><em><del>');
        $res = $res ? str_replace(array('&laquo;', '&raquo;', '«', '»', "'", '&quot;', '`'), '"', $res) : '';
        $res = $res ? str_replace(array('—', '—', '–', '−', '-'), '-', $res) : '';
        $res = $res ? str_replace(array("\r\n", '\r\n'), '<br>', $res) : '';
        $res = $res ? str_replace(array("\r", "\n", "\t"), '<br>', $res) : '';
        if ($maxLength) {
            $res = mb_substr($res, 0, $maxLength, "UTF-8");
        }
        return trim($res);
    }

    /**
     *
     * @param string $res
     * @param string $regexp '/[^\w\d]/Uui'     purify($res, '/[^\w\d]/Uui') 
     * @return string                     
     */
    public static function purify($res, $regexp)
    {
        $res = preg_replace($regexp, ' ', $res);
        $res = preg_replace('/ {2,}/Uui', ' ', $res);
        $res = $res ? str_replace(array("\r", "\n", "\t"), '', $res) : '';        
        return trim($res);
    }

    public static function sanitizeIP($res)
    {
        $res = $res ? str_replace(array("\r", "\n", "\t"), ' ', $res) : '';
        $res = preg_replace('/[^\.\d]/Uui', '', $res);
        $res = (filter_var($res, FILTER_VALIDATE_IP)) ? $res : '';
        return trim($res);
    }

    public static function sanitizeWDS($res)
    {
        $res = $res ? str_replace(array("\r", "\n", "\t"), ' ', $res) : '';
        $res = preg_replace('/[^\w\d\s]/Uui', ' ', $res);
        $res = preg_replace('/ {2,}/Uui', ' ', $res);
        return trim($res);
    }

    public static function sanitizeUrl($res)
    {
        $res = $res ? str_replace(array("\r", "\n", "\t"), '', $res) : '';
        $res = filter_var($res, FILTER_SANITIZE_URL);
        return trim($res);
    }

    public static function sanitizeText($res, $maxLength = 65535)
    {
        $res = strip_tags($res);
        $res = $res ? str_replace(array('&laquo;', '&raquo;', '«', '»', "'", '&quot;', '`'), '"', $res) : '';
        $res = $res ? str_replace(array('—', '—', '–', '−', '-'), '-', $res) : '';
        $res = $res ? str_replace(array("\r", "\n", "\t", "\r\n"), ' ', $res) : '';
        $res = preg_replace('/[^\w\d\s\-@\.\(\)#№\*!,%_=:&;\{\}\[\]\+\?\/\-"\+]/Uui', ' ', $res);
        $res = preg_replace('/\s{2,}/musi', ' ', $res);
        if ($maxLength) {
            $res = mb_substr($res, 0, $maxLength, "UTF-8");
        }
        return trim($res);
    }

    public static function transliterate($rus)
    {
        $rus = mb_strtolower($rus, "utf-8");
        $cyr = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ');
        $lat = array('a', 'b', 'v', 'g', 'd', 'e', 'e', 'zh', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', '', 'y', '', 'e', 'yu', 'ya', '-');
        $url = $rus ? str_replace($cyr, $lat, $rus) : '';
        return preg_replace("/[^\w\d-]*/Uu", '', $url);
    }
}
