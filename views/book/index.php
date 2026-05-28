<?php

use app\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'release_year',
            'isbn',
            [
                'attribute' => 'cover_image',
                'format' => 'html',
                'value' => static function (Book $model): string {
                    if (empty($model->cover_image)) {
                        return 'No image';
                    }

                    return Html::img($model->cover_image, [
                        'alt' => $model->title,
                        'style' => 'max-width: 60px; height: auto;',
                    ]);
                },
            ],
            [
                'class' => ActionColumn::class,
                'template' => Yii::$app->user->isGuest ? '{view}' : '{view} {update} {delete}',
                'urlCreator' => function ($action, Book $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
