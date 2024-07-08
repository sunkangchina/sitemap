<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests; 
/*
* Post Put Patch Delete Options
* https://github.com/spatie/laravel-route-attributes
*/
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class SiteMapController extends Controller
{
    #[Get('/sitemap/{url?}')]
    /**
    * 生成sitemap.xml  
    */
    public function index($url)
    {

        /*     
        https://github.com/spatie/laravel-sitemap
        */
        $req_url = 'https://'.$url; 
        try {
            $arr = get_headers($req_url, true);
        } catch (\Exception $e) {
            throw new \Exception(__('messages.Please enter real url address')); 
        } 
        $path = storage_path('app/public/sitemap.xml');
        $res = SitemapGenerator::create($req_url)->hasCrawled(function (Url $url) use ($req_url) { 
           $path = $url->path();
           $url->setUrl($req_url.urldecode($path)); 
           return $url;
        })->getSitemap();  
        $res->writeToFile($path);

        echo file_get_contents($path);
         
    }
}
