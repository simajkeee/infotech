<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Book Catalog';
?>

<div class="site-index">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-4">
            <h1 class="display-5 fw-bold">Book Catalog</h1>

            <p class="fs-5">
                Yii2 web application for managing books, authors, subscriptions, and author statistics.
            </p>

            <p>
                <?= Html::a('View Books', ['/book/index'], ['class' => 'btn btn-primary btn-lg me-2']) ?>
                <?= Html::a('View Authors', ['/author/index'], ['class' => 'btn btn-outline-primary btn-lg me-2']) ?>
                <?= Html::a('Top Authors Report', ['/report/top-authors'], ['class' => 'btn btn-outline-secondary btn-lg']) ?>
            </p>
        </div>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Books</h2>

                <p>
                    Browse books with title, release year, description, ISBN, cover image, and related authors.
                </p>

                <p>
                    <?= Html::a('Open Books', ['/book/index'], ['class' => 'btn btn-outline-primary']) ?>
                </p>
            </div>

            <div class="col-lg-4 mb-3">
                <h2>Authors</h2>

                <p>
                    Browse authors and subscribe to notifications about new books by a selected author.
                </p>

                <p>
                    <?= Html::a('Open Authors', ['/author/index'], ['class' => 'btn btn-outline-primary']) ?>
                </p>
            </div>

            <div class="col-lg-4 mb-3">
                <h2>Report</h2>

                <p>
                    View the top 10 authors by number of published books for a selected release year.
                </p>

                <p>
                    <?= Html::a('Open Report', ['/report/top-authors'], ['class' => 'btn btn-outline-primary']) ?>
                </p>
            </div>
        </div>
    </div>
</div>
