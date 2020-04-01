<template>
  <div id="app">
    <h1>Судоку</h1>
    <h2 v-if="finished">Игра закончена</h2>
    <div class="sudoku">
      <div class="sudoku__numbers">
        <div
            v-show="activeX !== null"
            v-for="n of 9"
            :key="n"
            class="sudoku__number"
            @click="onSelectNumber(n)"
        >
          <div>{{n}}</div>
        </div>
      </div>
      <div v-if="cells.length" class="sudoku__table">
        <div v-for="y of 9" :key="y" class="sudoku__row">
          <div
              v-for="x of 9"
              :key="y * 9 + x"
              class="sudoku__cell"
              :class="{
                'cell_br': x===3 || x===6,
                'cell_bb': y===3 || y===6,
                'cell_selected': activeX !== null && x === activeX + 1 && y === activeY + 1
              }"
              @click="onSelectCell(x - 1, y - 1)"
          >
            <div>{{cells[y-1][x-1]}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'

export default {
  data () {
    return {
      cells: [],
      activeX: null,
      activeY: null,
      finished: false
    }
  },
  async mounted () {
    const res = await fetch('http://sudoku/site/get-new-game')
    this.cells = await res.json()
  },
  methods: {
    onSelectCell (x, y) {
      this.activeX = x
      this.activeY = y
    },
    async onSelectNumber (number) {
      if (this.activeX === null || this.cells[this.activeY][this.activeX]) return

      const res = await fetch('http://sudoku/site/set-number?x=' + this.activeX + '&y=' + this.activeY + '&number=' + number, {
        method: 'GET'
        // credentials: 'include',
        // body: JSON.stringify({
        //   x: this.activeX,
        //   y: this.activeY,
        //   number
        // }),
        // headers: {
        //   'Content-Type': 'application/json;charset=utf-8'
        // }
      })

      const data = await res.json()

      if (data.enabled) {
        Vue.set(this.cells[this.activeY], this.activeX, number)
        this.activeX = null
        this.activeY = null
      }

      this.finished = data.finished
    }
  }
}
</script>

<style>
*, *:before, *.after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
h1 {
  text-align: center;
  font-size: 3em;
  color:darkcyan;
}
h2 {
  text-align: center;
  font-size: 3em;
  color:red;
}
.sudoku {
  display:flex;
  justify-content: space-around;
  margin: 40px auto;
  width: 1070px;
}
.sudoku__table {
  display:flex;
  flex-direction: column;
}
.sudoku__row {
  display:flex;
  justify-content: center;
}
.sudoku__cell {
  display:flex;
  justify-content: center;
  width: 80px;
  height: 80px;
  border: 1px solid #4e555b;
}
.sudoku__cell>div {
  display:flex;
  flex-direction: column;
  justify-content: center;
  font-size: 4em;
}
.sudoku__cell:hover {
  background-color: lightcyan;
  cursor: pointer;
}
.sudoku .cell_br {
  border-right: 3px solid #4e555b;
}
.sudoku .cell_bb {
  border-bottom: 3px solid #4e555b;
}
.sudoku .cell_selected {
  background-color: cyan;
}
.sudoku__numbers {
  display:flex;
  flex-direction: column;
  background-color: lightcyan;
  width: 80px;
  border: 1px solid #4e555b;
}
.sudoku__number {
  display:flex;
  justify-content: center;
  width: 80px;
  height: 80px;
  border: 1px solid #4e555b;
}
.sudoku__number>div {
  display:flex;
  flex-direction: column;
  justify-content: center;
  font-size: 4em;
}
.sudoku__number:hover {
  background-color: cyan;
  cursor: pointer;
}

</style>
