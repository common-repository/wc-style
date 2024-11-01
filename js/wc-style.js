jQuery(document).ready(function(){    
    jQuery('.wcsColorPicker').wpColorPicker();
jQuery( ".wcsTabs" ).tabs();
jQuery('.wcsTabs').fadeIn(1000);

jQuery('.ui-tabs-anchor').click(function(){
    
    jQuery('.wcsTabInner').fadeIn(1000);
});
});