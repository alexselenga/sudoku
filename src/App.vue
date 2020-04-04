<template>
  <div id="app">
    <h1>Судоку</h1>
    <h2 v-if="finishedByUserName">Игра закончена игроком {{finishedByUserName}}</h2>
    <div class="game">
      <div class="side-bar">
        <button @click="onNewGame()">Новая игра</button>
        <br>
        Имя:
        <input id="user-name" type="text" v-model="userName">
        <br>
        Игроки:
        <ul>
          <li v-for="(userName, index) of userNames" :key="index">{{userName}}</li>
        </ul>
      </div>
      <div class="sudoku">
        <div class="sudoku__numbers">
          <div
              v-show="activeX !== null && !cells[activeY][activeX]"
              v-for="number of 9"
              :key="number"
              class="sudoku__number"
              @click="onSelectNumber(activeX, activeY, number)"
          >
            <div>{{number}}</div>
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
              <div>{{cells[y - 1][x - 1]}}</div>
            </div>
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
      backHost: 'http://sudoku/',
      wsHost: 'ws://sudoku:8089',
      cells: [],
      userName: 'Alex',
      activeX: null,
      activeY: null,
      finishedByUserName: null,
      userNames: [],
      socket: null
    }
  },

  async mounted () {
    this.loadCurrentGame()
    this.socket = new WebSocket(this.wsHost)

    this.socket.onopen = () => {
      this.socket.send(JSON.stringify({ type: 'setUserName', userName: this.userName }))
    }

    this.socket.onmessage = event => {
      const data = JSON.parse(event.data)

      switch (data.type) {
        case 'refresh':
          this.loadCurrentGame()
          this.activeX = null
          this.activeY = null
          this.finishedByUserName = null
          break
        case 'setCell':
          this.showCell(data)
          break
        case 'finished':
          this.finishedByUserName = data.userName
          break
        case 'refreshUserNames':
          this.userNames = data.userNames
      }
    }
  },

  watch: {
    userName: function (val) {
      this.socket.send(JSON.stringify({ type: 'setUserName', userName: val }))
    }
  },

  methods: {
    async loadCurrentGame () {
      const res = await fetch(this.backHost + 'site/get-current-game')
      this.cells = await res.json()
    },

    showCell ({ x, y, number }) {
      Vue.set(this.cells[y], x, number)
    },

    async onNewGame () {
      const res = await fetch(this.backHost + 'site/get-new-game')
      this.cells = await res.json()
      this.activeX = null
      this.activeY = null
      this.finishedByUserName = null
      this.socket.send('refresh')
    },

    onSelectCell (x, y) {
      this.activeX = x
      this.activeY = y
    },

    async onSelectNumber (x, y, number) {
      if (x === null || this.cells[y][x]) return

      const res = await fetch(this.backHost + 'site/set-number', {
        method: 'POST',
        body: JSON.stringify({ x, y, number }),
        headers: {
          'Content-Type': 'application/json;charset=utf-8'
        }
      })

      const data = await res.json()

      if (data.enabled) {
        this.socket.send(JSON.stringify({ type: 'setCell', x, y, number }))
        this.showCell({ x, y, number })
      }

      if (data.finished) {
        this.finishedByUserName = this.userName
        this.socket.send(JSON.stringify({ type: 'finished', userName: this.userName }))
      }
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
ul {
  list-style: none;
}
button {
  width: 160px;
  height: 30px;
  margin: 10px auto;
}
#user-name {
  margin: 10px auto;
}
.game {
  display:flex;
  justify-content: space-around;
  width: 1000px;
  margin: 40px auto;
}
.sudoku {
  display:flex;
  flex-direction: column;
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
  width: 70px;
  height: 70px;
  border: 1px solid #aad2e2;
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
  border-right: 4px solid #aad2e2;
}
.sudoku .cell_bb {
  border-bottom: 4px solid #aad2e2;
}
.sudoku .cell_selected {
  background-color: cyan;
}
.sudoku__numbers {
  display:flex;
  background-color: lightcyan;
  height: 70px;
  margin-bottom: 20px;
}
.sudoku__number {
  display:flex;
  justify-content: center;
  width: 70px;
  height: 70px;
  border: 1px solid #aad2e2;
}
.sudoku__number>div {
  display:flex;
  flex-direction: column;
  justify-content: center;
  font-size: 3em;
}
.sudoku__number:hover {
  background-color: cyan;
  cursor: pointer;
}
.side-bar {
  width: 250px;
}
</style>
