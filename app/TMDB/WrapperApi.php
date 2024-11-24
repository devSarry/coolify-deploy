<?php

namespace App\TMDB;

use Cache;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tmdb\Api\Movies;
use Tmdb\Client;
use Tmdb\Event\BeforeRequestEvent;
use Tmdb\Event\Listener\Request\AcceptJsonRequestListener;
use Tmdb\Event\Listener\Request\ApiTokenRequestListener;
use Tmdb\Event\Listener\Request\ContentTypeJsonRequestListener;
use Tmdb\Event\Listener\Request\UserAgentRequestListener;
use Tmdb\Event\Listener\RequestListener;
use Tmdb\Event\RequestEvent;
use Tmdb\Model\Movie;
use Tmdb\Token\Api\ApiToken;
use Tmdb\Token\Api\BearerToken;

class WrapperApi
{
    private $client;

    private $ed;


    public function __construct()
    {
        $token =  new ApiToken(config('tmbd.api_key'));

        $ed = new EventDispatcher();

        $this->ed = $ed;


        $client = new Client(
            [
                /** @var ApiToken|BearerToken */
                'api_token' => $token,
                'event_dispatcher' => [
                    'adapter' => $ed
                ],
                // We make use of PSR-17 and PSR-18 auto discovery to automatically guess these, but preferably set these explicitly.
                'http' => [
                    'client' => null,
                    'request_factory' => null,
                    'response_factory' => null,
                    'stream_factory' => null,
                    'uri_factory' => null,
                ]
            ]
        );
        $requestListener = new RequestListener($client->getHttpClient(), $ed);
        $ed->addListener(RequestEvent::class, $requestListener);

        $apiTokenListener = new ApiTokenRequestListener($client->getToken());
        $ed->addListener(BeforeRequestEvent::class, $apiTokenListener);

        $acceptJsonListener = new AcceptJsonRequestListener();
        $ed->addListener(BeforeRequestEvent::class, $acceptJsonListener);

        $jsonContentTypeListener = new ContentTypeJsonRequestListener();
        $ed->addListener(BeforeRequestEvent::class, $jsonContentTypeListener);

        $userAgentListener = new UserAgentRequestListener();
        $ed->addListener(BeforeRequestEvent::class, $userAgentListener);

        $this->client = $client;
    }

    /**
     * @return array{
     *     adult: bool,
     *     backdrop_path: string,
     *     belongs_to_collection: ?array,
     *     budget: int,
     *     genres: array,
     *     homepage: string,
     *     id: int,
     *     imdb_id: string,
     *     origin_country: array,
     *     original_language: string,
     *     original_title: string,
     *     overview: string,
     *     popularity: float,
     *     poster_path: string,
     *     production_companies: array,
     *     production_countries: array,
     *     release_date: string,
     *     revenue: int,
     *     runtime: int,
     *     spoken_languages: array,
     *     status: string,
     *     tagline: string,
     *     title: string,
     *     video: bool,
     *     vote_average: float,
     *     vote_count: int
     * }
     */
    public function getMovieDetails(int $id)
    {
        return Cache::get('tmdb-movie-details-' . $id, function () use ($id) {
            return $this->client->getMoviesApi()->getMovie($id);
        });
    }


}
