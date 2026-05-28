<?php

use app\models\Author;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'authorIds')->checkboxList(
        ArrayHelper::map(
            Author::find()->orderBy(['full_name' => SORT_ASC])->all(),
            'id',
            'full_name'
        )
    )->label('Authors') ?>

    <?= $form->field($model, 'release_year')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coverImageFile')->fileInput() ?>

    <?php if ($model->cover_image): ?>
        <p>
            Current image:<br>
            <?= Html::img($model->cover_image, [
                'alt' => $model->title,
                'style' => 'max-width: 150px; height: auto;',
            ]) ?>
        </p>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
