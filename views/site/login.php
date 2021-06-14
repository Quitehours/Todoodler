<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="login" class="container">
  <header>
    <div class="logo"></div>
    <a href="/signup"><button class="btn-sketched btn-singin" id="signup">Регистрация</button></a>
  </header>
  <main class="wrapper">
    <div>
      <?php $form = ActiveForm::begin([
        'options' => [
          'class' => 'form-sketched'
       ]
      ]);?>
      <h1 class="head-info">Авторизация</h1>
      <?= $form->field($login_model, 'username')->textInput([
          'placeholder' => 'Логин',
          'class' => 'inp-sketched mt50',
          'type' => 'text'
      ])->label(false)?>

      <?= $form->field($login_model, 'password')->textInput([
          'placeholder' => 'Пароль',
          'type' => 'password',
          'class' => 'inp-sketched'
      ])->label(false)?>

      <?= Html::submitButton('Войти', ['class'=>'btn-sketched btn-input mt50'])?>

      <?php $form = ActiveForm::end();?>
    </div>
    <div class="pic"></div>
  </main>
</div>
