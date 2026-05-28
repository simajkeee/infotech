<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $year */
/** @var array $rows */

$this->title = 'Top 10 Authors';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-top-authors">
    <h1><?= Html::encode($this->title) ?></h1>

    <form method="get" action="">
        <input type="hidden" name="r" value="report/top-authors">

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input
                type="number"
                id="year"
                name="year"
                class="form-control"
                value="<?= Html::encode((string) $year) ?>"
                min="1000"
                max="<?= Html::encode((string) date('Y')) ?>"
            >
        </div>

        <button type="submit" class="btn btn-primary">Show report</button>
    </form>

    <br>

    <?php if ($rows === []): ?>
        <p>No books found for this year.</p>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Author</th>
                <th>Books Count</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($row['full_name']) ?></td>
                    <td><?= Html::encode((string) $row['book_count']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
