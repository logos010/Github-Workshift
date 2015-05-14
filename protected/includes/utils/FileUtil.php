<?php

/**
 * Description of FileUtil
 *
 * @author HEO
 */
class FileUtil {

    public static function getFileExtension($filename) {
        return strtolower(substr($filename, strpos($filename, '.') + 1));
    }

}
