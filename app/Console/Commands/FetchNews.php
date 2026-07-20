<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\NewsCache;
use Illuminate\Support\Facades\Http;
use Exception;
use SimpleXMLElement;

class FetchNews extends Command
{
    protected $signature = 'fetch:news';

protected $description = 'Fetch latest supply chain news from Google News RSS';
    public function handle()
    {
        $this->info("Mengambil daftar negara...");

        $countries = Country::all();

        $this->info("Jumlah negara : ".$countries->count());

        $totalNews = 0;

        foreach ($countries as $country) {

            $this->line("Mengambil berita ".$country->name);

            try{

                $keyword = urlencode($country->name." Supply Chain");

                $url = "https://news.google.com/rss/search?q={$keyword}";

                $response = Http::timeout(20)->get($url);

                if(!$response->successful()){

                    $this->error("Gagal");

                    continue;

                }

                $xml = new SimpleXMLElement($response->body());

                if(!isset($xml->channel->item)){

                    continue;

                }

                $count = 0;

                foreach($xml->channel->item as $item){

                    if($count>=5){

                        break;

                    }

                    $title = (string)$item->title;

                    $description = isset($item->description)
                        ? strip_tags((string)$item->description)
                        : null;

                    $link = (string)$item->link;

                    $sentiment = "Neutral";

                    $text = strtolower($title." ".$description);

                    if(
                        str_contains($text,'delay') ||
                        str_contains($text,'war') ||
                        str_contains($text,'crisis') ||
                        str_contains($text,'earthquake') ||
                        str_contains($text,'flood') ||
                        str_contains($text,'strike')
                    ){

                        $sentiment="Negative";

                    }

                    if(
                        str_contains($text,'growth') ||
                        str_contains($text,'recovery') ||
                        str_contains($text,'increase') ||
                        str_contains($text,'investment')
                    ){

                        $sentiment="Positive";

                    }

                    NewsCache::firstOrCreate(

                        [

                            'url'=>$link

                        ],

                        [

                            'country_id'=>$country->id,

                            'title'=>$title,

                            'description'=>$description,

                            'sentiment'=>$sentiment

                        ]

                    );

                    $count++;

                    $totalNews++;

                }

                $this->info("✔ ".$count." berita");

            }

            catch(Exception $e){

                $this->error($e->getMessage());

            }

        }

        $this->newLine();

        $this->info("=======================");

        $this->info("Import selesai");

        $this->info("Total berita : ".$totalNews);

        $this->info("=======================");
    }
}