<?php

declare(strict_types=1);

namespace app\services;

use RuntimeException;
use Yii;

class SmsPilotService
{
    private const API_URL = 'https://smspilot.ru/api.php';

    public function send(string $phone, string $message): bool
    {
        $config = Yii::$app->params['smsPilot'] ?? [];

        if (empty($config['enabled'])) {
            Yii::info(sprintf('SMS disabled. Phone: %s. Message: %s', $phone, $message), __METHOD__);

            return true;
        }

        $apiKey = (string) ($config['apiKey'] ?? '');

        if ($apiKey === '') {
            throw new RuntimeException('SMSPilot API key is not configured.');
        }

        $query = http_build_query([
            'send' => $message,
            'to' => $phone,
            'apikey' => $apiKey,
        ]);

        $response = @file_get_contents(self::API_URL . '?' . $query);

        if ($response === false) {
            Yii::error(sprintf('SMSPilot request failed. Phone: %s', $phone), __METHOD__);

            return false;
        }

        if (!str_starts_with($response, 'SUCCESS')) {
            Yii::error(sprintf('SMSPilot error. Phone: %s. Response: %s', $phone, $response), __METHOD__);

            return false;
        }

        Yii::info(sprintf('SMS sent. Phone: %s. Response: %s', $phone, $response), __METHOD__);

        return true;
    }
}
