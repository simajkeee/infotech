<?php

declare(strict_types=1);

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actionTopAuthors(?int $year = null): string
    {
        $year ??= (int) date('Y');

        $rows = (new Query())
            ->select([
                'author_id' => 'a.id',
                'full_name' => 'a.full_name',
                'book_count' => 'COUNT(b.id)',
            ])
            ->from(['a' => '{{%authors}}'])
            ->innerJoin(['ba' => '{{%book_author}}'], 'ba.author_id = a.id')
            ->innerJoin(['b' => '{{%books}}'], 'b.id = ba.book_id')
            ->where(['b.release_year' => $year])
            ->groupBy(['a.id', 'a.full_name'])
            ->orderBy(['book_count' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('top-authors', [
            'year' => $year,
            'rows' => $rows,
        ]);
    }
}
