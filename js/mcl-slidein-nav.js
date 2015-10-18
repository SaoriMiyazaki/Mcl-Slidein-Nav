(function($) {
$(function(){

var $menu     = $( '#mcl_slidein_nav_list' ),
    $menuBtn  = $( '#mcl_slidein_nav_btn' ),
    $body     = $( document.body ),
    $layer    = $( '#mcl_slidein_nav_layer' ),
    menuWidth = $menu.outerWidth(),
    menuPos   = mcl_slidein_nav.position;                
    
    $menu.css( menuPos , -menuWidth );
    
    $menuBtn.on( 'click', function(){
    	$body.toggleClass( 'open' );
        if($body.hasClass( 'open' )){
        	menu_open();                        
        } else {
        	menu_close();    
        }             
    });
   
    $layer.on('click', function(){
        menu_close()
        $body.removeClass( 'open' );
    });
    
    // menu open function -------------
    function menu_open(){
	    
	    $layer.show();
	    if( menuPos == 'left' ){
		    $menu.show().animate( { 'left' : 0 }, 300 );
	    }
	    else if( menuPos == 'right' ){
		    $menu.show().animate( { 'right' : 0 }, 300 );
	    }
    }    
    
    // menu close function -------------
	function menu_close(){
		$layer.hide();
		if( menuPos == 'left' ){
			$menu.animate( { 'left' : -menuWidth }, 300, function(){ $menu.hide(); } );
		}
		else if( menuPos == 'right' ){
		    $menu.animate( { 'right' : -menuWidth }, 300, function(){ $menu.hide(); } );
	    }
	}
});   
})(jQuery);
