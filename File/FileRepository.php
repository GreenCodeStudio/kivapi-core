<?php
namespace Core\File;

use Core\Database\Repository;

class FileRepository extends Repository
{

    public function defaultTable(): string
    {
        return 'file';
    }
}