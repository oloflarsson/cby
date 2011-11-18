/*jQuery.validator.addMethod("minecraftusername", function(value, element) {
	return this.optional(element) || /^[abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_]{2,16}$/.test(value);
}, "This must be a valid minecraft username.");

jQuery(document).ready(function() {

	jQuery.validator.setDefaults({ 
		rules: {
			os0: {
				required: true,
				minecraftusername: true
			}
		},
		errorPlacement: function(error, element) {}
	});
	
	jQuery('form.validatedform').each( function(){
		jQuery(this).validate();
	});
	
});*/