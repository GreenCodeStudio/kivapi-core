<?php

namespace Core\File;

class AssetManager extends FileManager
{
    public function get(string $path)
    {
        $path = explode("?", $path)[0];
        $filepath = $this->findFile($path);
        if ($filepath == null) {
            http_response_code(404);
            exit;
        }
        ob_end_clean();
        $this->output($filepath, mime_content_type($filepath));
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
