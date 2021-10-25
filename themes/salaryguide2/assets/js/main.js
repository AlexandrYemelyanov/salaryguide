jQuery(
    function($) {
      /*--- Begin vars ---*/
      // main
      var win = $(window);
      var doc = $(document);
      var body = $('html, body');

      // breakpoints
      var xl = 1289;
      var lg = 1019;
      var md = 779;
      var sm = 599;
      var xs = 459;
      /*--- End vars ---*/

      /*--- Begin header ---*/
      var header = $('.header');
      var burger = $('.header__burger');
      var logo = $('.header__logo');
      var menu = $('.header__menu');
      var social = $('.header__social');
      var nav = $('.header__nav');
      var navClose = $('.header__nav_close');

      burger.add(navClose).on('click', function(event) {
        event.preventDefault();

        var _ = $(this);
        var _id = _.attr('href');

        $(_id).toggleClass('is_open');
      });

      doc.on('click touchstart', function(event) {
        if (!$(event.target).closest(nav).length && !$(event.target).is(burger)) {
          nav.removeClass('is_open');
        }
      });

      function fnAdaptiveHeader() {
        var ww = window.innerWidth;

        if (ww > lg) { if (menu.hasClass('is_moved')) menu.removeClass('is_moved').insertAfter(logo); }
        else { if (!menu.hasClass('is_moved')) menu.addClass('is_moved').appendTo(nav); }

        if (ww > xs) { if (social.hasClass('is_moved')) social.removeClass('is_moved').insertBefore(burger); }
        else { if (!social.hasClass('is_moved')) social.addClass('is_moved').appendTo(nav); }
      }

      fnAdaptiveHeader();
      win.on('resize.header', fnAdaptiveHeader);
      /*--- End header ---*/

      /*--- Begin accordeon ---*/
      var accordeon = $('.accordeon');

      accordeon.each(function() {
        var _ = $(this);
        var _accordeonHeader = _.find('.accordeon__header');
        var _accordeonBody = _.find('.accordeon__body');

        _accordeonHeader.on('click', function() {
          var _header = $(this);
          var _body = _header.next();

          _accordeonHeader.not(_header).removeClass('is_active');
          _accordeonBody.not(_body).stop().slideUp(300, function() {
            $(this).find('.levels, .levels_tiny').removeClass('is_active');
          });

          _header.toggleClass('is_active');
          _body.stop().slideToggle(300, function() {
            $(this).find('.levels, .levels_tiny').addClass('is_active');
          });
        });
      });
      /*--- End accordeon ---*/

      /*--- Begin tabs ---*/
      var tabs = $('.tabs');

      tabs.each(function() {
        var _ = $(this);
        var _navItem = _.find('.tabs__nav li');
        var _bodyItem = _.find('.tabs__body_item');

        _navItem.on('click', function() {
          var _item = $(this);
          var _itemIndex = _item.index();

          if (_item.hasClass('is_active')) return;

          _item.addClass('is_active').siblings().removeClass('is_active');
          _bodyItem.filter('.is_active').hide(0, function() {
            $(this).removeClass('is_active');
            _bodyItem.find('.levels').removeClass('is_active');
            _bodyItem.eq(_itemIndex).stop().fadeIn(300, function() {
              $(this).addClass('is_active');
              $(this).find('.levels').addClass('is_active');
            });

            if (_bodyItem.eq(_itemIndex).find('.js_slick')) {
              _bodyItem.eq(_itemIndex).find('.js_slick').slick('setPosition');
            }
          });
        });
      });

        $('.tabs__nav.tabs__checkbox li').off('click');
        tabs.each(function() {
            var _ = $(this);
            var _navItem = _.find('.tabs__nav.tabs__checkbox li');
            var _bodyItem = _.find('.tabs__body_item');

            _navItem.on('click', function() {
                var _item = $(this),
                    year = _item.text(),
                    elem = _bodyItem.find('[data-year="'+year+'"]'),
                    allElem = _bodyItem.find('.levels__item');

                if (_item.hasClass('is_active')) {
                    _item.removeClass('is_active');
                    elem.hide(500).addClass('hidden');
                } else {
                    _item.addClass('is_active');
                    elem.show(500).removeClass('hidden');
                }

                allElem.each(function(i, el){
                    var obj = $(el);

                    obj.find('.white').removeClass('white');
                    obj.find('.levels__line ')
                        .filter(':not(.hidden)')
                        .filter(':odd')
                        .find('.levels__width')
                        .addClass('white')
                });

            });
        });
      /*--- End tabs ---*/

      /*--- Begin form ---*/
      var foto = $('input[name="foto"]');
      var addBtn = $('.resume__add_btn');

      if ($('select').length > 0) {
        $('select').select2({
          width: '100%'
        });
      }

      foto.on('change', function() {
        var _ = $(this);
        var _files = _[0].files[0];
        var _preview = _.next().find('img');
        var _reader = new FileReader();

        if (_files) {
          _reader.onload = function() { _preview.attr('src', _reader.result); }
          _reader.readAsDataURL(_files);
        } else _preview.attr('src', 'assets/img/resume/preview.svg');
      });

        function fnClearForm(el) {
            el.find(':input').each(function() {
                var _input = $(this);

                switch(_input.attr('type')) {
                    case 'text':
                    case 'textarea': _input.val('').prop('disabled', false); break;
                    case 'checkbox': _input.prop('checked', false); break;
                }
            });
            el.find('.is_error').removeClass('is_error');
            el.find('.is_disabled').removeClass('is_disabled');
        }


        addBtn.on('click', function(event) {
            event.preventDefault();

            var _ = $(this);
            var _resume = _.parents('.resume__list');
            var _activeItem = _resume.find('.slick-active');

            if (_activeItem.find('.resume__body select').length > 0) {
                _activeItem.find('.resume__body select').select2('destroy');
            }

            var _clone = _activeItem.find('.resume__body').first().clone(false, true);

            fnClearForm(_clone);
            _clone.append('<div class="resume__body_delete"><a class="flex_inline middle" href="#"><span class="icon_close"></span>Удалить</a></div>');
            _clone.hide().insertBefore(_.parent()).show();

            if (_activeItem.find('.resume__body select').length > 0) {
                _activeItem.find('.resume__body select').select2({
                    width: '100%'
                });
            }

            _resume[0].slick.animateHeight();
            $.applyDataMask();
        });

      doc.on('click', '.resume__body_delete a', function(event) {
        event.preventDefault();

        var _ = $(this);
        var _body = _.parents('.resume__body');
        var _resume = _.parents('.resume__list');

        _body.hide().remove();
        _resume[0].slick.animateHeight();
      });

      doc.on('change', 'input[name="until_now[]"]', function() {
        var _ = $(this);
        var _formRow = _.parents('.form__row');
        var _ending = _formRow.find('input[name="ending[]"]');

        if (_.is(':checked')) _ending.val('').prop('disabled', true);
        else _ending.prop('disabled', false);
      });

        var bodyClone;

        doc.on('change', 'input[name="no_experience"]', function() {
            var _ = $(this);
            var _remove = _.parents('.resume__remove');
            var _resume = _.parents('.resume__list');
            var _item = _.parents('.resume__item');
            var _body = _item.find('.resume__body');
            var _add = _item.find('.resume__add');

            if (_.is(':checked')) {
                bodyClone = _body.first().clone(false, true);
                _body.hide().remove();
                _add.hide();
            } else {
                fnClearForm(bodyClone);
                bodyClone.hide().insertAfter(_remove).show();
                $.applyDataMask();
                _add.show();
            }

            _resume[0].slick.animateHeight();
        });

      doc.on('input keypress', '.form__item .is_required', function() {
        var _ = $(this);

        if (_.closest('.resume__list').length) _.closest('.resume__list').slick('setPosition');
        _.parents('.form__item').removeClass('is_error');
      });

      function fnResumeCheck(resume) {
        var activeItem = resume.find('.slick-active');
        var requiredFields = activeItem.find('.is_required');
        var error = false;

        requiredFields.each(function() {
          var _el = $(this);
          var _elParent = _el.parent();

          _elParent.removeClass('is_error');
          resume.slick('setPosition');

          if (_el.val() == '' && !_el.is(':disabled')) {
            _elParent.addClass('is_error');
            resume.slick('setPosition');
            error = true;
          } else {
            if (_el.attr('name') == 'email') {
              var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

              if (!pattern.test(_el.val())) {
                _elParent.addClass('is_error');
                resume.slick('setPosition');
                error = true;
              }
            }
          }
        });

        if(!error) resume.slick('slickNext');
      }
      /*--- End form ---*/

      /*--- Begin js_slick ---*/
      $('.js_slick').each(function() {
        var _ = $(this);
        var _settings = {
          rows: 0,
          speed: 700,
          prevArrow: '<span class="icon_arrow_left2"></span>',
          nextArrow: '<span class="icon_arrow_right2"></span>',
          dotsClass: 'slick-dots flex middle center'
        };

        if (_.hasClass('services__list')) {
          $.extend(_settings, {
            dots: true,
            slidesToShow: 3,
            responsive: [{
              breakpoint: lg + 1,
              settings: {
                slidesToShow: 2
              }
            }, {
              breakpoint: xs + 1,
              settings: {
                slidesToShow: 1,
                adaptiveHeight: true
              }
            }]
          });
        } else if (_.hasClass('resume__list')) {
          $.extend(_settings, {
            arrows: false,
            infinite: false,
            adaptiveHeight: true,
            draggable: false,
            swipe: false,
            touchMove: false
          });

          var steps = $('.resume__steps li');
          var back = $('.resume__back');
          var backBtn = back.find('.btn');
          var next = $('.resume__next');
          var nextBtn = next.find('.btn');
          var submit = $('.resume__submit');

          backBtn.on('click', function(event) {
            event.preventDefault();
            _.slick('slickPrev');
          });

          nextBtn.on('click', function(event) {
            event.preventDefault();
            fnResumeCheck(_);
          });

          _.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            var diff = currentSlide - nextSlide;

            if (nextSlide !== 0) back.stop().fadeIn(300);
            else back.stop().fadeOut(300);

            if (nextSlide == slick.slideCount - 1) {
              next.hide();
              submit.stop().fadeIn(300);
            } else {
              next.stop().fadeIn(300);
              submit.hide();
            }

            if (diff == 1 || diff == (slick.slideCount - 1) * (-1)) {
              steps.eq(nextSlide).removeClass('is_done').addClass('is_active');
              steps.eq(currentSlide).removeClass('is_active');
            } else {
              steps.eq(currentSlide).removeClass('is_active').addClass('is_done');
              steps.eq(nextSlide).addClass('is_active');
            }
          });
        }

        _.slick(_settings);
      });
      /*--- End js_slick ---*/

      /*--- Begin career ---*/
      var careerWrap = $('.career__wrap');
      var careerList = $('.career__list');
      var careerCell = $('.career__cell');
      var careerStep = $('.career__step');
      var careerNext = $('.career__next');

    function fnScrollToStep(id) {
        var careerWrap = $('.career__wrap');
        var careerList = $('.career__list');

        var _ = $('#' + id);
        var _center = _.offset().left + _.outerWidth() / 2;
        var _careerWrapWidth = careerWrap.outerWidth();
        var _careerWrapCenter = careerWrap.offset().left + _careerWrapWidth / 2;
        var _careerListWidth = careerList.outerWidth();
        var _maxX = _careerListWidth - _careerWrapWidth;
        var _translateX = _center - _careerWrapCenter;

        if (_maxX <= 0 || _translateX <= 0) _.trigger('click');
        else {
            if (_translateX >= _maxX) _translateX = _maxX;
            gsap.to(careerList, {
                x: -(_translateX),
                duration: .5,
                onComplete: function() {
                    setTimeout(function() {
                        _.trigger('click');
                    }, 100);
                }
            });
        }
    }

      function fnCareerArrows() {
        if ($('.career__arrow').length) {
          $('.career__arrow').remove();
        }

        $('[data-arrows]').each(function(elIndex) {
          var _ = $(this);
          var _offsetTop = _.offset().top;
          var _offsetRight = _.offset().left + _.outerWidth();
          var _dataArrows = _.data('arrows');

          for (var i = 0; i < _dataArrows.length; i++) {
            var _nextId = $('#' + _dataArrows[i]);

            if (_nextId.length) {
              var _nextIdOffsetTop = _nextId.offset().top;
              var _nextIdOffsetLeft = _nextId.offset().left;
              var _svgClass = 'career__arrow';
              var _svgWidth = _nextIdOffsetLeft - _offsetRight + 3;
              var _svgHeight = Math.abs(_nextIdOffsetTop - _offsetTop) + 10;
              var _cpv = Math.round(_svgWidth * Math.min(_svgHeight / 50, 1));
              var x1 = 3, y1 = 5;
              var x2 = _svgWidth - 5, y2 = _svgHeight - 5;

              if (_nextIdOffsetTop < _offsetTop) {
                _svgClass += ' career__arrow--up';
                y1 = _svgHeight - 5;
                y2 = 5;
              }

              var _arrow = $('<svg class="' + _svgClass + '" width="' + _svgWidth + '" height="' + _svgHeight + '" viewBox="0 0 ' + _svgWidth + ' ' + _svgHeight + '" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                  '<defs>' +
                  '<marker id="arrow_start' + elIndex + '" refX="3" refY="3" markerWidth="6" markerHeight="6" markerUnits="userSpaceOnUse" orient="auto">' +
                  '<circle cx="3" cy="3" r="3" fill="white"/>' +
                  '</marker>' +
                  '<marker id="arrow_end' + elIndex + '" refX="4" refY="5" markerWidth="9" markerHeight="10" markerUnits="userSpaceOnUse" orient="auto">' +
                  '<path d="M0 0L9 5L0 10L2 5L0 0Z" fill="white"/>' +
                  '</marker>' +
                  '</defs>' +
                  '<path d="M' + x1 + ' ' + y1 + ' C'  + (x1 + _cpv) + ' ' + y1 + ' ' + (x2 - _cpv) + ' ' + y2 + ' ' + x2 + ' ' + y2 + '" stroke="white" stroke-width="2" marker-start="url(#arrow_start' + elIndex + ')" marker-end="url(#arrow_end' + elIndex + ')"/>' +
                  '</svg>');

              _arrow.hide().appendTo(_).delay(100 * elIndex).fadeIn(300);
            }
          }
        });
      }

      var done;
      var ww = window.innerWidth;

      win.on('resize.career', function() {
        if (window.innerWidth == ww) return;
        else if ($('.career__info').is(':visible')) {
          ww = window.innerWidth;
          clearTimeout(done);
          done = setTimeout(function() {
            fnCareerArrows();
          }, 100);
        }
      });

      function fnCloseStep() {
        $('.career__step').filter('.is_active').each(function() {
          var _active = $(this);
          var _activeCell = _active.closest('.career__cell');
          var _activeNext = _active.find('.career__next');

          _activeNext.hide();
          _activeCell.height('');
          _active.removeClass('is_active');
        });
      }

        $('.career__info').on('click', '.career__step', function(event) {
        var _ = $(this);
        var _cell = _.closest('.career__cell');
        var _cellHeight = _cell.height();
        var _next = _.find('.career__next');
        var _target = event.target;

        if (!_.hasClass('is_active')) {
          fnCloseStep();

          _.addClass('is_active');
          _cell.height(_cellHeight);
          _next.stop().slideDown(300);
        } else {
          if (!$(_target).hasClass('btn')) {
            _next.stop().slideUp(300, function() {
              _cell.height('');
              _.removeClass('is_active');
            });
          }
        }
      });

      doc.on('click touchstart', function(event) {
        if (!$(event.target).closest('.career__step').length) { fnCloseStep(); }
      });
      /*--- End career ---*/

      /*--- Begin career, salary, trends ---*/
      // это можно удалить или переделать,
      // здесь только логика появления блоков
      $('.career__info').on('showCareer', function(event) {
        event.preventDefault();

        $(this).slideDown(500, function() {
          var _ = $(this);
          body.animate({scrollTop: _.offset().top - header.outerHeight()}, 500).promise().then(function() {
            // это функция появления стрелок,
            // она должна выполнятся после появления блока
            fnCareerArrows();

              Draggable.create($('.career__list'), {
                  bounds: $('.career__wrap'),
                  zIndexBoost: false,
                  onDragStart: fnCloseStep,
                  type: 'x'
              });
                var currentPosition = $('.career__step.current_position');

              console.log(currentPosition.attr('id'));


              fnScrollToStep(currentPosition.attr('id'));

          });
        });

        return false;
      });

      // это можно удалить или переделать,
      // здесь только логика появления блоков
      $('.salary__form').on('submit', function(event) {
        event.preventDefault();

        $('.salary__info').slideDown(500, function() {
          var _ = $(this);

          body.animate({scrollTop: _.offset().top - header.outerHeight()}, 500, function() {
            _.find('.levels').addClass('is_active');
          });
        });

        return false;
      });

      // это можно удалить или переделать,
      // здесь только логика появления блоков
      $('.trends__select_form').on('submit', function(event) {
        event.preventDefault();

        $('.trends__info').slideDown(500, function() {
          var _ = $(this);
          body.animate({scrollTop: _.offset().top - header.outerHeight()}, 500);
        });

        return false;
      });
      /*--- End career, salary, trends ---*/

      /*--- Begin footer ---*/
      var colTitle = $('.footer__col_title');

      colTitle.on('click', function() {
        var _ = $(this);
        var ww = window.innerWidth;

        if (ww <= xs && _.next().length) {
          _.toggleClass('is_active');
          _.next().slideToggle();
        }
      });

      win.on('resize.footer', function() {
        var ww = window.innerWidth;
        if (ww > xs) colTitle.removeClass('is_active').next().removeAttr('style');
      });
      /*--- End footer ---*/

      /*--- Begin js_modal ---*/
      $('.js_modal').on('click', function(event) {
        event.preventDefault();

        var _ = $(this);
        var _id = _.attr('href') || _.data('href');

        $(_id).modal({
          showClose: false,
          fadeDuration: 250
        });
      });
      /*--- End js_modal ---*/


    $(window).on('load', function() {
      var mainImg = $('.main__img_decor');
      setTimeout(function() { mainImg.addClass('is_load'); }, 500);
    });

});
