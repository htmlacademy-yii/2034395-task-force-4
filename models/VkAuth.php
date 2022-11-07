<?php

namespace app\models;

use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;
use VK\Exceptions\VKOAuthException;
use Yii;
use yii\base\Model;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

/**
 *
 */
class VkAuth extends Model
{
    private array $scope = [VKOAuthUserScope::WALL, VKOAuthUserScope::EMAIL];
    private array $fields = ['city', 'email', 'photo_200_orig', 'first_name', 'bdate'];

    public function auth(): void
    {
        $oauth = new VKOAuth();
        $url = $oauth->getAuthorizeUrl(
            VKOAuthResponseType::CODE,
            Yii::$app->params['vkClientId'],
            Yii::$app->params['vkRedirectUri'],
            VKOAuthDisplay::POPUP,
            $this->scope,
        );

        Yii::$app->response->redirect($url);
    }

    /**
     * @throws VKOAuthException
     * @throws VKClientException
     */
    public function getToken(string $code): array
    {
        $oauth = new VKOAuth();
        return $oauth->getAccessToken(
            Yii::$app->params['vkClientId'],
            Yii::$app->params['vkClientSecret'],
            Yii::$app->params['vkRedirectUri'],
            $code
        );
    }

    /**
     * @throws VKApiException
     * @throws VKClientException
     */
    public function getUserData(array $token): array
    {
        $api = new VKApiClient();
        $accessToken = $token['access_token'];
        $userId = $token['user_id'];

        $result = [];

        $users = $api->users()
            ->get($accessToken, [
                'user_ids' => $userId,
                'fields' => $this->fields
            ]);

        if (count($users) === 1) {
            $result = $users[0];
        }

        return $result;
    }
}