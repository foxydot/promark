// my-ajax-request.js
jQuery(document).ready(function(e){e(".additional-projects li a").click(function(t){t.preventDefault();var n="id="+e(this).attr("id");n+="&action=my_frontend_action";n+="&nonce="+MyAjaxObject.nonce;e.post(MyAjaxObject.ajax_url,n,function(t){e("#sidebar-alt").html(t)})})});