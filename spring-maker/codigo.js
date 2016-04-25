$(document).ready(function(){
  var token;

  login();

  $("#on").click(function(){
    execute_action(1,125);
  });
  $("#off").click(function(){
    execute_action(0,125);
  });
  $("#low").click(function(){
    execute_action(1,122);
  });
  $("#mid").click(function(){
    execute_action(1,123);
  });
  $("#high").click(function(){
    execute_action(1,124);
  });

  function login() {
    $.ajax({
      url: 'http://www.creaengine.com/v1/session',
      type: 'POST',
      dataType: 'json',
      crossDomain: true,
      async: false,
      data: "scopes=module-owner",
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Basic ZDc2MGQ2Mzg5YzE1M2FkZDRiYzMwMjFkNzI1ZjZhNmE6OTZhZjM3MTk4YzIxMDRmOTgyYzcwOGRhNGMzMzg3MzY=");
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        token = json.access_token;
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          alert("Error general");
        } else {
          if (jqXHR.responseJSON.code == 2)
            alert("Error de validacion de campos");
          else
            alert("Error general");
        }
      },
    });
  }

  function execute_action(value, action) {
    $.ajax({
      url: 'http://www.creaengine.com/v1/module/38f9f745ba7ddc142e245474b3317245/execute-action',
      type: 'PUT',
      dataType: 'json',
      crossDomain: true,
      async: false,
      data: "value=" + value + "&action=" + action,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        alert('Enviado')
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          alert("Error general");
        } else {
          if (jqXHR.responseJSON.code == 2)
            alert("Error de validacion de campos");
          else
            alert("Error general");
        }
      },
    });
  }
});
