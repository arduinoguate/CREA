$(document).ready(function() {
  var textfield = $("input[name=user]");
  var pass = $("input[name=passwordfield]");
  $('button[type="submit"]').click(function(e) {
    e.preventDefault();
    //little validation just to check username
    if (textfield.val() != "") {
      if (pass.val() != "") {
        $.ajax({
          type: 'POST',
          url: 'post/login.php',
          data: {
            username: textfield.val(),
            password: pass.val()
          },
          success: function(json) {
						json = JSON.parse(json);
            if (json.code == 200) {
              logged(textfield.val(), json.email);
            } else {
              $("#output").removeClass(' alert alert-success');
              $("#output").addClass("alert alert-danger animated fadeInUp").html("Credenciales incorrectas");
            }
          },
          error: function(e, msj, xmlHttpReq) {
            $("#output").removeClass(' alert alert-success');
            $("#output").addClass("alert alert-danger animated fadeInUp").html("Oops, algo a salido mal. Intenta de nuevo");
          }
        });

      } else {
        //remove success mesage replaced with error message
        $("#output").removeClass(' alert alert-success');
        $("#output").addClass("alert alert-danger animated fadeInUp").html("Ingrese una contraseña ");
      }
    } else {
      //remove success mesage replaced with error message
      $("#output").removeClass(' alert alert-success');
      $("#output").addClass("alert alert-danger animated fadeInUp").html("Ingrese un usuario ");
    }
    //console.log(textfield.val());

  });
});

function logged(username) {
  //$("body").scrollTo("#output");
  $("#output").addClass("alert alert-success animated fadeInUp").html("Bienvenido " + "<span style='text-transform:uppercase'>" + username + "</span>");
  $("#output").removeClass(' alert-danger');
  $("input").css({
    "height": "0",
    "padding": "0",
    "margin": "0",
    "opacity": "0"
  });
  //change button text
  $('button[type="submit"]').html("continue")
    .removeClass("btn-info")
    .addClass("btn-default").click(function() {
      location = 'dashboard.php';
    });

  //show avatar
  $(".avatar").css({
    "background-image": "url('http://robohash.org/" + username + "?gravatar=yes')"
  });
}

$("#passwordfield").on("keyup", function() {
  alert("hai");
  if ($(this).val())
    $(".glyphicon-eye-open").show();
  else
    $(".glyphicon-eye-open").hide();
});
$(".glyphicon-eye-open").mousedown(function() {
  $("#passwordfield").attr('type', 'text');
}).mouseup(function() {
  $("#passwordfield").attr('type', 'password');
}).mouseout(function() {
  $("#passwordfield").attr('type', 'password');
});
