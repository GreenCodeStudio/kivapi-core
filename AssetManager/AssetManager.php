<?php

namespace Core\AssetManager;

class AssetManager
{
    public static function getAsset(string $path)
    {
        $filepath = static::findFile($path);
        if ($filepath == null) {
            http_response_code(404);
            exit;
        }
        ob_end_clean();
        header('content-type: '.mime_content_type($filepath));
        $file = fopen($filepath, 'r');
        while ($data = fread($file, 1024)) {
            echo $data;
        }
        exit;
    }

    public static function findFile(string $path)
    {
        $path = str_replace('\\', '/', $path);
        if (preg_match('/\/\.\.?\//', $path)) throw new \Exception();
        $filepath = __DIR__.'/../../Assets/'.$path;
        if (is_file($filepath))
            return $filepath;
        return null;
    }
}