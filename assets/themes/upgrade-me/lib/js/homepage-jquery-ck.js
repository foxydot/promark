jQuery(document).ready(function(e){var t=e("#homepage-widgets section.widget").length;e("#homepage-widgets").addClass("cols-"+t);var n=12/t;e("#homepage-widgets section.widget").addClass("col-sm-"+n);e("#homepage-widgets section.widget").addClass("col-xs-12");e.backstretch(php_data.stylesheet_uri+"/lib/img/bkg-homepage.jpg")});