jQuery(document).ready(function ($) {

	$('.carousel').carousel({
	  interval: 8000
	})
	
	var $wparmdisplay = $('#media-container');
	
	$wparmdisplay.imagesLoaded( function(){
	
	  $(function(){
	  
		  $wparmdisplay.masonry({
			itemSelector: '.media-item',
		    columnWidth: 320,
		    isFitWidth: true,
		    isAnimated: !Modernizr.csstransitions
		  });
		  
	  });
	  
	});
	
    window.setTimeout(update, 20000); // This sets the auto-checking
    
    $(".media-item .thumb").colorbox({transition:"fade"});
    $(".media-item .iframe").colorbox({iframe:true, width:"600px", height:"600px"});
    $(".media-item .iframe-instagram").colorbox({iframe:true, width:"645px", height:"780px"});

    
	$('#loadmore_div a').click(function (e) {
		e.preventDefault();
		var the_button = $(this);
		the_button.addClass('active');
		var last_media_id = $("#media-container div.media-item:last-child").attr('data-internalid'); // find first data-internalid value
		var last_media_date = $("#media-container div.media-item:last-child").attr('data-timestamp'); 
		var image_only = getParameterByName ( 'allimages' );
		var s = getParameterByName( 's' );
		$.ajax({
	       url:ajaxurl,
	       type:'POST',
	       dataType: "html",
	       data:'action=wparm_load_more&last_media_id=' + last_media_id + '&last_media_date=' + last_media_date + '&allimages=' + image_only + "&s=" + s,
		   timeout: 3000,
		   success: function(data) {
			  var boxes = $(data);
			  boxes.imagesLoaded(function() {
				  $( "#media-container" ).append( boxes ).masonry( 'appended', boxes, true );
			      $( ".media-item .thumb" ).colorbox({transition:"fade"});
			      $( ".media-item .iframe").colorbox({iframe:true, width:"600px", height:"600px"});
			      $( ".media-item .iframe-instagram").colorbox({iframe:true, width:"655px", height:"770px"});

				  $( "#media-container div.media-item div.thumbnail-older" ).animate( { backgroundColor: "#f6f6f6"}, 2200, function() {
					    // Animation complete.
					    $( "#media-container div.thumbnail-older" ).removeClass("thumbnail-older");				    
					    $( "#media-container div.thumbnail" ).css("background-color","");
					  });
			  });
			  the_button.removeClass('active');
		   },
		   error: function (XMLHttpRequest, textStatus, errorThrown) {
		   	  // do nothing for now
		   }
	    });
	});	
	     	
});

jQuery.fn.exists = function(){return this.length>0;}



var statementArray = [
    'Chilling Out.',
    'Just entered \'distraction\' free mode in WordPress, and my wife and kids are missing',
    'Child themes = when WordPress &amp; a parent theme love each other very much...',
    'I wish there were grandchild themes in WordPress, so I can make a \'Get Off My Lawn\' plugin',
    'If you see @dimensionmedia at #wcsf, tell him he\'s not as ugly as people say.',
    'Remember #blamenacin?',
    'A post, page, and a custom post type walk into a bar...'    
];

function update() {
  
	jQuery("#notice_div .notice-message").html('Loading New Posts...'); 
	jQuery("#notice_div a").addClass('active');
	
	var last_media_id = jQuery("#media-container div.media-item:first-child").attr('data-internalid'); // find first data-internalid value
	var last_media_date = jQuery("#media-container div.media-item:first-child").attr('data-timestamp'); 
	var image_only = getParameterByName ( 'allimages' );
	var s = getParameterByName( 's' );
	  
	jQuery.ajax({
	       url:ajaxurl,
	       type:'POST',
	       dataType: "html",
	       data:'action=wparm_update_media&last_media_id=' + last_media_id + '&last_media_date=' + last_media_date + '&allimages=' + image_only + "&s=" + s,
		   timeout: 3000,
		   success: function(data) {
			  var boxes = jQuery(data);
			  boxes.imagesLoaded(function() {
				  jQuery( "#media-container" ).prepend( boxes ).masonry( 'reload' );
			      jQuery( ".media-item .thumb" ).colorbox({transition:"fade"});
			      jQuery( ".media-item .iframe").colorbox({iframe:true, width:"600px", height:"600px"});
			      jQuery( ".media-item .iframe-instagram").colorbox({iframe:true, width:"655px", height:"770px"});

				  jQuery( "#media-container div.media-item div.thumbnail-new" ).animate( { backgroundColor: "#f6f6f6"}, 2200, function() {
					    // Animation complete.
					    jQuery( "#media-container div.thumbnail-new" ).removeClass("thumbnail-new");				    
					    jQuery( "#media-container div.thumbnail" ).css("background-color","");
					  });
			  });

			  var randomNumber = Math.floor(Math.random()*statementArray.length);
		      jQuery("#notice_div .notice-message").html( statementArray[randomNumber] ); 
		      jQuery("#notice_div a").removeClass('active');
		      // notifications (Chrome only)

		      window.setTimeout(update, 10000);
		   },
		   error: function (XMLHttpRequest, textStatus, errorThrown) {
		      jQuery("#notice_div .notice-message").html('Timeout contacting server. Checking again in a few.');
		      jQuery("#notice_div a").removeClass('active');
		      window.setTimeout(update, 60000);
		   }
	    });
}

function getParameterByName(name) {
 
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    
}


var e = document.getElementById("fullscreen_trigger");
var f = document.getElementById("fullscreen_container");


e.onclick = function() {

	if (RunPrefixMethod(document, "FullScreen") || RunPrefixMethod(document, "IsFullScreen")) {
		RunPrefixMethod(document, "CancelFullScreen");
	}
	else {
		RunPrefixMethod(f, "RequestFullScreen");
	}

}

var pfx = ["webkit", "moz", "ms", "o", ""];
function RunPrefixMethod(obj, method) {
	
	var p = 0, m, t;
	while (p < pfx.length && !obj[m]) {
		m = method;
		if (pfx[p] == "") {
			m = m.substr(0,1).toLowerCase() + m.substr(1);
		}
		m = pfx[p] + m;
		t = typeof obj[m];
		if (t != "undefined") {
			pfx = [pfx[p]];
			return (t == "function" ? obj[m]() : obj[m]);
		}
		p++;
	}

}



