<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Организации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="pull-right">
        <?php 
            Modal::begin([
                'header' => 'Импорт данных',
                'toggleButton' => ['label' => 'Импортировать данные', 'class' => 'btn btn-success'],
            ]); 
        ?>
        
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <?= $form->field($importDataOrgForm, 'file')->fileInput(['accept' => 'text/xml']) ?>
            <div class="form-group text-right">
                <?= Html::submitButton('Заргузить', ['class' => 'btn btn-success' ]) ?>
            </div>
            <?php ActiveForm::end(); ?>

         <?php Modal::end(); ?>

    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'displayName',
            'ogrn',
            'oktmo',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} ',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
