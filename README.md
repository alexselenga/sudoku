INSTALLATION
------------

//DB Dump: sudoku.sql 
	('dsn' => 'mysql:host=localhost;dbname=sudoku', 'username' => 'root', 'password' => '', 'charset' => 'utf8')

//PHP	
    composer install
    
//JS
    yarn install

//Запуск WebSocket сервера. Возможно, придется скорректировать порт - 72 строка в ws.php. По умолчанию 8089.
    php commands/ws.php

//Скорректировать src/App.vue
    backHost: 'http://sudoku/',
    wsHost: 'ws://sudoku:8089',

//Обновить JS
    "build": "vue-cli-service build --dest web --no-clean",

//Скорректировать уровень сложности (например, для тестирования = 2) в controllers/SiteController
    const emptyCellCount = 20;
