<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="register" class="container">
    <header>
      <div class="logo"></div>
      <a href="/login"><button class="btn-sketched btn-singin" id="login">Войти</button></a>
    </header>
    <main class="wrapper">
      <div>
        <!-- <div class="form-sketched"> -->
          <?php //todo Открываем ActiveForm для работы с формами Yii2   
          $form = ActiveForm::begin([
            'options' => [
              'class' => 'form-sketched'
              ]
              ]);?>
        <h1 class="head-info">Регистрация</h1>
          <?= $form->field($model, 'username')->textInput([
            'class' => 'inp-sketched mt50',
            'placeholder' => 'Логин',
            'type' =>'text'

            // label убрал 
          ])->label(false) ?>
          <?= $form->field($model, 'password')->textInput([
            'class' => 'inp-sketched',
            'type' =>'password',
            'placeholder' => 'Пароль'

          ])->label(false) ?>
           <p><?= $validate ? 'Пользователь с таким логином уже существует' : '' ?></p>
          <?= Html::submitButton('Зарегистрироваться', ['class'=>'btn-sketched btn-input mt50'])?>
          <?php ActiveForm::end() ?>
          <!-- </div> -->
      </div>
      <div class="pic"></div>
    </main>
  </div>

