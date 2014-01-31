<?php
class Helper {
    //обрезает строку
    public static function truncate($str, $length = 25, $suffix = '...') {
        $str = trim(strip_tags($str));
        $breaks = array('.', ',', '?', '!', ' ', ':'. '-', '(', ')');
        $text = mb_substr($str, 0, $length);
        if (mb_strlen($str) > $length) {
            $pos = 999;
            for ($i = 0; $i < sizeof($breaks); $i++) {
                if (false !== ($tmp = mb_strpos($str, $breaks[$i], $length))) {
                    if ($tmp <= $pos) {
                        $pos = $tmp;
                    }
                }
            }
            $text .= mb_substr($str, $length, $pos - $length);
            $text .= $suffix;
        }
        return $text;
    }
}