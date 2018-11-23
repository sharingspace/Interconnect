//error msg animation

jQuery(document).ready(function($) {

  $(".msg").show("slow").delay(3000).fadeOut(1000);
  
  $("input").autosizeInput(); // autoGrow Input filed

//left menu

$('#cssmenu > ul > li > a').click(function() {
  $('#cssmenu li').removeClass('active');
  $(this).closest('li').addClass('active');	
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    $('#cssmenu ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;	
  }		
});
});

// IE checkbox border fixed

function styleInputs() {

var cbxs=document.getElementsByTagName('INPUT');

for (var i=0; i<cbxs.length; i++) {

if(cbxs[i].type=='checkbox') {

cbxs[i].style.border='none';

}

}

}
window.onload=styleInputs;