<?php

namespace app\helpers;

use Yii;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GeocoderHelpers
{
    const GEOCODER_API_URL = 'https://geocode-maps.yandex.ru/1.x';

    /**
     * @throws GuzzleException
     */
    public static function getGeocoderData(?string $location): ?object
    {
        if (!$location) {
            return null;
        }

        $client = new Client();

        $apiParams = [
            'apikey' => Yii::$app->params['geocoderApiKey'],
            'format' => 'json',
            'lang' => 'ru_RU',
            'geocode' => $location,
            'results' => 1,
        ];

        $request = $client->request('GET', self::GEOCODER_API_URL, ['query' => $apiParams]);

        $data = json_decode($request->getBody());

        if ($data->response
                ->GeoObjectCollection
                ->metaDataProperty
                ->GeocoderResponseMetaData
                ->found > 0) {
            return $data->response
                ->GeoObjectCollection
                ->featureMember[0]
                ->GeoObject;
        }

        return null;
    }
}