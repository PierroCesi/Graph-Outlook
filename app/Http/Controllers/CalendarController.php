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



  public function posts()
  {
    $viewData = $this->loadViewData();

    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);

    $data = [
        'Subject' => 'Discuss the Calendar REST API',
        'Body' => [
            'ContentType' => 'HTML',
            'Content' => 'I think it will meet our requirements!',
        ],
        'Start' => [
            'DateTime' => '2020-07-03T19:00:00',
            'TimeZone' => 'Pacific Standard Time',
        ],
        'End' => [
            'DateTime' => '2020-07-03T20:00:00',
            'TimeZone' => 'Pacific Standard Time',
        ],
    ];

    // Append query parameters to the '/me/events' url
    $url = '/me/events';

    $events = $graph->createRequest('POST', $url)
        ->attachBody($data)
        ->setReturnType(Model\Event::class)
        ->execute();

      echo '<pre>';print_r($events);exit;
  }

  
  public function post()
  {
      echo('lol');
      
  }
}