<?php

class Helper {

    /**
     * Print variable to screen
     * @param  mix     $var
     * @param  boolean $die die() after print
     * @param  boolean $all show to all users
     */
    public static function var_dump($var, $die = false, $all = true)
    {
        if (is_admin() || ($all == true)) {
            ?>
            <font class="debug" style="text-align: left; font-size: 10px">
                <pre><? var_dump($var) ?></pre>
                <br></font>
            <?
        }
        if ($die) {
            wp_die();
        }
    }

    /*
     * Print variable to console
     * @param  mix
     */
    public static function c_dump($var) {
        echo "<script> console.log(";
        echo json_encode($var);
        echo ");</script>";
    }

    public static function log_dump ()  {
        if ( true === WP_DEBUG ) {
            if (func_num_args() == 1) {
                $str = "";
                $val = func_get_arg(0);
            } elseif (func_num_args() == 2) {
                $str = func_get_arg(0) . ": ";
                $val = func_get_arg(1);
            }

            if ($val === true) {
                $val = "boolean: true";
            } elseif ($val === false) {
                $val = "boolean: false";
            } else {
                $val = print_r($val, 1);
            }

            error_log( $str . $val );
        }
    }

    /**
     * Подставляет единственное или множественное число для слова
     *
     * @param  ingeer $count Количество
     * @param  string $date2 Текст
     * @return string
     */
    public static function pluralize( $count, $text ) { 
        return ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
    }

    /**
     * Сравнение дат, обертка для DateTime::diff
     * !Сравнивает без учета часов
     *
     * @param  string $date1 Дата номер 1
     * @param  string $date2 Дата номер 2
     * @return object        DataTime::diff
     */
    public static function diffDate($date1, $date2 = "now") {
        $DateTime1 = new DateTime( date("Y-m-d", strtotime($date1)) );
        $DateTime2 = new DateTime( date("Y-m-d", strtotime($date2)) );
        return $DateTime2->diff($DateTime1);
    }

    /**
     * Выводит в текстовом виде время разницу между датами
     *
     * @param  string $date1 Дата номер 1
     * @param  string $date2 Дата номер 2
     * @return string
     */
    public static function getTimeAgo($date1, $date2 = "now") {
        $diff = self::diffDate($date1, $date2);
        if( $diff->y > 0 ) {
            return $diff->y ." ".self::pluralize($diff->y, "year")." ago";
        } elseif( $diff->m > 0 ) {
            return $diff->m ." ".self::pluralize($diff->m, "month")." ago";
        } elseif( $diff->d > 0 ) {
            return $diff->d ." ".self::pluralize($diff->d, "day")." ago";
        } else {
            return "today";
        }
    }

    public static function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        wp_die();
    }

    /**
     * Возвращает ссылку на указанную страницу указанного языка
     * NB: Работает только с одинаковыми slug при включенном плагине Polylang Slug
     * 
     * @param  string $original_url ссылка на страницу
     * @param  string $language     код языка
     * @return string
     */
    public static function getUrlForLang($original_url, $language = null) {
        if( $language === null ) {
            $language = pll_current_language();
        }
        $post_id = url_to_postid( $original_url );
        $lang_post_id = self::getPageIdLang( $post_id, $language );

        $url = "";
        if($lang_post_id != 0) {
            $url = get_permalink( $lang_post_id );
        }else {
            // No page found, it's most likely the homepage
            // $url = pll_home_url( $language );
            // But use origin url
            $url = $original_url;
        }

        return $url;
    }

    public static function getPageIdLang($pageId, $language = null) {
        if( $language === null ) {
            $language = pll_current_language();
        }
        return icl_object_id( $pageId , 'page', true, $language );
    }

    public static function isCurrentUrl( $url ) {
        $page = url_to_postid( $url );
        return $page > 0 && is_page( url_to_postid( $url ) );
    }

    public static function getHomeBlockUrl($block) {
        return (!is_front_page()?home_url('/'):'').'#'.$block;
    }

    public static function stripTagsWContent($text, $tags = '', $invert = FALSE) { 

        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags); 
        $tags = array_unique($tags[1]); 

        if(is_array($tags) AND count($tags) > 0) { 
            if($invert == FALSE) { 
                return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text); 
            } 
            else { 
                return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text); 
            } 
        } 
        elseif($invert == FALSE) { 
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text); 
        }
        return $text; 
    }

    public static function is_order_received_page() {
        global $wp;
        return isset( $wp->query_vars['order-received'] );
    }

    public static function hideParamPrice($str) {
        return preg_replace("/\s\(.*\)/", "", $str);
        return $str;
    }

    public static function getCounter($i = 0) {
        return function ($next = true) use (&$i) {
            if( !$next ) {
                return $i;
            }

            return $i++;
        };
    }
}