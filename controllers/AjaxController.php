<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

/**
 * @var Yii $this
 */
class AjaxController extends Controller
{
    const GEOCODER_API_URL = 'https://geocode-maps.yandex.ru/1.x';

    /**
     * @throws GuzzleException
     */
    public static function actionGeocoder(?string $location): ?object
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