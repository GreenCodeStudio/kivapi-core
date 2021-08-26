<?php

namespace Core\SpecialPages;

use Core\ComponentManager\PageRepository;
use DOMDocument;

class Sitemap implements ISpecialPage
{
    public function generate(): void
    {
        header('content-type: application/xml');
        $dom = new DOMDocument("1.0", "UTF-8");
        $root = $dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
        $dom->append($root);
        $this->generateUrls($dom, $root);
        echo $dom->saveXML();
    }

    public function generateUrls($dom, $root)
    {
        $all = (new PageRepository())->getAll();
        $urlPrefix = $_ENV['urlPrefix'];
        foreach ($all as $page) {
            if (!empty($page->path)) {
                $url = $dom->createElement('url');
                $url->append($dom->createElement('loc', $urlPrefix . $page->path));
                $root->append($url);
            }
        }
    }
}