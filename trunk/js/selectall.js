jQuery(function(jQuery) {
	jQuery('.selectall').click(function()
	 {
    	jQuery(this.form.elements).filter(':checkbox').prop('checked', this.checked);
     });
});	