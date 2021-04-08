<?php


namespace Core\File;


class UploadedFileManager extends FileManager
{
    public function get(string $path)
    {
        preg_match('/^[0-9a-fA-F]*/', $path, $out)[0];
        $id = $out[0] ?? null;
        $info = (new FileRepository())->getByIdString($id);
        if ($info == null) {
            http_response_code(404);
            exit;
        }
        $filepath = (new File())->getDir().'/'.$id.'.bin';
        if (!is_file($filepath)) {
            http_response_code(404);
            exit;
        }
        ob_end_clean();

        $this->output($filepath, $info->mime);
    }
}