<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;

class CalendarController extends Controller
{
  public function calendar()
  {
    $viewData = $this->loadViewData();

    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);

    $queryParams = array(
        //'$select' => 'start,end',
      '$select' => 'subject,organizer,start,end',
      '$orderby' => 'createdDateTime DESC'
    );

    // Append query parameters to the '/me/events' url
    $getEventsUrl = '/me/events?'.http_build_query($queryParams);

    $events = $graph->createRequest('GET', $getEventsUrl)
      ->setReturnType(Model\Event::class)
      ->execute();

      $viewData['events'] = $events;
      return view('calendar', $viewData);
  }


  public function getCalendarID()
  {
    $viewData = $this->loadViewData();

    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);

    // Append query parameters to the '/me/events' url
    $getEventsUrl = '/me/calendar';

    $events = $graph->createRequest('GET', $getEventsUrl)
      ->setReturnType(Model\Event::class)
      ->execute();

      print_r($events);
      
  }

  public function posts()
  {
    $viewData = $this->loadViewData();

    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);
    $id = 'AQMkADdkNmYxNDkyLTgwZTgtNDhkZS1iOWY4LWFmMzY5ZDBkMWM2ZQBGAAADoDVqpyagXEW74hUu8Hq7PgcAqfdduveac0OsiDWQMaeAnwAAAgEGAAAAqfdduveac0OsiDWQMaeAnwAAAh0SAAAA';

    $data = [
        'Subject' => 'Discuss the Calendar REST API',
        'Body' => [
            'ContentType' => 'HTML',
            'Content' => 'I think it will meet our requirements!',
        ],
        'Start' => [
            'DateTime' => '2020-07-03T19:00:00',
            'TimeZone' => 'Europe/Paris',
        ],
        'End' => [
            'DateTime' => '2020-07-03T20:00:00',
            'TimeZone' => 'Europe/Paris',
        ],
    ];

    // Append query parameters to the '/me/events' url
    $url = '/me/events';

    $events = $graph->createRequest('POST', $url)
        ->attachBody($data)
        ->setReturnType(Model\Event::class)
        ->execute();

      print_r($events);
  }

  
  public function test()
  {
      echo('lol');
      
  }
}

