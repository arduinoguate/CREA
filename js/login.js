$(document).ready(function(){
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
			        data: {user: textfield.val(), pass: pass.val()},
			        success: function(json) {
						if (json.code == 0){
			                logged(json.user, json.email);
			            }else{
			            	$("#output").removeClass(' alert alert-success');
		        			$("#output").addClass("alert alert-danger animated fadeInUp").html("Wrong credentials");
			            }
			        },
			        error: function(e, msj, xmlHttpReq) {
			        	$("#output").removeClass(' alert alert-success');
		        		$("#output").addClass("alert alert-danger animated fadeInUp").html("Oops, something went wrong. Please try again");
			        }
			    });

			} else {
		        //remove success mesage replaced with error message
		        $("#output").removeClass(' alert alert-success');
		        $("#output").addClass("alert alert-danger animated fadeInUp").html("sorry enter a password ");
		    }
	    } else {
	        //remove success mesage replaced with error message
	        $("#output").removeClass(' alert alert-success');
	        $("#output").addClass("alert alert-danger animated fadeInUp").html("sorry enter a username ");
	    }
	    //console.log(textfield.val());

	});
});

function logged(username, email){
	//$("body").scrollTo("#output");
    $("#output").addClass("alert alert-success animated fadeInUp").html("Welcome back " + "<span style='text-transform:uppercase'>" + username + "</span>");
    $("#output").removeClass(' alert-danger');
    $("input").css({
        "height":"0",
        "padding":"0",
        "margin":"0",
        "opacity":"0"
    });
    //change button text
    $('button[type="submit"]').html("continue")
        .removeClass("btn-info")
        .addClass("btn-default").click(function(){
        	location = 'index.php';
    	});

    //show avatar
    $(".avatar").css({
        "background-image": "url('http://robohash.org/"+email+"?gravatar=yes')"
    });
}

$("#passwordfield").on("keyup",function(){alert("hai");
    if($(this).val())
        $(".glyphicon-eye-open").show();
    else
        $(".glyphicon-eye-open").hide();
    });
$(".glyphicon-eye-open").mousedown(function(){
        $("#passwordfield").attr('type','text');
    }).mouseup(function(){
    	$("#passwordfield").attr('type','password');
    }).mouseout(function(){
    	$("#passwordfield").attr('type','password');
    });
