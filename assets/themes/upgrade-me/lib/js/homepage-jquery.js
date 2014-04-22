jQuery(document).ready(function($) {
    var numwidgets = $('#hp-callout section.widget').length;
    $('#hp-callout').addClass('cols-'+numwidgets);
    var cols = 12/numwidgets;
    $('#hp-callout section.widget').addClass('col-sm-'+cols);
    $('#hp-callout section.widget').addClass('col-xs-12');
    
    $.backstretch(php_data.stylesheet_uri + '/lib/img/bkg-homepage.jpg');
    
    var bw_img;
    $('#homepage-widgets section.widget').hover(function(e){
        bw_img = $(this).find('.bw').attr('style');
        $(this).find('.bw').attr('style','');
    },function(e){
        $(this).find('.bw').attr('style',bw_img);
    });
});