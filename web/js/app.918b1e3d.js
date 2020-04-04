(function(e){function t(t){for(var r,u,i=t[0],c=t[1],o=t[2],f=0,d=[];f<i.length;f++)u=i[f],Object.prototype.hasOwnProperty.call(s,u)&&s[u]&&d.push(s[u][0]),s[u]=0;for(r in c)Object.prototype.hasOwnProperty.call(c,r)&&(e[r]=c[r]);l&&l(t);while(d.length)d.shift()();return a.push.apply(a,o||[]),n()}function n(){for(var e,t=0;t<a.length;t++){for(var n=a[t],r=!0,i=1;i<n.length;i++){var c=n[i];0!==s[c]&&(r=!1)}r&&(a.splice(t--,1),e=u(u.s=n[0]))}return e}var r={},s={app:0},a=[];function u(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,u),n.l=!0,n.exports}u.m=e,u.c=r,u.d=function(e,t,n){u.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},u.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},u.t=function(e,t){if(1&t&&(e=u(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(u.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)u.d(n,r,function(t){return e[t]}.bind(null,r));return n},u.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return u.d(t,"a",t),t},u.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},u.p="/";var i=window["webpackJsonp"]=window["webpackJsonp"]||[],c=i.push.bind(i);i.push=t,i=i.slice();for(var o=0;o<i.length;o++)t(i[o]);var l=c;a.push([0,"chunk-vendors"]),n()})({0:function(e,t,n){e.exports=n("56d7")},"034f":function(e,t,n){"use strict";var r=n("85ec"),s=n.n(r);s.a},"56d7":function(e,t,n){"use strict";n.r(t);n("e260"),n("e6cf"),n("cca6"),n("a79d");var r=n("2b0e"),s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{attrs:{id:"app"}},[n("h1",[e._v("Судоку")]),e.finishedByUserName?n("h2",[e._v("Игра закончена игроком "+e._s(e.finishedByUserName))]):e._e(),n("div",{staticClass:"game"},[n("div",{staticClass:"side-bar"},[n("button",{on:{click:function(t){return e.onNewGame()}}},[e._v("Новая игра")]),n("br"),e._v(" Имя: "),n("input",{directives:[{name:"model",rawName:"v-model",value:e.userName,expression:"userName"}],attrs:{id:"user-name",type:"text"},domProps:{value:e.userName},on:{input:function(t){t.target.composing||(e.userName=t.target.value)}}}),n("br"),e._v(" Игроки: "),n("ul",e._l(e.userNames,(function(t,r){return n("li",{key:r},[e._v(e._s(t))])})),0)]),n("div",{staticClass:"sudoku"},[n("div",{staticClass:"sudoku__numbers"},e._l(9,(function(t){return n("div",{directives:[{name:"show",rawName:"v-show",value:null!==e.activeX&&!e.cells[e.activeY][e.activeX],expression:"activeX !== null && !cells[activeY][activeX]"}],key:t,staticClass:"sudoku__number",on:{click:function(n){return e.onSelectNumber(e.activeX,e.activeY,t)}}},[n("div",[e._v(e._s(t))])])})),0),e.cells.length?n("div",{staticClass:"sudoku__table"},e._l(9,(function(t){return n("div",{key:t,staticClass:"sudoku__row"},e._l(9,(function(r){return n("div",{key:9*t+r,staticClass:"sudoku__cell",class:{cell_br:3===r||6===r,cell_bb:3===t||6===t,cell_selected:null!==e.activeX&&r===e.activeX+1&&t===e.activeY+1},on:{click:function(n){return e.onSelectCell(r-1,t-1)}}},[n("div",[e._v(e._s(e.cells[t-1][r-1]))])])})),0)})),0):e._e()])])])},a=[],u=(n("d3b7"),n("96cf"),n("1da1")),i={data:function(){return{backHost:"http://sudoku/",wsHost:"ws://sudoku:8089",cells:[],userName:"Alex",activeX:null,activeY:null,finishedByUserName:null,userNames:[],socket:null}},mounted:function(){var e=this;return Object(u["a"])(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.loadCurrentGame(),e.socket=new WebSocket(e.wsHost),e.socket.onopen=function(){e.socket.send(JSON.stringify({type:"setUserName",userName:e.userName}))},e.socket.onmessage=function(t){var n=JSON.parse(t.data);switch(n.type){case"refresh":e.loadCurrentGame(),e.activeX=null,e.activeY=null,e.finishedByUserName=null;break;case"setCell":e.showCell(n);break;case"finished":e.finishedByUserName=n.userName;break;case"refreshUserNames":e.userNames=n.userNames}};case 4:case"end":return t.stop()}}),t)})))()},watch:{userName:function(e){this.socket.send(JSON.stringify({type:"setUserName",userName:e}))}},methods:{loadCurrentGame:function(){var e=this;return Object(u["a"])(regeneratorRuntime.mark((function t(){var n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,fetch(e.backHost+"site/get-current-game");case 2:return n=t.sent,t.next=5,n.json();case 5:e.cells=t.sent;case 6:case"end":return t.stop()}}),t)})))()},showCell:function(e){var t=e.x,n=e.y,s=e.number;r["a"].set(this.cells[n],t,s)},onNewGame:function(){var e=this;return Object(u["a"])(regeneratorRuntime.mark((function t(){var n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,fetch(e.backHost+"site/get-new-game");case 2:return n=t.sent,t.next=5,n.json();case 5:e.cells=t.sent,e.activeX=null,e.activeY=null,e.finishedByUserName=null,e.socket.send("refresh");case 10:case"end":return t.stop()}}),t)})))()},onSelectCell:function(e,t){this.activeX=e,this.activeY=t},onSelectNumber:function(e,t,n){var r=this;return Object(u["a"])(regeneratorRuntime.mark((function s(){var a,u;return regeneratorRuntime.wrap((function(s){while(1)switch(s.prev=s.next){case 0:if(console.log(e,t,n),null!==e&&!r.cells[t][e]){s.next=3;break}return s.abrupt("return");case 3:return s.next=5,fetch(r.backHost+"site/set-number",{method:"POST",body:JSON.stringify({x:e,y:t,number:n}),headers:{"Content-Type":"application/json;charset=utf-8"}});case 5:return a=s.sent,s.next=8,a.json();case 8:u=s.sent,u.enabled&&(r.socket.send(JSON.stringify({type:"setCell",x:e,y:t,number:n})),r.showCell({x:e,y:t,number:n})),u.finished&&(r.finishedByUserName=r.userName,r.socket.send(JSON.stringify({type:"finished",userName:r.userName})));case 11:case"end":return s.stop()}}),s)})))()}}},c=i,o=(n("034f"),n("2877")),l=Object(o["a"])(c,s,a,!1,null,null,null),f=l.exports,d=n("8c4f");r["a"].use(d["a"]);var v=[],m=new d["a"]({routes:v}),p=m;r["a"].config.productionTip=!1,new r["a"]({router:p,render:function(e){return e(f)}}).$mount("#app")},"85ec":function(e,t,n){}});
//# sourceMappingURL=app.918b1e3d.js.map