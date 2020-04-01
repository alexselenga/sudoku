<?php

namespace app\ext;

class Sudoku
{
    public $cells = [];

    const VALUES = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9];

    protected $funcs = [
        'transposing', 'transposing', 'transposing',
        'randomSwapRow', 'randomSwapRow', 'randomSwapRow',
        'randomSwapAreaRows',
        'randomSwapColumn', 'randomSwapColumn', 'randomSwapColumn',
        'randomSwapAreaColumns',
    ];

    public function __construct($cells = null, $emptyCellCount = 30, $rndCount = 20) {
        if ($cells) {
            $this->cells = $cells;
        } else {
            $this->generateCells($emptyCellCount, $rndCount);
        }
    }

    public function validateCell($x, $y) {
        $vr = $this->validateRow($y);
        $vc = $this->validateColumn($x);
        $va = $this->validateArea($x, $y);

        if (is_array($vr) && is_array($vc) && is_array($va)) {
            return array_diff_key(static::VALUES, $vr + $vc + $va);
        }

        return false;
    }

    public function isFinished() {
        for ($y = 0; $y < 9; $y++) {
            for ($x = 0; $x < 9; $x++) {
                if ($this->cells[$y][$x]) continue;
                $possibleValues = $this->validateCell($x, $y);
                if (!is_array($possibleValues)) return true;
                if (count($possibleValues)) return false;
            }
        }

        return true;
    }

    public function validateAll() {
        for ($y = 0; $y < 9; $y++) {
            for ($x = 0; $x < 9; $x++) {
                if (!is_array($this->validateCell($x, $y))) return false;
            }
        }

        return true;
    }

    protected function validateRow($y) {
        $values = [];

        for ($x = 0; $x < 9; $x++) {
            $value = $this->cells[$y][$x];
            if (!$value) continue;
            if ($values[$value]) return false;
            $values[$value] = $value;
        }

        return $values;
    }

    protected function validateColumn($x) {
        $values = [];

        for ($y = 0; $y < 9; $y++) {
            $value = $this->cells[$y][$x];
            if (!$value) continue;
            if ($values[$value]) return false;
            $values[$value] = $value;
        }

        return $values;
    }

    protected function validateArea($x, $y) {
        $values = [];

        $startX = intdiv($x, 3) * 3;
        $startY = intdiv($y, 3) * 3;

        for ($y = $startY; $y < $startY + 3; $y++) {
            for ($x = $startX; $x < $startX + 3; $x++) {
                $value = $this->cells[$y][$x];
                if (!$value) continue;
                if ($values[$value]) return false;
                $values[$value] = $value;
            }
        }

        return $values;
    }

    protected function generateCells($emptyCellCount = 10, $rndCount = 20) {
        for ($y = 0; $y < 9; $y++) {
            for ($x = 0; $x < 9; $x++) {
                $this->cells[$y][$x] = (intdiv($y, 3) + $y % 3 * 3 + $x) % 9 + 1;
            }
        }

        for ($i = 0; $i < $rndCount; $i++) {
            $func = $this->funcs[random_int(0, count($this->funcs) - 1)];
            $this->$func();
        }

        for ($i = 0; $i < $emptyCellCount; $i++) {
            do {
                $x = random_int(0, 8);
                $y = random_int(0, 8);
            } while (!$this->cells[$y][$x]);

            $this->cells[$y][$x] = null;
        }
    }

    protected function transposing() {
        for ($y = 0; $y < 9; $y++) {
            for ($x = $y; $x < 9; $x++) {
                list($this->cells[$y][$x], $this->cells[$x][$y]) = [$this->cells[$x][$y], $this->cells[$y][$x]];
            }
        }
    }

    protected function randomSwapRow() {
        $y1 = random_int(0, 8);
        $startY2 = intdiv($y1, 3) * 3;

        do {
            $y2 = random_int($startY2, $startY2 + 2);
        } while ($y1 == $y2);

        $this->swapRow($y1, $y2);
    }

    protected function randomSwapAreaRows() {
        $y1 = random_int(0, 2) * 3;

        do {
            $y2 = random_int(0, 2) * 3;
        } while ($y1 == $y2);

        for ($y = 0; $y < 3; $y++) {
            $this->swapRow($y1++, $y2++);
        }
    }

    protected function randomSwapColumn() {
        $this->transposing();
        $this->randomSwapRow();
        $this->transposing();
    }

    protected function randomSwapAreaColumns() {
        $this->transposing();
        $this->randomSwapAreaRows();
        $this->transposing();
    }

    protected function swapRow($y1, $y2) {
        for ($x = 0; $x < 9; $x++) {
            list($this->cells[$y1][$x], $this->cells[$y2][$x]) = [$this->cells[$y2][$x], $this->cells[$y1][$x]];
        }
    }
}