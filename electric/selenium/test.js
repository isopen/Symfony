require('chromedriver');
const wd = require('selenium-webdriver');
const host = "http://localhost";
const client = new wd.Builder()
      .forBrowser('chrome')
      .build();

client.manage().window().setSize(1024, 768);

client.get(host).then(function() {
  for(var k = 0; k < 100; k ++) {
    for(var i = 1; i < 5; i++) {
      for(var j = 1; j < 5; j++) {
        client.findElement({css: '#field_game .row:nth-child(' + i + ') .field_lamp:nth-child(' + j + ')'}).click();
        client.findElement({css: '#field_game .row:nth-child(' + i + ') .field_lamp:nth-child(' + j + ')'}).click();
      }
    }
    client.findElement({css: '#field_game #new_game_btn'}).click();
    client.findElement({css: '#field_game #best_results_btn'}).click();
  }
});