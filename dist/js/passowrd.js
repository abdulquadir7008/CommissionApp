
var passwordregex6digits = new RegExp("^(?=.{10,})");
var passwordregexLowercase = new RegExp("^(?=.*[a-z])");
var passwordregexUppercase = new RegExp("^(?=.*[A-Z])");
var passwordregexNumber = new RegExp("^(?=.*[0-9])");
var passwordRegexSpecial = new RegExp("^(?=.*[!@#$%^&*])");
var passwordRegexAll = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{10,})");

$(".pword").on('keyup',function () {
	var thepassword = $(".pword").val();	
	checkpassword(thepassword);
});

var checkpassword = function(thisPassword) {
	console.log(thisPassword);
	if (passwordregex6digits.test(thisPassword)) {
		$(".passfailSix").html('<span class="pass">Pass</span>');
	} else {
		$(".passfailSix").html('<span class="fail">Fail</span>');
	}

	if (passwordregexLowercase.test(thisPassword)) {
		$(".passfailLower").html('<span class="pass">Pass</span>');
	} else {
		$(".passfailLower").html('<span class="fail">Fail</span>');
	}
	
	if (passwordregexUppercase.test(thisPassword)) {
		$(".passfailUpper").html('<span class="pass">Pass</span>');
	} else {
		$(".passfailUpper").html('<span class="fail">Fail</span>');
	}

	if (passwordregexNumber.test(thisPassword)) {
		$(".passfailNum").html('<span class="pass">Pass</span>');
	} else {
		$(".passfailNum").html('<span class="fail">Fail</span>');
	}
	
	if (passwordRegexSpecial.test(thisPassword)) {
		$(".passfailSpecial").html('<span class="pass">Pass</span>');
	} else {
		$(".passfailSpecial").html('<span class="fail">Fail</span>');
	}
	if (passwordRegexAll.test(thisPassword)) {
		$(".passfailAll").html('<span class="pass">Pass</span>');
		$(".checkmark").show();
		$(":submit").attr("disabled", false);
		$(".regbtn").show();
		$(".visit").hide();
	} else {
		$(".passfailAll").html('<span class="fail">Fail</span>');
		$(".checkmark").hide();
		$(".regbtn").attr("disabled", true);
		$(".stloginBtn").attr("disabled", false);
		$(".stloginBtn").click(function() {
        $(".tooltip_og").hide();
     });
		$(".regbtn").hide();
		$(".visit").show();
		$(".visit").click(function() {
        $(".tooltip_og").show();
     });
		
	}
};

$(".pword").on('keyup', function() {
	var thepassword = $(".pword").val();	
	checkpassword(thepassword);
	$(".tooltip_og").fadeIn('fast');
});

$(".pword").on("focusout", function() {
		$(".tooltip_og").fadeOut('fast', function () { 

		$(".passfail ").html('<span class="fail">Fail</span>');
		$(".checkmark").hide();
		});
});
	
var password = document.getElementById("password")
  , confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
// Also see: https://www.quirksmode.org/dom/inputfile.html

var inputs = document.querySelectorAll('.file-input')

for (var i = 0, len = inputs.length; i < len; i++) {
  customInput(inputs[i])
}
	
$(".toggle-password,.toggle-password2").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    input = $(this).parent().find("input");
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});	