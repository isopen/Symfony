'use strict';

var Protection = function(row = 5, column = 5) {
  this.init(row, column);
}

Protection.prototype.get_random_int = function(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

Protection.prototype.init = function(row = 5, column = 5) {
  this.field_game = $("#field_game"); // сетка
  this.row = row; // количество строк
  this.column = column; // количество колонок
  this.column_count = this.row * this.column; // количество ячеек в сетке
  this.count = 0; // количество ходов
  this.chance = this.get_random_int(1, this.column_count); // шанс 1 к 25
  this.vin = false; // выйграл/не выйграл
  this.protection = {}; // значения сетки
  for(var i = 0; i < this.row; i++) {
    var k = {};
    for(var j = 0; j < this.column; j++) {
      k[j] = 0;  
    }
    this.protection[i] = k;
  }
}

Protection.prototype.set_point = function(x, y) {
  if(this.protection[x][y] == 0) {
    this.protection[x][y] = 1;
  }else {
    this.protection[x][y] = 0;
  }
}

Protection.prototype.set_points = function(points) {
  for (var i in points) {
    if(i == -1) {
      this.protection[points[i].x][points[i].y] = 0;
    }else {
      this.set_point(points[i].x, points[i].y);
    }
  }
}

Protection.prototype.set_back_count = function(count) {
  this.back_count = count;
}

Protection.prototype.build_view = function() {
  var c_red = 0;
  for(var i = 0; i < this.row; i++) {
    for(var j = 0; j < this.column; j++) {
      var el_view = this.field_game.children("div:nth-child(" + (i + 1)  + ")").children("div:nth-child(" + (j + 1) + ")").children("div");
      if(this.protection[j][i] == 1) {
        el_view.removeClass("lamp_out");
        el_view.addClass("lamp_up");
        c_red++;
      }else {
        el_view.removeClass("lamp_up");    
        el_view.addClass("lamp_out");
      }
    }
  }
  this.field_game.children(".step_box").children(".step_counter").text(this.count);
  if(((this.count % this.column_count) == 0) && this.count != 0) {
    this.chance = this.get_random_int(this.count, this.count + this.column_count);
  } 
  if(c_red == this.column_count) {
    this.vin = true;
  }
}

Protection.prototype.reload_game = function() {
  this.set_back_count(this.count);
  this.init();
  this.build_view();
}
