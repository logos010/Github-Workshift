<?php

/**
 * Description of ImageUtil
 *
 * @author HEO
 */
class ImageUtil {

    const SMALL_PATH = 'upload/small/';
    const MEDIUM_PATH = 'upload/medium/';
    const ORIGINAL_PATH = 'upload/original/';
    const ADS_PATH = 'upload/ads/';

    public static function createImagePath($name, $sizePath = self::SMALL_PATH) {
        if ($name == '')
            return;
        return ShortUtil::homeUrl() . $sizePath . $name;
    }
    
    
}