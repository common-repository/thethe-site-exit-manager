(function(a){a.extend({send:function(){var e;a("input[name=find-posts-what]:checked").each(function(){e=a(this).val()});var d={search_string:a("#find-posts-input").val(),search_type:e,action:"find_posts",_ajax_nonce:a("#_ajax_nonce").val()};a.ajax({type:"POST",url:ajaxurl,data:d,success:function(b){findPosts.show(b)},error:function(b){findPosts.error(b)}})}},findPosts)})(jQuery);