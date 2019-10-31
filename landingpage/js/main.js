$( document ).ready(function() {
	$('.open-popup-link').magnificPopup({
	  type:'inline',
	  midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
	});

	var registerUrl = "../registerconference";

	$('.btn-submit').click(function(){

    	var firstname      = $('#firstname').val();
    	var lastname       = $('#lastname').val();
    	var m_phone        = $('#m_phone').val();
    	var email          = $('#email').val();
    	var profession     = $('#profession').val();
    	var home_address   = $('#home_address').val();
    	var office_address = $('#office_address').val();
    	var education      = $('#education').val();
    	var employment     = $('#employment').val();
    	var interest_sm    = $('#interest_sm').val();
    	var agreement      = $('#agreement').prop('checked');

    	$('.message').fadeIn();
    	// if( (firstname == '') || (email == '') )
    	// {
    	// 	$('.message').text('Semua field (*) harus diisi!');
    	// }else if((branch_id == '0')){

    	// 	$('.message').text('Anda belum memilih Studio 20FIT');
    	// }else if( (agreement == false)){
    	// 	$('.message').text('Anda harus dengan menyetujui persyaratan untuk melanjutkan');
    	// }else{

    	$('.message').text('Please wait...');
    	$.post(registerUrl,
		{
		        firstname: firstname,
		        lastname: lastname,
		        m_phone: m_phone,
		        email: email,
		        profession: profession,
		        home_address: home_address,
		        office_address: office_address,
		        education: education,
		        employment: employment,
		        interest_sm: interest_sm,
		        agreement: agreement
		},
		function(data, status){
		    	// var obj = $.parseJSON(data);
		    	if(data.status === 'error'){
		    		$('.message').text(data.error_message);
		    	}else{
		    		$('.message').text('Thank you for registration');
		    		var delay = 0; //Your delay in milliseconds

					setTimeout(function(){
						window.location = "success.html";
					}, delay);
		   //  		console.log('success, handle redirection');
		    	}
		});

    	// }

    	return false;
    });

    $('.message').click(function(){
    	$(this).fadeOut();
    });

});