<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\AuthorSubscription;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
/** @var AuthorSubscription $subscription */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
        ],
    ]) ?>

    <h3>Subscribe to new books by this author</h3>

    <?php $form = ActiveForm::begin([
        'action' => ['author/subscribe', 'id' => $model->id],
        'method' => 'post',
    ]); ?>

    <?= $form->field($subscription, 'phone')->textInput([
        'maxlength' => true,
        'placeholder' => '+79990000000',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Subscribe', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
