<?php

namespace Core\SpecialPages;
class Robots implements ISpecialPage
{
    public function generate(): void
    {
        $urlPrefix = $_ENV['urlPrefix'];
        header('content-type: text/plain');
        echo "User-Agent: *
Allow: /

Sitemap: $urlPrefix/sitemap.xml";
    }
}