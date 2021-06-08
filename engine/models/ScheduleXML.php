<?php

/**
 * Project: raspprod
 * Date: 21.07.2017
 * Time: 18:28
 */
class ScheduleXML
{
    public static function calculateRevision ($file) {
        $algo = md5_file($file);
        return $algo ?: false;
    }

    public static function validateFile ($file) {
        return stripos($file, '<format>136</format>') !== false;
    }

    public static function removeByRev ($rev) {
        if (LATEST_REV == $rev) file_put_contents(STORAGE_DIR . '/latest', '');
        @delete_dir(STORAGE_RAW_DIR. '/' .$rev. '/');
        @unlink(STORAGE_DIR. '/temp/' .$rev. '.xml');
    }

    public static function moveFile ($file) {
        $rev = self::calculateRevision($file);
        $upload_file = UPLOAD_DIR . $rev. '.xml';
        $validity = self::validateFile(file_get_contents($file));
        if ($validity && rename($file, $upload_file)) {
            return array($upload_file, $rev);
        } else {
            return false;
        }
    }
}