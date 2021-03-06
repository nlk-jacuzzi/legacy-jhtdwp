// js for front end of ProGo Themes Business Pro sites
var progo_cycle, progo_timing, progo_sw = '651px';

function proGoTwitterCallback(twitters) {
  for (var i=0; i<twitters.length; i++){
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
    });
    jQuery('.tweets p.last').before('<p>'+status+'<br /><a href="http://twitter.com/'+twitters[i].user.screen_name+'/status/'+twitters[i].id_str+'" target="_blank">'+relative_time(twitters[i].created_at)+'</a> via '+ twitters[i].source +'</p>');
  }
}

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}

function progo_homecycle( lnk ) {
	if( lnk == false ) {
		lnk = jQuery('#pagetop .ar a:last');
	}
	if( lnk.hasClass('off') == false ) {
		lnk.add(lnk.siblings('a')).addClass('off');
		clearTimeout(progo_cycle);
		var onn = jQuery('#pagetop .slide.on');
		var nex = onn.next();
		var dir = '-=' + progo_sw;
		if( lnk.hasClass('n') ) {
			if(nex.hasClass('slide') == false) {
				nex = onn.prevAll('.slide:first-child');
			}
			nex.css({'left':progo_sw});
		} else {
			nex = onn.prev();
			if(nex.size() == 0) {
				nex = onn.nextAll('.ar').prev();
			}
			nex.css({'left':'-' + progo_sw});
			dir = '+=' + progo_sw;
		}
		onn.add(nex).animate({
			left: dir
		}, 600, function() {
			jQuery(this).toggleClass('on');
			jQuery('#pagetop .ar a').removeClass('off');
			progo_scrollcheck();
		});
	}
	return false;
}

function progo_scrollcheck() {
	var ptop = jQuery('#pagetop');
	var fset = ptop.offset();
	var wscrolltop = jQuery(window).scrollTop();
	clearTimeout(progo_cycle);
	if( ( progo_timing > 0 ) && ( wscrolltop < fset.top ) ) {
		progo_cycle = setTimeout("progo_homecycle(false)",progo_timing);
	}
}

jQuery(function($) {
	var progo_ptop = $('#pagetop');
	if(progo_ptop.hasClass('slides')) {
		progo_ptop.children('div.ar').children('a').click(function() { return progo_homecycle($(this)); });
		progo_ptop.addClass('sliding');
		$(window).bind('scroll.progo',progo_scrollcheck).trigger('scroll.progo');
	}
	
	$('#nav > li > a').addClass('first');
	$('#nav ul.sub-menu').prev().addClass('sub').bind('mouseover',function() {
		$(this).parent().addClass('hover').children('ul').show();
	}).parent().bind('mouseleave',function() {
		$(this).removeClass('hover').children('ul').hide();
	});
	
	$('.eml').each(function() {
		var addr = $(this).html();
		$(this).html('<a href="mailto:'+ addr +'">'+ addr +'</a>');
	});
});