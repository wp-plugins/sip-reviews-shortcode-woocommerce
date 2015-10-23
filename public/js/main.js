
/* All Tabs */
function TabsNav(){
$('.nav-tabs a').click(function () {
	var contentRel = $(this).attr('data-rel');
	if(!$('#' + contentRel).is(':visible')){
		$('.tabs-content .tabs').hide();
		$('.comment-open-click').hide();
		$('.nav-tabs a').removeClass('active');
		$(this).addClass('active');
		$('#' + contentRel).fadeIn();
	}
});
}
function Tabs(){
$('.tabs a').click(function () {
	var contentRel = $(this).attr('data-rel');
	if(!$('#' + contentRel).is(':visible')){
		$('.content .tab').hide();
		$('#' + contentRel).fadeIn();
	}
});
}
function Tabfn(){
var Tabs=$('.tabfn a')
Tabs.bind("click",function(){
    $('.tab-content').hide();
	var show2Tab=$('#'+$(this).attr('data-rel'));        
    show2Tab.show();
	});
};
/*End*/

$('.expandonclick, #Reviewtab, #Question, #Videotitle, #Phototitle').click(function(){
	$(this).next().slideDown();
});

$('.photoComputer').click(function(){
	$(this).hide();
		$('.photoWeb').fadeIn();
		$('.photoWebsite').fadeIn();
		$('.photoComp').hide();
		
});
$('.photoWebsite').click(function(){
	$(this).hide();
		$('.photoWeb').hide();
		$('.photoWebsite').hide();
		$('.photoComp').fadeIn();
		$('.photoComputer').fadeIn();
		
});




$('#S1').change(function() { 
var Opt = $(this).find('option:selected').val();
        if(Opt == 'Show all reviews') {
            $('.ShowEve').hide();
			$('.WhatOthers').hide();
			$('.zeroReview').show();
			$('.ShowAll').show();
        } else {
            $('.ShowEve').show();
			$('.ShowAll').hide();
			$('.zeroReview').hide();
			$('.WhatOthers').show();
        }
});

// Comment Additional
$('.comment .form-control').click(function () {
		$(this).next().slideDown();
		alert("ali");
});

// Rating
$(function() {
      $('.example').barrating({theme: 'fontawesome-stars'});
   });
//End



jQuery(document).ready(function($) {
	$('.sip-star-rating').each(function () {
		//alert($(this).text());
		var value = $(this).text();
	 	$('.rating-readonly-'+value).barrating({theme: 'fontawesome-stars', readonly:true, initialRating: value });
  });
});

// Rating
// $(function() {
//       $('.rating-readonly').barrating({theme: 'fontawesome-stars', readonly:true, initialRating: 3});
//    });
//End

$('.example').barrating('show', {
        onSelect:function(value, text) {
        //your code goes here.
        $('#div-to-be-revealed').toggleClass('invisible')
        $('.show').slideDown();
    }
});

//****** DOCUMENT READY FUNCTION ******//
$(document).ready(function(){
	TabsNav();	
	Tabs();	
	Tabfn();
});