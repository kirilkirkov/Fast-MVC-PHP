<?php
require rtrim(dirname(__FILE__), '/') . '/../Helpers.php';

use App\Models\User;

function generate() {
    @unlink(rtrim(dirname(__FILE__), '/') . "/../../public/sitemap.xml");

    $content = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $slugs = (new User())->getAllSlugs();
    foreach($slugs as $slug) {
        $content .= '<url>
        <loc>https://biojivot.com/p/'.$slug['slug'].'</loc>
      </url>';
    }
    
    $content .= '</urlset>';

    $myfile = fopen(rtrim(dirname(__FILE__), '/') . "/../../public/sitemap.xml", "w") or die("Unable to open file!");
    fwrite($myfile, $content);
    fclose($myfile);
}