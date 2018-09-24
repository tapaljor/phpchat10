from = 11;
$(document).ready( function() {
	
	$(window).scroll( function() {

		if ($(window).scrollTop() >= $(document).height() - $(window).height()) {

			$.ajax ({
				type: "POST",
				url: "loadmore.php",
				data: { from: from },
				beforeSend: function() {
					$(".loading").load("loading.html");
				},
				success: function(data) {
					$(".loading").hide();//hiding loading once content loaded
					$("#loadmore").append(data);
					from += 4;
				},
				error: function (request,error) {
					// This callback function will trigger on unsuccessful action                
					alert('Apologies! something went wrong, please try again');
				}
			});
		}
	});
	setInterval( function() {
		$("html").load("activitytime.php");
	}, 1000*600);
	//1000 is equal to 1 sec
});

function loadmore() {

	$.ajax ({
		type: "POST",
		url: "loadmore.php",
		data: { from: from },
		beforeSend: function() {
			$(".loading").load("loading.html");
		},
		success: function(data) {
			$(".loading").hide();//hiding loading once content loaded
			$("#loadmore").append(data);
			from += 4;
		},
	});
}

function check_username(username) {

	$.ajax ({
		type: "POST",
		url: "check_username.php",
		data: { username: username },

		success: function(feedback) {

			var _js = jQuery.parseJSON(feedback);
			$("#file_error").html(_js.message);
			$("#file_error").attr("class", "");
			if ( _js.code == 1) {
				$("#file_error").addClass("green");
			} else {
				$("#file_error").addClass("red");
			}
		}
	});
}
function change_pass() {

	$("#password1, #password2").show();
}
function compare_password(password, re_password) {

	var password = password.value;
	var re_password = re_password.value;

	if(password != re_password) {
		$("#file_error").html('Password mismatched');
		$("#file_error").attr("class", "");
		$("#file_error").addClass("red");
	} else {
		$("#file_error").html('Password matched');
		$("#file_error").attr("class", "");
		$("#file_error").addClass("green");
	}
}
function search_ads(obj) {

	var name1 = obj.value;

	$.ajax ({
		type: "POST",
		url: "search_ads.php",
		data: { name: name1 },

		success: function(data) {
			$("#loadmoread").html(data);
		}
	});
	return false;
}
function search_country(obj) {

	var ming1 = obj.value;

	$.ajax ({
		type: "POST",
		url: "search_country.php",
		data: { ming: ming1 },
		success: function(data) {
			$("#country").html(data);
		}
	});
	return false;
}

function morefiles(id1) {

	$.ajax ({
		type: "POST",
		url: "morefiles.php",
		data: { id: id1 },
		success:function(data) {
			$("#morefiles").html(data);
		}
	});
	return false;
}

function likedislike(loginidhash1, likeridhash1, type1) {

	$.ajax ({
		type: "POST",
		url: "likedislike.php",
		data: { loginidhash: loginidhash1, likeridhash: likeridhash1, type: type1 },

		success: function(data) {
			$("#likedislike").html(data);
		}
	});
}
function getlistregion(code) {

	$.ajax ({
		type: "POST",
		url: "getlistregion.php",
		data: { code: code },
		beforeSend: function(data) {
			$("#listregion").load("loading.html");
		},	
		success:function(data) {
			$("#listregion").html(data);
		}
	});
	return false;
}
function readtc() {
	
	$("#tc").show();
}

