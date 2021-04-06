<?php

namespace Core\AssetManager;

use Core\File\FileManager;

class AssetManager extends FileManager
{
    public function get(string $path)
    {
        $filepath = $this->findFile($path);
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

    public function findFile(string $path)
    {
        $path = str_replace('\\', '/', $path);
        if (preg_match('/\/\.\.?\//', $path)) throw new \Exception();
        $filepath = __DIR__.'/../../Assets/'.$path;
        if (is_file($filepath))
            return $filepath;
        return null;
    }
}