jQuery(function($){

    $('.node-create').on('click', function(){
        var create_button = $(this),
            parent = $('#tree-plan a.active'),
            parent_li = parent.closest('li'),
            select = '',
            current_id = '',
            sector_id = parent.data('category'),
            parent_id = parent.data('id'),
            plan_id = 0,
            child_block = parent.next('ul');

        removeMessages();

        if( parent_id.l == "" ) {
            parent.find('select').focus();
            return false;
        }

        if( !child_block.length ) {
            parent.after('<ul></ul>');
            child_block = parent.next('ul');
        }

        parent.removeClass('parent').addClass('parent');
        parent_li.removeClass('open').removeClass('close').addClass('open');

        select = '<select name="st" class="sector-title" data-category="' + sector_id + '">';

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: "career_plan_get_sector",
                category: sector_id
            },
            dataType: 'json',
            async: false,
            beforeSend: function( xhr ) {
                create_button.find('i').removeClass('dashicons-plus').addClass('dashicons-update').addClass('spin');
            },
            success: function( data ) {
                var i = 0;
                $.each(data,function(key,val){
                    if( i==0 ) current_id = key;
                    select += '<option value="'+key+'">'+val+'</option>';
                    i++;
                });
                create_button.find('i').removeClass('dashicons-update').removeClass('spin').addClass('dashicons-plus');
            }
        });

        select += '</select>';

        plan_id = parent.data('plan') == undefined ? getPlanId() : parent.data('plan');

        child_block.append('<li><a href="#" class="branch" data-category="'+sector_id+'" data-parent="'+parent_id+'" data-sector="'+current_id+'" data-id="" data-item="0" data-plan="'+plan_id+'">'+select+'</a></li>');
        child_block.find('li:last').find('select').select2().change();
    });

    $('#tree-plan').on('change', '.sector-title', function () {
        var $select = $(this),
            sector = $select.val(),
            category = $select.data('category'),
            container = $select.closest('a'),
            wrap = {};

        container.find('.job-wrap').remove();
        container.find('.select2').after('<span class="job-wrap"><i class="dashicons dashicons-update spin"></i></span>');
        wrap = container.find('.job-wrap');

        select = '<select name="jt" class="job-title">';

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: "career_plan_get_elem",
                sector: sector,
                category: category
            },
            dataType: 'json',
            async: false,
            success: function( data ) {
                var i = 0;
                $.each(data,function(key,val){
                    if( i==0 ) current_id = key;
                    select += '<option value="'+key+'">'+val+'</option>';
                    i++;
                });
            }
        });

        select += '</select>';

        wrap.html(' / ' + select);
        wrap.find('select').change().select2();
    });

    $('#tree-plan').find('select').select2();

    $('.node-delete').on('click', function(){
        var parent = $('#tree-plan a.active'),
            parent_li = parent.closest('li'),
            ico = $(this).find('i'),
            ico_class = ico.attr('class');

        removeMessages();
        $.ajax({
            beforeSend: function() {
                ico.attr("class", "dashicons dashicons-update spin");
            },
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: "career_plan_delete",
                item: parent.data('item')
            },
            async: false,
            success: function (res){
                if (res == "0") {
                    $('.error-delete').removeClass('hide');
                } else  {
                    parent_li.remove();
                }

            }
        }).done(function(){
            ico.attr("class", ico_class);
        });

    });

    $('.nodes-save').on('click', function(){
        var $a = $('#tree-plan a'),
            save_button = $(this),
            error = false,
            unique = []
            data = [];

        removeMessages();
        $a.each(function(i,el){
            var obj = $(el),
                id = obj.data('id'),
                sector, parent, item_id;
            if( id != "0" ) {
                item_id = obj.data('item');
                sector = obj.data('sector');
                category = obj.data('category');
                parent = obj.data('parent');
                plan = obj.data('plan');
                if ( id == parent ) {
                    error = true;
                    obj.addClass('red-text');
                    return false;
                } else {
                    data.push({
                        id: id,
                        sector: sector,
                        category: category,
                        parent: parent,
                        plan: plan,
                        item: item_id
                    });
                }
            }
        });

        if (error) {
            $('.error').removeClass('hide');
            return false;
        }

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: "career_plan_save",
                plan: data
            },
            dataType: 'json',
            async: false,
            beforeSend: function( xhr ) {
                save_button.find('i').removeClass('dashicons-yes').addClass('dashicons-update').addClass('spin');
            },
            complete: function ( data ) {
                $('.success').removeClass('hide');
                save_button.find('i').removeClass('dashicons-update').removeClass('spin').addClass('dashicons-yes');
            }
        });
    });

    $('#tree-plan').on('click', 'li.open a.parent', function(e){
        if ($(e.target).prop("tagName") != 'A') {
            return false;
        }

        var obj = $(this).closest('li');
        obj.removeClass('open').addClass('close');

    });

    $('#tree-plan').on('click', 'li.close a.parent', function(e){
        if ($(e.target).prop("tagName") != 'A') {
            return false;
        }

        var $a = $(this),
            obj = $a.closest('li'),
            parent = $a.data('id') == undefined ? 0 : $a.data('id'),
            plan = $a.hasClass('branch') ? $a.data('plan') : 0,
            category = $a.data('category'),
            branches = {};

        $('a.active').removeClass('active');
        $a.addClass('active');

        if ($a.next('ul').length < 1) {
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: "career_plan_get_branch",
                    category: category,
                    plan: plan,
                    parent: parent
                },
                dataType: 'json',
                async: false,
                success: function(data) {
                    branches = data;
                }
            });

            $(branches).each(function(i, branch){
                var newBranch = {};
                $('.node-create').click();

                newBranch = $a.next('ul').find('li:last').find('a');
                // setter
                if (branch.plan != "0") {
                    newBranch.data('plan', branch.plan);
                }

                newBranch.data('item', branch.item_id);
                newBranch.find('.sector-title').val( branch.sector ).trigger('change');
                newBranch.find('.job-title').val( branch.job ).trigger('change');
                if (branch.child == 1) {
                    newBranch.addClass('parent').closest('li').addClass('close');
                }
            });
        }

        obj.removeClass('close').addClass('open');
    });

    $('#tree-plan').on('click', 'li a', function(e){
        e.preventDefault();

        $('#tree-plan li a').removeClass('active');
        $(this).addClass('active');
    });


    $('#tree-plan').on('change', 'select.job-title', function(){
        var obj = $(this),
            aObj = obj.closest('a');

        removeMessages();
        aObj.data('id',obj.val());
        aObj.next('ul').children('li').find('a').data('parent',obj.val());
    });

    $('#tree-plan').on('change', 'select.sector-title', function(){
        var obj = $(this),
            aObj = obj.closest('a');

        removeMessages();
        aObj.data('sector',obj.val());
    });

    $(window).scroll(function(){
        var pos = $(window).scrollTop();

        if(pos>50){
            $('.action-buttons').css('top','30px');
        } else {
            $('.action-buttons').css('top','auto');
        }
    });


    function removeMessages()
    {
        if( !$('.error').hasClass('hide') ){
            $('.error').addClass('hide');
            $('.red-text').removeClass('red-text');
        }

        if( !$('.error-delete').hasClass('hide') ){
            $('.error-delete').addClass('hide');
        }

        if( !$('.success').hasClass('hide') ){
            $('.success').addClass('hide')
        }
    }

    function getPlanId()
    {
        var plans = $('.branch'),
            max_js = 0,
            max_db = 0,
            max = 0;
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: "career_plan_get_maxplan"
            },
            async: false,
            success: function(data) {
                max_db = data * 1;
            }
        });
        if (plans.length) {
            plans.each(function(i, el){
                if( $(el).data('plan') > max ) {
                    max_js = $(el).data('plan');
                }
            });
        }

        if (max_js > max_db) {
            max = max_js;
        } else {
            max = max_db;
        }
        max++;

        return max;
    }

    /*

    var tree = $('#tree');
    tree.jstree({
        "core" : {
            "animation" : 0,
                "check_callback" : true,
                'force_text' : true,
                "themes" : { "stripes" : true }
        },
        "types" : {
            "#" : { "max_children" : 1, "max_depth" : 4, "valid_children" : ["root"] },
            "root" : { "icon" : "/static/3.3.8/assets/images/tree_icon.png", "valid_children" : ["default"] },
            "default" : { "valid_children" : ["default","file"] },
            "file" : { "icon" : "glyphicon glyphicon-file", "valid_children" : [] }
        },
        "plugins" : [ "contextmenu", "dnd", "search", "state", "types", "wholerow" ]
    });

    $('.node-create').on('click',function() {

        var ref = $('#tree').jstree(true),
            sel = ref.get_selected();

        console.log(sel);
        console.log(sel.length);

        if(!sel.length) { return false; }
        sel = sel[0];
        console.log(sel);
        sel = ref.create_node(sel, {"type":"file"});
        console.log(sel);
        if(sel) {
            ref.edit(sel);
        }

    });
    $('.node-delete').on('click', function() {
        var ref = $('#tree').jstree(true),
            sel = ref.get_selected();
        if(!sel.length) { return false; }
        ref.delete_node(sel);
    });

    $( "#accordion" ).accordion({
        heightStyle: "content"
    });
*/
});
