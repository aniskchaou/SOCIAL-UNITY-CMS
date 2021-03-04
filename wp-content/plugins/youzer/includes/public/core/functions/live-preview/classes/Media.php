<?php
/**
 * Copyright (c) 2015 Leonardo Cardoso (http://leocardz.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.0
 */

/**
 *  This class mounts the iframe embed code for the video services below
 * */
class Media
{

    /** Return iframe code for Youtube videos */
    static function mediaYoutube($url)
    {
        $media = array();
        if (preg_match("/(.*?)v=(.*?)($|&)/i", $url, $matching)) {
            $vid = $matching[2];
            array_push($media, "https://i2.ytimg.com/vi/$vid/hqdefault.jpg");
            array_push($media, '<div class="embed-responsive embed-responsive-16by9"><iframe id="' . date("YmdHis") . $vid . '" class="embed-responsive-item" width="499" height="368" src="http://www.youtube.com/embed/' . $vid . '" frameborder="0" allowfullscreen></iframe></div>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    /** Return iframe code for TED videos */
    static function mediaTED($url)
    {
        $url = explode("/", $url);
        $media = array();
        if (count($url) > 0) {
            $url = $url[count($url) - 1];
            $url = explode("?", $url);
            if (count($url) > 0) {
                $url = $url[0];
                $embed = '<iframe src="https://embed-ssl.ted.com/talks/' . $url . '.html" width="640" height="360" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
                array_push($media, "", '<div class="embed-responsive embed-responsive-16by9">' . $embed . '</div>');
            } else {
                array_push($media, "", "");
            }
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    /** Return iframe code for Vine videos */
    static function mediaVine($url)
    {
        $url = str_replace("https://", "", $url);
        $url = str_replace("http://", "", $url);
        $breakUrl = explode("/", $url);
        $media = array();
        if ($breakUrl[2] != "") {
            $vid = $breakUrl[2];
            array_push($media, Media::mediaVineThumb($vid));
            array_push($media, '<div class="embed-responsive embed-responsive-16by9 lp-vine-fix"><iframe id="' . date("YmdHis") . $vid . '" class="vine-embed embed-responsive-item" src="https://vine.co/v/' . $vid . '/embed/simple" width="499" height="499" frameborder="0"></iframe></div><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    static function mediaVineThumb($id)
    {
        $vine = file_get_contents("http://vine.co/v/{$id}");
        preg_match('/property="og:image" content="(.*?)"/', $vine, $matches);

        return ($matches[1]) ? $matches[1] : false;
    }

    /** Return iframe code for Vimeo videos */
    static function mediaVimeo($url)
    {
        $url = str_replace("https://", "", $url);
        $url = str_replace("http://", "", $url);
        $breakUrl = explode("/", $url);
        $media = array();
        if ($breakUrl[1] != "") {
            $imgId = $breakUrl[1];
            $hash = unserialize(file_get_contents("https://vimeo.com/api/v2/video/$imgId.php"));
            array_push($media, $hash[0]['thumbnail_large']);
            array_push($media, '<div class="embed-responsive embed-responsive-16by9"><iframe id="' . date("YmdHis") . $imgId . '" class="embed-responsive-item" width="499" height="280" src="http://player.vimeo.com/video/' . $imgId . '" width="654" height="368" frameborder="0" webkitallowfullscreen mozallowfullscreen allowFullScreen ></iframe></div>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    /** Return iframe code for Metacafe videos */
    static function mediaMetacafe($url)
    {
        $media = array();
        preg_match('|metacafe\.com/watch/([\w\-\_]+)(.*)|', $url, $matching);
        if ($matching[1] != "") {
            $vid = $matching[1];
            $vtitle = trim($matching[2], "/");
            array_push($media, "http://s4.mcstatic.com/thumb/{$vid}/0/6/videos/0/6/{$vtitle}.jpg");
            array_push($media, '<div class="embed-responsive embed-responsive-16by9"><iframe id="' . date("YmdHis") . $vid . '" class="embed-responsive-item" width="499" height="368" src="http://www.metacafe.com/embed/' . $vid . '" allowFullScreen frameborder=0></iframe></div>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    /** Return iframe code for Dailymotion videos */
    static function mediaDailymotion($url)
    {
        $media = array();
        $id = strtok(basename($url), '_');
        if ($id != "") {
            //$hash = file_get_contents("http://www.dailymotion.com/services/oembed?format=json&url=http://www.dailymotion.com/embed/video/$id");
            //$hash=json_decode($hash,true);
            //array_push($media, $hash['thumbnail_url']);

            array_push($media, "http://www.dailymotion.com/thumbnail/160x120/video/$id");
            array_push($media, '<div class="embed-responsive embed-responsive-16by9"><iframe id="' . date("YmdHis") . $id . '" class="embed-responsive-item" width="499" height="368" src="http://www.dailymotion.com/embed/video/' . $id . '" allowFullScreen frameborder=0></iframe></div>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    /** Return iframe code for College Humor videos */
    static function mediaCollegehumor($url)
    {
        $media = array();
        preg_match('#(?<=video/).*?(?=/)#', $url, $matching);
        $id = $matching[0];
        if ($id != "") {
            $hash = file_get_contents("http://www.collegehumor.com/oembed.json?url=http://www.dailymotion.com/embed/video/$id");
            $hash = json_decode($hash, true);
            array_push($media, $hash['thumbnail_url']);
            array_push($media, '<div class="embed-responsive embed-responsive-16by9"><iframe id="' . date("YmdHis") . $id . '" class="embed-responsive-item" width="499" height="368" src="http://www.collegehumor.com/e/' . $id . '" allowFullScreen frameborder=0></iframe></div>');
        } else {
            array_push($media, "", "");
        }
        return $media;

    }

    /** Return iframe code for Blip videos */
    static function mediaBlip($url)
    {
        $media = array();
        if ($url != "") {
            $hash = file_get_contents("http://blip.tv/oembed?url=$url");
            $hash = json_decode($hash, true);
            preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $hash['html'], $matching);
            $src = $matching[1];
            array_push($media, $hash['thumbnail_url']);
            array_push($media, '<div class="embed-responsive embed-responsive-16by9"><iframe id="' . date("YmdHis") . 'blip" class="embed-responsive-item" width="499" height="368" src="' . $src . '" allowFullScreen frameborder=0></iframe></div>');
        } else {
            array_push($media, "", "");
        }
        return $media;
    }

    /** Return iframe code for Funny or Die videos */
    static function mediaFunnyordie($url)
    {
        $media = array();
        if ($url != "") {
            $hash = file_get_contents("http://www.funnyordie.com/oembed.json?url=$url");
            $hash = json_decode($hash, true);
            preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $hash['html'], $matching);
            $src = $matching[1];
            array_push($media, $hash['thumbnail_url']);
            array_push($media, '<div class="embed-responsive embed-responsive-16by9"><iframe id="' . date("YmdHis") . 'funnyordie" class="embed-responsive-item" width="499" height="368" src="' . $src . '" allowFullScreen frameborder=0></iframe></div>');
        } else {
            array_push($media, "", "");
        }
        return $media;

    }

}
