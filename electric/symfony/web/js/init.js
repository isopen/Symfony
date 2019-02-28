'use strict';

$().ready(function() {
  var prot = new Protection();
  $("#field_game .field_lamp").click(function() {
    var x = $(this).index(),
        y = $(this).parent().index(),
        point = {"x": x, "y": y};
    prot.set_point(x, y);
    prot.count++;
    $.post("/calculation", {point: point, count: prot.count, chance: prot.chance}, 
    function(data) {
      if(data.status) {
        prot.set_points(data.points);
        prot.build_view();
        if(prot.vin) {
          prot.reload_game();
          $("#result_modal").modal('show');
        }
      }else {
        prot.count--;
        alert(data.message);
      }
    });
  });
  $("#save_result_modal").click(function() {
      var name = $("#name_result_modal").val();
      if(name != "") {
        $.post("/setresult", {name: name, count: prot.back_count},
        function(data) {
          if(data.status) {
            $("#result_modal").modal('hide');
          }else {
            $("#name_result_modal").addClass("valid_result_modal");
          }
        });
      }else {
        $("#name_result_modal").addClass("valid_result_modal");
      }
  });
  $("#new_game_btn").click(function() {
      prot.reload_game();
  });
  $("#best_results_btn").click(function() {
      $.post("app_dev.php/bestresults",
      function(data) {
        $("#best_results_game").html(data);
      });
  });
  $('#result_modal').on('hide.bs.modal', function (e) {
    $("#name_result_modal").removeClass("valid_result_modal");
  });
});