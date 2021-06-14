<div id="main" class="container">
  <header>
    <div id="logo" class="logo"></div>
    <button class="btn-sketched btn-singin" id="logout">Выйти</button>
  </header>
  <div id="modal" class="modal-wrapper hidden">
    <div class="modal">
      <h1>Создать группу</h1>
      <div>
        <input id="modal-input" class="inp-sketched" type="text">
        <button id="modal-add" class="btn-sketched btn-rounded">Добавить</button>
      </div>
    </div>
  </div>
  <main class="wrapper">
    <div>
      <div class="groups">
        <div class="groups-header">
          <h1>Списки</h1>
          <button id="add-group" class="btn-sketched btn-rounded">+</button>
        </div>
        <!-- <ul id="default-group-list"> 
          <li onclick="selectGroup(event)" data-id="-1" class="selected">Все задачи</li>
          <li onclick="selectGroup(event)" data-id="0">Персональные</li>
        </ul> -->
        <ul id="group-list">
            <!-- <div><button class="btn-sketched btn-rounded">X</button></div>
            <div>Список покупок</div> -->
          <!-- </li> 
          <li><button class="btn-sketched btn-rounded">X</button> Работа</li> -->
        </ul>
      </div>
    </div>
    <div>
      <div class="input-wrapper">
        <input id="input-text" class="todo-input" type="text">
        <button id="add-todo" class="btn-sketched btn-rounded"></button>
      </div>
      <div id="todo-list" class="todo-list">
        <!-- <div cla s="todo">
          <input type="checkbox">
          <span>Купить еды</span>
          <div class="delete"></div>
        </div>
        <div class="todo checked">
          <input type="checkbox" checked>
          <span>Купить воды</span>
          <div class="delete"></div>
        </div>
        <div class="todo">
          <input type="checkbox">
          <span class="group">*Персональные</span>
          <span>Пойти на шиномонтажку к дяде Максиму,
            разобраться с ручником</span>
          <div class="delete"></div>
        </div> -->
        <h1>Загрузка...</h1>
      </div>
    </div>
  </main>
</div>
<?php $this->registerJsFile('@web/scripts/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]) ?>