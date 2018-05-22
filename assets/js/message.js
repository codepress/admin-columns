<<<<<<< HEAD
'use strict';jQuery(function($){$(document).on('click','.ac-notice [data-dismiss], .ac-notice button.notice-dismiss',function(){var $notice=$(this).parents('.ac-notice');var name=$notice.data('name');if(!name){return false}setTimeout(function(){'use strict';$notice.fadeOut().remove()},3000);$.post(ajaxurl,{action:'ac_notice_dismiss',name:name,_ajax_nonce:$notice.data('nonce')},function(){$notice.fadeOut().remove()})})});
=======
'use strict';jQuery(function($){$(document).on('click','.ac-notice [data-dismiss], .ac-notice button.notice-dismiss',function(e){e.preventDefault();var $notice=$(this).parents('.ac-notice');var name=$notice.data('name');if(!name){return false}$notice.fadeOut(500,function(){$notice.remove()});$.post(ajaxurl,{action:'ac_notices',name:name,_ajax_nonce:$notice.data('nonce')})})});
>>>>>>> origin/1036-refactor-notices
