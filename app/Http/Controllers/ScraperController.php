<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScraperController extends Controller
{
    public function getMarketCap()
    {
        // Create a new Guzzle client
        $client = new Client();

        // Send a GET request to the URL
        $response = $client->request('GET', 'https://www.screener.in/company/itc/consolidated/');

        // Get the body of the response
        $html = $response->getBody()->getContents();

        // Create a new Crawler instance and pass the HTML content to it
        $crawler = new Crawler($html);

        // Use the Crawler to find the <span> element with class 'number'
        $spanNumber = $crawler->filter('span.number')->first();

        // Check if the element exists and get its text content
        $marketCap = $spanNumber->count() > 0 ? $spanNumber->text() : 'Element not found';

        // Return the result (for example, as a JSON response)
        return response()->json(['market_cap' => $marketCap]);
    }
}
