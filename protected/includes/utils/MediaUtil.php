<?php

/**
 * Description of MediaUtil
 *
 * @author HAO
 */
class MediaUtil {

    public static function write($model, $width, $height) {
        if ($model->ext == 'flv')
            return self::writeObject($model, $width, $height);
        else
            return self::writeQuickTime($model, $width, $height);
    }

    public static function writeObject($model, $width, $height) {
        $url = ShortUtil::baseUrl() . '/' . $model->uri;
        $flash = ShortUtil::themePath() . '/images/flvplayer.swf';
        return "<embed src=\"{$flash}\" type=\"application/x-shockwave-flash\" 
            pluginspage=\"http://www.macromedia.com/go/getflashplayer\" width=\"{$width}\" height=\"{$height}\" 
            allowfullscreen=\"true\" flashvars=\"file={$url}&height={$height}&width={$width}&autostart=false&link=http://www.thegioinuochoa.com.vn/&linkfromdisplay=false&linktarget=_blank&backcolor=0x444444&frontcolor=0xFFFFFF&lightcolor=0xE9006F&shownavigation=true&showdigits=total&showvolume=true&usefullscreen=true&\" 
            bgcolor=\"#FFFFFF\" allowScriptAccess=\"always\"></embed>";
    }

    public static function writeQuickTime($model, $width, $height) {
        $url = urlencode(ShortUtil::baseUrl()."/services/media/index?cate=".Yii::app()->controller->cate."&subCate=".Yii::app()->controller->subCate."&id={$model->id}");
//        echo urldecode($url);
        $flash = ShortUtil::themePath() . '/images/player_local.swf';
        
        return '<object width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash" 
            id="musicplayer" name="player" data="' . $flash . '">
        <param name="menu" value="true">
        <param name="allowfullscreen" value="true">
        <param name="allowscriptaccess" value="always">
        <param name="wmode" value="opaque">
        <param name="flashvars" value="xmlPath='  . $url .'&amp;colorAux=0x0099ff&amp;colorBorder=0x333333&amp;colorMain=0xffffff&amp;local=embed&amp;mAuto=true&amp;repeat=false&amp;fvalue=995&amp;nid=0"></object>';
    }

}

?>
