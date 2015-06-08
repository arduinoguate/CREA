$(document).ready(function(){
  var api = '/v1/';

  load_module_libs();

  function load_module_libs() {
    var plat = getParameterByName('plat');

    $.ajax({
      url: api + "module_type?q=" + plat,
      type: 'GET',
      dataType: 'json',
      crossDomain: true,
      async: false,
      complete: function(resp) {
        json = resp.responseJSON;
        $.each(json.modulos, function(i, item) {
          var item = '<option value="' + item.idtipo_modulo + '">' + item.nombre + '</option>';
          $("#files").append(item);
        });
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status != '422') {
          window.location = "logout.php";
        } else
          $("#files").html("<h1><center>No hay tipos</center></h1>");
      },
    });
  }

  function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
      results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }
});
