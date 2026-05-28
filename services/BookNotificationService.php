<?php

declare(strict_types=1);

namespace app\services;

use app\models\AuthorSubscription;
use app\models\Book;

readonly class BookNotificationService
{
    public function __construct(
        private SmsPilotService $smsPilotService,
    ) {
    }

    public function notifySubscribers(Book $book): void
    {
        $authorIds = $book->getAuthors()
                          ->select('id')
                          ->column();

        if ($authorIds === []) {
            return;
        }

        $subscriptions = AuthorSubscription::find()
                                           ->where(['author_id' => $authorIds])
                                           ->all();

        $phones = [];
        foreach ($subscriptions as $subscription) {
            $phones[$subscription->phone] = $subscription->phone;
        }

        foreach ($phones as $phone) {
            $this->smsPilotService->send($phone, 'New book has been added to the catalog.');
        }
    }
}
