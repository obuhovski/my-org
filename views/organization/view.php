<?php

use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Organization */

$this->title = $model->displayName;
$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'displayName',
            'ogrn',
            'oktmo',
        ],
    ]) ?>

    <br>

    <h3>Пользователи организации</h3>

    <p class="pull-right">
        <?php
        Modal::begin([
            'header' => 'Импорт данных',
            'toggleButton' => ['label' => 'Добавить пользователя', 'class' => 'btn btn-primary', 'onclick' => "$('#add-user-form')[0].reset()"],
        ]);
        ?>

        <?php $form = ActiveForm::begin([
            'id' => 'add-user-form',
            'enableAjaxValidation' => true
        ]); ?>

        <?= $form->field($user, 'firstname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($user, 'lastname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($user, 'middlename')->textInput(['maxlength' => true]) ?>

        <?= $form->field($user, 'b_date')->widget(DatePicker::className(), [
            'removeButton' => false,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($user, 'inn')->textInput() ?>

        <?= $form->field($user, 'snils')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Modal::end(); ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getUsers()]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'firstname',
            'lastname',
            'middlename',
            'b_date:date',
            'inn',
            'snils',

        ],
    ]); ?>

</div>
