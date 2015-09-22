$(document).ready(function() {
  var token = $("#sess_token").val();
  var api = '/v1/';

  load_devices();
  load_types();
  load_module_types();
  load_response_types();

  $("#add_action_btn").click(function(e) {
    e.preventDefault();

    mod = $("#act_module").val();
    cmd = $("#act_cmd").val();
    type = $("#act_type").val();
    name = $("#act_name").val();
    response = $("#res_type").val();

    save_new_action(mod, cmd, type, name, response);

    $('#actionModal').modal("hide");
  });

  $("#add_module_btn").click(function(e) {
    e.preventDefault();

    type = $("#mod_type").val();
    name = $("#mod_name").val();

    save_new_module(name, type);

    $('#moduleModal').modal("hide");
  });

  $("#add_mod").click(function(e) {
    e.preventDefault();

    $('#moduleModal').modal("show");
  });

  $("#act_type").change(function() {
    console.log($("#act_type").val());
    console.log($("#act_type"));
    console.log($("#act_type").data("cmd"));
    command = update_command($("#act_type").find(':selected').data("cmd"), $("#act_cmda").val());
    $("#act_cmd").val(command);
  });

  $("#act_cmda").keyup(function() {
    command = update_command($("#act_type").find(':selected').data("cmd"), $("#act_cmda").val());
    $("#act_cmd").val(command);
  });

  $("#api_access").click(function(e) {
    e.preventDefault();

    user = $(this).data("user");
    $("#user-info").hide();
    $("#user-password").hide();

    load_api(user);
  });

  $("#user_profile").click(function(e) {
    e.preventDefault();

    user = $(this).data("user");
    $("#user-info").show();
    $("#user-password").hide();

    load_user(user);
  });

  $("#user_password").click(function(e) {
    e.preventDefault();

    user = $(this).data("user");
    $("#user-password").show();
    $("#user-info").hide();

    load_password();
  });

  $("#user-edit-form input").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function($form, event, errors) {
      // additional error messages or events
    },
    submitSuccess: function($form, event) {
      event.preventDefault(); // prevent default submit behaviour
      // get values from FORM
      var nombre = $("input#eu-nombre").val();
      var apellido = $("input#eu-apellido").val();
      var email = $("input#eu-email").val();
      var user = $("#sess_user").val();
      $.ajax({
        url: api + "user/" + user + '/update',
        type: 'PUT',
        dataType: 'json',
        crossDomain: true,
        async: false,
        data: "nombre=" + nombre + "&apellido=" + apellido + "&email=" + email,
        beforeSend: function(xhr) {
          xhr.setRequestHeader("Authorization", "Bearer " + token);
        },
        complete: function(resp) {
          json = resp.responseJSON;
          console.log(json);
          load_user(user);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          var arr = ["202", "422", "200"];
          if (!inArray(jqXHR.status, arr)) {
            window.location = "logout.php";
          } else {
            if (jqXHR.status == "200") {
              show_alert("Changes saved successfully");
            } else
            if (jqXHR.responseJSON.code == 2)
              show_alert("Fields validation Error");
            else
              show_alert("General Error");
          }
        },
      });
    },
    filter: function() {
      return $(this).is(":visible");
    },
  });

  $("#password-edit-form input").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function($form, event, errors) {
      // additional error messages or events
    },
    submitSuccess: function($form, event) {
      event.preventDefault(); // prevent default submit behaviour
      // get values from FORM
      var password = $("input#cp-pass").val();
      var pass_conf = $("input#cp-pass-confirm").val();
      if (password != pass_conf){
        show_alert("Passwords don't match");
      }else
        $.ajax({
          url: api + "user/" + user + '/update',
          type: 'PUT',
          dataType: 'json',
          crossDomain: true,
          async: false,
          data: "password=" + password,
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + token);
          },
          complete: function(resp) {
            json = resp.responseJSON;
            console.log(json);
            load_user(user);
          },
          error: function(jqXHR, textStatus, errorThrown) {
            var arr = ["202", "422", "200"];
            if (!inArray(jqXHR.status, arr)) {
              window.location = "logout.php";
            } else {
              if (jqXHR.status == "200") {
                show_alert("Changes saved successfully");
              } else
              if (jqXHR.responseJSON.code == 2)
                show_alert("Fields validation Error");
              else
                show_alert("General Error");
            }
          },
        });
    },
    filter: function() {
      return $(this).is(":visible");
    },
  });

  function update_command(cmd, value) {
    if (cmd == '') {
      return "NA:" + value;
    } else {
      return cmd + ":" + value;
    }
  }


  function load_user(user) {
    //LOAD API ACCESS KEYS
    $.ajax({
      url: api + "user/" + user,
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        var info = "";
        console.log(json.usuarios[0]);
        if (json.http_code == 200) {
          info = '<div class="row"><div class="col-md-12"><h2>Edit Information</h2></div>';
          info += '<div class="col-md-12">You can edit your information selecting the "edit" option.<hr/></div>';
          info += '</div>';
          $("#eu-nombre").val(json.usuarios[0].nombre);
          $("#eu-apellido").val(json.usuarios[0].apellido);
          $("#eu-email").val(json.usuarios[0].email);
        } else
          console.log("test");
        $('#dashboard').html(info);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else
          $('#devices').append('<li class="list-group-item"><span class="glyphicon glyphicon-remove text-primary"></span>No available information</li>');
      },
    });
  }

  function load_password() {
    info = '<div class="row"><div class="col-md-12"><h2>Change Password</h2></div>';
    info += '<div class="col-md-12">You can change your password here.<hr/></div>';
    info += '</div>';
    $('#dashboard').html(info);
  }

  function load_api(user) {
    //LOAD API ACCESS KEYS
    $.ajax({
      url: api + "user/" + user + "/api",
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        var info = '<div class="row"><div class="col-md-12"><h2>API Credentials</h2></div>';
        info += '<div class="col-md-12">The following access tokens can be used to access CREA from your registered devices.<hr/></div>';
        info += '<div class="col-md-12">API Key <b>' + json.credenciales.client_id + '</b><br/>';
        info += 'API Secret <b>' + json.credenciales.client_secret + '</b><br/>';
        info += 'Authorization <b>' + btoa(json.credenciales.client_id + ":" + json.credenciales.client_secret) + '</b></div>';
        info += '</div>';
        $('#dashboard').html(info);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else
          $('#devices').append('<li class="list-group-item"><span class="glyphicon glyphicon-remove text-primary"></span>No devices</li>');
      },
    });
  }

  function load_devices() {
    //LOAD DEVICES
    $("#user-info").hide();
    $("#user-password").hide();
    $("#devices").html("");
    $.ajax({
      url: api + "module/",
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        if (json.http_code == 200)
          $.each(json, function(i, item) {
            $.each(item, function(j, modulo) {
              var item = '<li class="list-group-item"><span class="glyphicon glyphicon-hdd text-primary"></span><a href="#" class="modulo" id="' + modulo.id + '">' + modulo.nombre + '</a></li>';
              $("#devices").append(item);

            });
            $(".modulo").click(function(e) {
              e.preventDefault();

              get_modulo($(this).attr("id"));
              $("#user-info").hide();
              $("#user-password").hide();
            });
          });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else
          $('#devices').append('<li class="list-group-item"><span class="glyphicon glyphicon-remove text-primary"></span>No devices</li>');
      },
    });
  }

  function load_types() {
    $.ajax({
      url: api + "action_type",
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      complete: function(resp) {
        json = resp.responseJSON;
        $.each(json.tipos, function(i, item) {
          var item = '<option value="' + item.idtipo_action + '" data-cmd="' + item.comando + '">' + item.nombre + '</option>';
          $("#act_type").append(item);
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else
          $("#act_type").append("<option>No types</option>");
      },
    });
  }

  function load_response_types() {
    $.ajax({
      url: api + "action_response",
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      complete: function(resp) {
        json = resp.responseJSON;
        $.each(json.respuestas, function(i, item) {
          var item = '<option value="' + item.id + '">' + item.nombre_en + '</option>';
          $("#res_type").append(item);
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else
          $("#res_type").append("<option>No types</option>");
      },
    });
  }

  function load_module_types() {
    $.ajax({
      url: api + "module_type",
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      complete: function(resp) {
        json = resp.responseJSON;
        $.each(json.modulos, function(i, item) {
          var item = '<option value="' + item.idtipo_modulo + '">' + item.nombre + '</option>';
          $("#mod_type").append(item);
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else
          $("#act_type").append("<option>No types</option>");
      },
    });
  }

  function get_modulo(mod) {
    //LOAD DEVICES
    $.ajax({
      url: api + "module/" + mod,
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        $.each(json.modulos, function(i, item) {
          var info = '<div class="row"><div class="col-md-8">Nombre <b>' + item.nombre + '</b><br/>';
          info += 'Device ID <b>' + item.id + '</b><br/>';
          info += 'Device <b>' + item.tipo_modulo.nombre + '</b><br/>';
          info += 'Status <b>' + item.estado + '</b><br/>Last response <b>' + item.last_response + '</b><br/>';
          info += '<small>Updated at: <b>' + item.updated_at + '</b></small></div>';
          info += '<div class="col-md-4"><div class="col-md-12  text-right"><button data-mod="' + item.id + '" class="btn-success btn-lg add_action">Add Action</button></div>';
          info += '<div class="col-md-12  text-right"><small><a href="'+item.tipo_modulo.url_libreria+'" target="_blank" class="">Download Library</a></small></div>';
          info += '<div class="col-md-12  text-right"><small><a href="#" class="eliminar_mod text-danger" id="' + item.id + '">Delete</a></small></div></div>';
          info += '</div><hr/><p>Actions</p>';
          info += '<div class="row" id="actions_int"></div>';

          $('#dashboard').html(info);

          $(".add_action").click(function(e) {
            e.preventDefault();
            $('#actionModal').modal("show");
            $('#act_module').val($(this).data("mod"));
          });

          $(".eliminar_mod").click(function(e) {
            e.preventDefault();
            id = $(this).attr("id");
            show_confirm_delete_module("Do you wish to delete this module?", id);
          });

          $.ajax({
            url: api + "module/" + mod + '/actions',
            type: 'GET',
            dataType: 'json',
            crossDomain: true,
            async: false,
            beforeSend: function(xhr) {
              xhr.setRequestHeader("Authorization", "Bearer " + token);
            },
            complete: function(resp) {
              json = resp.responseJSON;
              console.log(json);
              $.each(json.acciones, function(i, item) {
                info = '<div class="col-md-4"><div class="col-md-11 alert alert-success">Name: <b>' + item.nombre + '</b><br/>';
                info += 'Type: <b>' + item.tipo_accion.nombre + '</b><br/>';
                info += 'Command: <b>' + item.comando + '</b><br/>';
                info += 'Last instruction: <b>' + item.ultimo_valor + '</b><br/><hr/>';
                info += '<div class="form-group">';
                info += '<label for="' + item.id + '">Send Instruction</label>';
                info += '<input type="text" class="form-control" data-modulo="' + item.modulo_id.id + '" id="' + item.id + '" placeholder="Instruction">';
                info += '</div>';
                info += '<div class="form-group">';
                info += '<button class="col-md-8 btn btn-primary btn-sm send_action" data-act="' + item.id + '">Send</button><button class="btn btn-danger btn-sm delete_action" data-act="' + item.id + '"><span class="glyphicon glyphicon-trash"></span></button>';
                info += '</div>';
                info += '<small>Updated at: <b>' + item.updated_at + '</b></small></div></div>';


                $('#actions_int').append(info);

              });

              $(".send_action").click(function(e) {
                e.preventDefault();
                id = $(this).data("act");
                mod = $("#" + id).data("modulo");
                value = $("#" + id).val();

                execute_action(mod, value, id);
              });

              $(".delete_action").click(function(e) {
                e.preventDefault();
                id = $(this).data("act");
                mod = $("#" + id).data("modulo");
                show_confirm_delete_action("Do you wish to delete this action?", mod, id);

              });

            },
            error: function(jqXHR, textStatus, errorThrown) {
              if (jqXHR.status != '422') {
                window.location = "logout.php";
              } else
                $('#actions_int').html('<div class="col-md-12"><h2>No available actions</h2></div>');
            },
          });


        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        window.location = "logout.php";
        console.log(textStatus);
      },
    });
  }

  function delete_action(mod, action) {
    $.ajax({
      url: api + "module/" + mod + '/actions',
      type: 'DELETE',
      dataType: 'json',
      crossDomain: true,
      async: false,
      data: "action=" + action,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        get_modulo(mod);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else {
          if (jqXHR.responseJSON.code == 2)
            show_alert("Field validation error");
          else
            show_alert("General Error");
        }
      },
    });
  }

  function delete_module(mod){
    $.ajax({
      url: api + "module/" + mod,
      type: 'DELETE',
      dataType: 'json',
      crossDomain: true,
      async: false,
      data: "",
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        load_devices();
        $('#dashboard').html("");
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else {
          if (jqXHR.responseJSON.code == 2)
            show_alert("Field validation error");
          else
            show_alert("General Error");
        }
      },
    });
  }

  function execute_action(mod, value, action) {
    $.ajax({
      url: api + "module/" + mod + '/execute-action',
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
        get_modulo(mod);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else {
          if (jqXHR.responseJSON.code == 2)
            show_alert("Field validation error");
          else
            show_alert("General Error");
        }
      },
    });
  }

  function save_new_action(mod, cmd, type, name, response) {
    $.ajax({
      url: api + "module/" + mod + '/register-action',
      type: 'PUT',
      dataType: 'json',
      crossDomain: true,
      async: false,
      data: "tipo-accion=" + type + "&comando=" + cmd + "&input=1&nombre=" + name + "&tipo_respuesta=" + response,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        get_modulo(mod);
        $("#act_module").val("");
        $("#act_cmd").val("");
        $("#act_cmda").val("");
        $("#act_name").val("");
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else {
          if (jqXHR.responseJSON.code == 2)
            show_alert("Field validation error");
          else
            show_alert("General Error");
        }
      },
    });
  }

  function save_new_module(name, type) {
    $.ajax({
      url: api + 'module/',
      type: 'POST',
      dataType: 'json',
      crossDomain: true,
      async: false,
      data: "nombre=" + name + "&tipo=" + type,
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
      },
      complete: function(resp) {
        json = resp.responseJSON;
        console.log(json);
        load_devices();
        $("#mod_name").val("");
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else {
          if (jqXHR.responseJSON.code == 2)
            show_alert("Field validation error");
          else
            show_alert("General Error");
        }
      },
    });
  }

  function show_alert(message) {
    //pending to implement
    $("#mod_alert_msg").html(message);
    $('#alertModal').modal("show");
  }

  function show_confirm_delete_action(message, mod, id) {
    //pending to implement
    $("#mod_delete_msg").html(message);
    $('#deleteModal').modal("show");
    $("#modal_yes_btn").unbind("click");
    $("#modal_yes_btn").bind("click", function(){
      delete_action(mod, id);
    });
  }

  function show_confirm_delete_module(message, mod) {
    //pending to implement
    $("#mod_delete_msg").html(message);
    $('#deleteModal').modal("show");
    $("#modal_yes_btn").unbind("click");
    $("#modal_yes_btn").bind("click", function(){
      delete_module(mod);
    });
  }

});
