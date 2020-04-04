<?php

namespace app\controllers;

use app\ext\Sudoku;
use app\models\Game;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    const emptyCellCount = 20;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        if (Yii::$app->controller->action->id != 'index') {
            Yii::$app->request->parsers = ['application/json' => 'yii\web\JsonParser'];
            Yii::$app->response->format = Yii::$app->response::FORMAT_JSON;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->renderContent('');
    }

    public function actionGetNewGame()
    {
        Game::deleteAll();
        $sudoku = new Sudoku(null, static::emptyCellCount);

        for ($y = 0; $y < 9; $y++) {
            for ($x = 0; $x < 9; $x++) {
                $model = new Game;
                $model->x = $x;
                $model->y = $y;
                $model->number = $sudoku->cells[$y][$x];
                $model->save();
            }
        }

        return $sudoku->cells;
    }

    public function actionGetCurrentGame() {
        $models = Game::find()->all();
        $cells = [];

        foreach ($models as $model) {
            $cells[$model->y][$model->x] = $model->number;
        }

        return $cells;
    }

    public function actionSetNumber()
    {
        $post = Yii::$app->request->post();
        $x = $post['x'];
        $y = $post['y'];
        $number = $post['number'];

        $enabled = false;
        $finished = false;
        $models = Game::find()->all();
        $cells = [];

        foreach ($models as $model) {
            $cells[$model->y][$model->x] = $model->number;
        }

        if (!$cells[$y][$x]) {
            $sudoku = new Sudoku($cells);
            $possibleValues = $sudoku->validateCell($x, $y);
            $enabled = in_array($number, $possibleValues);

            if ($enabled) {
                $model = Game::find()->where(['x' => $x, 'y' => $y])->one();

                if ($model) {
                    $model->number = $number;
                    $model->save();

                    $sudoku->cells[$y][$x] = $number;
                    $finished = $sudoku->isFinished();
                }
            }
        }

        return [
            'enabled' => $enabled,
            'finished' => $finished,
        ];
    }
}
