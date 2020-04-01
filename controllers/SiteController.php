<?php

namespace app\controllers;

use app\ext\Sudoku;
use app\models\Game;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->renderContent('');
    }

    public function actionGetNewGame()
    {
        Yii::$app->response->format = Yii::$app->response::FORMAT_JSON;

        Game::deleteAll();
        $sudoku = new Sudoku;

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

    public function actionSetNumber($x, $y, $number)
    {
        Yii::$app->response->format = Yii::$app->response::FORMAT_JSON;

        $models = Game::find()->all();
        $cells = [];

        foreach ($models as $model) {
            $cells[$model->y][$model->x] = $model->number;
        }

        $enabled = false;
        $finished = false;

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
