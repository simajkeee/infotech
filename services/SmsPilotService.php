<?php

declare(strict_types=1);

namespace app\services;

use RuntimeException;
use Yii;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class SmsPilotService
{
    private const API_URL = 'https://smspilot.ru/api.php';

    public function __construct(
        private readonly Client $client = new Client(),
    ) {
    }

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

        $phone = $this->normalizePhone($phone);
        try {
            $response = $this->client
                ->createRequest()
                ->setMethod('GET')
                ->setUrl(self::API_URL)
                ->setData([
                    'send' => $message,
                    'to' => $phone,
                    'from' => (string) ($config['from'] ?? 'INFORM'),
                    'apikey' => $apiKey,
                    'format' => 'json',
                ])
                ->setOptions([
                    'timeout' => 10,
                ])
                ->send();
        } catch (Exception $exception) {
            Yii::error(
                sprintf('SMSPilot request failed. Phone: %s. Error: %s', $phone, $exception->getMessage()),
                __METHOD__
            );

            return false;
        }

        if (!$response->isOk) {
            Yii::error(
                sprintf('SMSPilot HTTP error. Phone: %s. Status: %s. Response: %s', $phone, $response->statusCode, $response->content),
                __METHOD__
            );

            return false;
        }

        $data = $response->data;
        if (!is_array($data)) {
            Yii::error(
                sprintf('SMSPilot invalid response. Phone: %s. Response: %s', $phone, $response->content),
                __METHOD__
            );

            return false;
        }

        if (isset($data['error'])) {
            $description = $data['error']['description_ru']
                ?? $data['error']['description']
                ?? 'Unknown SMSPilot error';

            Yii::error(
                sprintf('SMSPilot error. Phone: %s. Error: %s. Response: %s', $phone, $description, $response->content),
                __METHOD__
            );

            return false;
        }

        Yii::info(
            sprintf('SMS sent. Phone: %s. Response: %s', $phone, $response->content),
            __METHOD__
        );

        return true;
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
