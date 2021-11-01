jQuery(function($){
    if($('#goto-sector').length>0) {
        $('[href="/info-sector"]').prop('href',$('#goto-sector').prop('href'));
    }


    var $animate1 = $('.elementor-2 .elementor-element.elementor-element-557ebf0 > .elementor-element-populated'),
        $animate2 = $('.elementor-2 .elementor-element.elementor-element-c2be9af > .elementor-element-populated');

    if($animate1.length>0 && $animate2.length>0) {
        var $animate1CenterX = $animate1.offset().left + ($animate1.width() / 2),
            $animate1CenterY = $animate1.offset().top + ($animate1.height() / 2),

            $animate2CenterX = $animate2.offset().left + ($animate2.width() / 2),
            $animate2CenterY = $animate2.offset().top + ($animate2.height() / 2),

            maxMove = $('.elementor-element-8ce203a').width() / 30;

        $( "body" ).mousemove(function(e){
            var X = e.pageX;
            var Y = e.pageY;

            var distX1 = X - $animate1CenterX,
                distY1 = Y - $animate1CenterY,
                distX2 = X - $animate2CenterX,
                distY2 = Y - $animate2CenterY;

            if (Math.abs(distX1) < 500 && distY1 < 200) {
                $animate1.css('background-position', (distX1/50+50) + '% ' + (distY1/50+50) + '%');
            }

            $animate2.css('background-position', (distX2/40+50) + '% ' + (distY2/50+50) + '%');

        });
    }

/*
    $('.select365').select2({
        width: 365
    });
    $('.select-career').select2();
*/

    $('.elementor-element-557ebf0,.elementor-element-c2be9af').click(function(e){
        var obj = $(this),
            elem = $(e.target),
            form = elem.find('.career-main-select'),
            switcher = elem.hasClass('elementor-widget-container');

        if (!switcher && elem.closest('.elementor-icon-box-wrapper').length==1) {
            switcher = true;
            form = elem.closest('.elementor-widget-container').find('.career-main-select');
        }

        if( switcher ) {
            if(form.css('display')=='none') {
                $('.career-main-select').animate({height: "hide"}, 300);
                form.animate({height: "show"}, 300);
            }
        }
    });

    $('#companySelect, #employerSelect').change(function(){
        var obj = $(this);
        if(obj.val().length>0) {
            obj.closest('form').submit();
        }
    });

    $('#form-calculator').on('change','select',function(){
        var com = {
                "category": "job",
                "aoe": "type",

                "aoe-plan": "type-plan",
                "category-plan": "job-plan",
                "job-plan": "aoe-plan",

                "job": "aoe",
                "type": "region"
            },
            obj = $(this),
            additional = {},
            id = obj.prop("id"),
            nextObj = $('#'+com[id]),
            nextObjEmpty = nextObj.find('[value=""]');

        $('.form__item').removeClass('error');

        if(obj.val().length==0) return false;

        switch(id) {
            case "exp":
            case "aoe":
            case "aoe-plan":
                var jobVal = {},
                    catVal = {};
                jobVal = $("#job").length > 0 ? $("#job").val() : $("#job-plan").val();
                catVal = $("#job").length > 0 ? $("#category").val() : $("#category-plan").val()
                additional = {
                  job: jobVal,
                  category: catVal
                };
            break
            case "job-plan":
            case "job":
                var catVal = {};
                catVal = $("#job").length > 0 ? $("#category").val() : $("#category-plan").val()
                additional = {
                    category: catVal
                };
                break;
            case "type":
                additional = {
                    job: $("#job").val(),
                    exp: $("#exp").val()
                };
            break;
            case "region":
                $("#salary").prop("disabled",false);
                return false;
            break;
        }

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: "career_calculator_get_elem",
                type: id,
                added: additional,
                id: obj.val()
            },
            dataType: 'html',
            async: false,
            beforeSend: function( xhr ) {
                nextObj.next('.select2').remove();
                nextObj.after(' <span id="loader" class="dashicons dashicons-update spin"></span>');
            },
            success: function( data ) {
                var current = false;

                $('#loader').remove();
                nextObj.hide().after('<select id="'+com[id]+'" name="'+com[id]+'" class="form__select career-required">'+data+'</select>');
                nextObj.remove();
                $('#'+com[id]).select2();


                $('#form-calculator select').each(function(i,el){
                    var obj = $(el),
                        curr_id = obj.prop('id');

                    if(current) {
                        obj.hide().after('<select id="'+curr_id+'" name="'+curr_id+'" class="form__select career-required" disabled autocomplete="off"><option value="" selected>...</option></select>');
                        obj.remove();
                        $('#'+curr_id).select2();
                    }
                    if( curr_id == com[id]) current = true;
                });
            }
        });
    });

    $('#form-calculator').on('focus','input, select',function(){
        $(this).closest('.form__item').removeClass('is_error');
    });

    $('#form-calculator').submit(function(){
        $('#calculator-go').click();
        return false;
    });

    $('#calculator-go').click(function(e){
        e.preventDefault();
        var salary = $("#salary"),
            error = false,
            obj = $(this),
            form = obj.closest('form');

/*
        if(salary.length>0 && (salary.prop("disabled") || salary.val() == "") ){
            salary.closest('.form__item').addClass('is_error');
            return false;
        }
*/

        $('.career-required').each(function(i,el){
            var obj = $(el);

            if(obj.val() == "" ){
                obj.closest('.form__item').addClass('is_error');
                error = true;
            }
        });

        if(error) return false;

        if (form.find('[name="action"]').val() == 'career_calculator_get_json') {
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: obj.closest('form').serialize(),
                dataType: 'json',
                async: false,
                beforeSend: function (xhr) {
                    obj.append(' <span class="dashicons dashicons-update spin"></span>');
                },
                success: function (data) {

                    $('#lev__salary .lev__value').text(data.salary);
                    $('#lev__salary .levels__width').css('width', data.salary_per + '%');

                    $('#lev__middle .lev__value').text(data.middle);
                    $('#lev__middle .levels__width').css('width', data.middle_per + '%');

                    $('#lev__max .lev__value').text(data.max);
                    $('#lev__max .levels__width').css('width', data.max_per + '%');

                    $('#lev__min .lev__value').text(data.min);
                    $('#lev__min .levels__width').css('width', data.min_per + '%');

                    $('#lev__begin').text(data.begin);
                    $('#lev__average').text(data.average);
                    $('#lev__finish').text(data.finish);

                    obj.find('.dashicons').remove();
                    $('#calculator-write-us').css({'display': 'flex'});

                    $('.salary__info').slideDown(500, function() {
                        var _ = $(this);
                        var header = $('.header');
                        var body = $('html, body');

                        body.animate({scrollTop: _.offset().top - header.outerHeight()}, 500, function() {
                            _.find('.levels').addClass('is_active');
                        });
                    });
                }
            });
        } else {
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: obj.closest('form').serialize() + '&' + $.param({
                    'aoeString': $('#select2-aoe-container').text(),
                    'categoryString': $('#select2-category-container').text(),
                    'jobString': $('#select2-job-container').text(),
                    'typeString': $('#select2-type-container').text(),
                    'regionString': $('#select2-region-container').text()
                }),
                dataType: 'html',
                async: false,
                beforeSend: function (xhr) {
                    obj.append(' <span class="dashicons dashicons-update spin"></span>');
                },
                success: function (data) {
                    $('#career__info-tree').html(data);
                    console.log('количество загруженных ячеек = ' + $('.career__cell').length);
                    $('.career__info').trigger('showCareer');
                    $('.dashicons-update.spin').remove();
                }
            });
        }

    });

    $('#sector-aoe').change(function(){
        var obj = $(this);
        if(obj.val()!="") {
            location.href = obj.closest('form').prop('action') + '?sector=' + obj.val();
        }
    });

    /////////////////////////
    //// Конструтор резюме
    ////////////////////////*
    /*
    $('.resume__form').on('submit', function(e){
        e.preventDefault();

        var form = $(this),
            btnSubmit = form.find('[type="submit"]'),
            formData = new FormData(form[0]);

        formData.append( 'action', 'career_create_resume' );

        $('.download').remove();

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            async: false,
            beforeSend: function (xhr) {
                btnSubmit.append(' <span class="dashicons dashicons-update spin"></span>');
            },
            success: function (data) {
                if( data.file != undefined ) {
                    form.append('<a href="/wp-content/uploads/resume/' + data.file + '" class="download" download></a>');
                    $('.download').click();
                }
                btnSubmit.find('.dashicons').remove();
            }
        });

        return false;
    });

*/

    function setBruncheLines() {
        var svg = $('#svg-line'),
            xDelta = 30, // левый margin элемента
            yDelta = 30; // верхний margin элемента
        $('#branche-wrapper .branche-row .col .item').each(function(i,el){
            var obj = $(el),
                chs = String(obj.data('child')?obj.data('child'):''),
                childsArr = [];

            if (chs.length>0) {
                var x1 = obj.position().left+obj.outerWidth()+xDelta,
                    y1 = obj.position().top+obj.outerHeight()/2+yDelta;

                childsArr = chs.split(':');

                $.each(childsArr,function(j,id){
                    var childNode = $('.job-'+id),
                        currentParent = obj,
                        currentContainer = currentParent.closest('.col'),
                        newLine,
                        isEmpty = false,
                        x2, y2, currIndex, emptyNode;

                 //   console.log('.job-'+id);
                    do {
                        newLine = document.createElementNS('http://www.w3.org/2000/svg','line');
                        // проверяем существование ребенка в следующей колонке
                        var nextContainer = currentContainer.next('.col');

                        if (nextContainer.find('.item').index(childNode)!=-1) {
                            x2 = childNode.position().left+xDelta,
                            y2 = childNode.position().top+childNode.outerHeight()/2+yDelta;

                            newLine.setAttribute('x1',x1);
                            newLine.setAttribute('y1',y1);
                            newLine.setAttribute('x2',x2);
                            newLine.setAttribute('y2',y2);

                            svg.append(newLine);

                            isEmpty = false;
                        } else {
                            currIndex = currentContainer.find('.item').index(currentParent),
                            emptyNode = nextContainer.find('.item').eq(currIndex);

                            // проводим линю до пустой ячейки
                            x2 = emptyNode.position().left+xDelta,
                            y2 = emptyNode.position().top+obj.outerHeight()/2+yDelta;

                            newLine.setAttribute('x1',x1);
                            newLine.setAttribute('y1',y1);
                            newLine.setAttribute('x2',x2);
                            newLine.setAttribute('y2',y2);

                            svg.append(newLine);

                            // проводим линию на всю ширину пустой ячейки
                            newLine = document.createElementNS('http://www.w3.org/2000/svg','line');

                            x1 = x2;
                            y1 = y2;

                            x2 += emptyNode.outerWidth();

                            newLine.setAttribute('x1',x1);
                            newLine.setAttribute('y1',y1);
                            newLine.setAttribute('x2',x2);
                            newLine.setAttribute('y2',y2);

                            svg.append(newLine);

                            currentContainer = nextContainer;
                            currentParent = emptyNode;

                            x1 = emptyNode.position().left+obj.outerWidth()+xDelta,
                            y1 = emptyNode.position().top+obj.outerHeight()/2+yDelta;

                            isEmpty = true;

                        }

                    } while (isEmpty);

                });
            }
        });
    }

/*
    $('.btn--compare').on('click', function(e){
        e.stopPropagation();
        var container = $(this).closest('.container'),
            year_pad = container.find( ".m-year" ),
            year = container.find( ".accordeon__header_years" ),
            dis = year_pad.css('display');

        if (dis == 'none') {
            year_pad.show();
            year.show();
        } else {
            year_pad.hide();
            year.hide();
        }
    });

    $('.accordeon__header').on('click', function(){
        var container = $(this).find('.container'),
            year_pad = $( ".m-year" ),
            year = $( ".accordeon__header_years" );
        year_pad.hide();
        year.hide();
    });
*/

    $('.year-list').on('click', 'li', function (e){
        e.stopPropagation();
        var obj = $(this),
            container = obj.closest('.accordeon__item').find('.levels_tiny'),
            year = obj.data('set-year');

        if (obj.hasClass('is_active')) {
            obj.removeClass('is_active');
            $('[data-year="'+year+'"]', container).addClass('line__hide');
        } else {
            $('[data-year="'+year+'"]', container).removeClass('line__hide');
            obj.addClass('is_active');
        }
    });

    function initRate() {
        var li = $('#grade-form .rate ul li');

        li.on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10);

            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });

        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });

        li.on('click', function(){
            var onStar = parseInt($(this).data('value'), 10);
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }

            var ratingValue = parseInt($('#grade-form .rate ul li.selected').last().data('value'), 10);
            $('#site-rate').val( ratingValue );


        });
    }
    function initGradeForm()
    {
        $('#grade-form__send').click(function(e){

            e.preventDefault();
            var obj = $(this);

            if (obj.hasClass('active') ) {

                if (obj.data('step') == 1) {
                    obj.data('step', 2);
                    $('#grade-form .step-1').hide();
                    $('#grade-form .step-2').show();
                    obj.text('Отправить');
                } else {
                    $.ajax({
                        url: '/wp-admin/admin-ajax.php',
                        type: 'POST',
                        data: {
                            action: "career_send_grade_site",
                            rate: $('#site-rate').val(),
                            recom: $('#grade-form__text').val()
                        },
                        success: function (data) {
                            $('.grade-form--close').click();
                        }
                    });
                }

            }

        });

    }
    initRate();
    initGradeForm();


    if ($.cookie('spend_time') == undefined) {
        $.cookie('spend_time', (new Date()).getTime(), { expires: 30, path: '/' });
        $.cookie('spend_time_stop', 0, { expires: 180, path: '/' });
    }

    var dedtime = 30000, // время в мс через которое будет открыто окно оценки
        startSpentTime = $.cookie('spend_time'),
        stopSpentTime = $.cookie('spend_time_stop');

    setInterval(function() {
        if (((new Date()).getTime() - startSpentTime) >= dedtime && stopSpentTime < 1) {
            gradeFormShowAnimate();
        }
    }, 5000);

    $('#grade-form .grade-form--close').on('click', function(){
        var obj = $(this);
        if (obj.hasClass('up')) {
            gradeFormShowAnimate();
        } else {
            gradeFormHideAnimate();
        }
    });

    function gradeFormShowAnimate()
    {
        $('#grade-form').animate({
            bottom: "0"
        }, 1000, function() {
            $('#grade-form .grade-form--close').removeClass('up');
            stopSpentTime = 1;
            $.cookie('spend_time', '', { expires: 30, path: '/' });
            $.cookie('spend_time_stop', 1, { expires: 180, path: '/' });
        });
    }
    function gradeFormHideAnimate()
    {
        $('.grade.active').removeClass('active');
        $('#grade-form .step-1').show();
        $('#grade-form .step-2').hide();
        $('#grade-form__send').removeClass('active').data('step', 1).text('Далее');

        $('#grade-form').animate({
            bottom: "-188px"
        }, 1000, function() {
            $('#grade-form .grade-form--close').addClass('up');
            $.cookie('spend_time', '', { expires: 30, path: '/' });
            $.cookie('spend_time_stop', 1, { expires: 180, path: '/' });
        });
    }

    $('#grade-form .rate').on('click', 'li.grade', function() {
        var obj = $(this),
            form = obj.closest('form');
        $('li.grade').removeClass('active');
        obj.addClass('active');
        form.find('button').addClass('active');
        form.find('#site-rate').val( obj.text() );
    });


    $('.send-resume').on('click', function(){
        var obj = $(this),
            form = obj.closest('form'),
            inputAction = form.find('[name="action"]'),
            currentaction = obj.data('action');

        if (currentaction == 'career_create_resume') {
            inputAction.val( currentaction );
            form.submit();
        } else {
            inputAction.val( currentaction );
            obj.find('span').removeClass('icon_arrow_right2').addClass('dashicons dashicons-update spin');
            formData = new FormData(form.get(0));

            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {
                    obj.text('Отправлено');
                }
            });
        }
    });

});
