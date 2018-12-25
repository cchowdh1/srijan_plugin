$(document).ready(function(){

  $( ".test-encrypt" ).click(function() {
	  $('.decrypted_text').hide();
	var word_enc = $('#encrypt-text').val();
	
	if (word_enc == '' || word_enc == " ") {
		alert("Cannot be empty");
		return false;
	}
	var d = new Date();
    var n = d.getTime();
   $.ajax({
      type: "POST",
      dataType: "json",
      url: "form_fields.php?time="+n, 
      data: {
              word_enc: word_enc,
            },
      success: function(data) {
		console.log(data);
        $(".encrypted_text").html("<span>Encrypted Text</span>: "+data['encrypt']);
		$(".decrypted_text").html("<span>Decrypted Text</span>: "+data['decrypt']);
        
      },
	  error: function(data) {
		  console.log(data);
      }
    });
  });
  
  $( ".test-decrypt" ).click(function() {
	$('.decrypted_text').show();
  });
  
  
});