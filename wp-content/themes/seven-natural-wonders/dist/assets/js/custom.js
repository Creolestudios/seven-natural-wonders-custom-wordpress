(function ($) {
  $(document).ready(function () {
    jQuery('.home-banner-slider, .did-you-know-slider').slick({
      arrows: true,
      centerPadding: '0px',
      dots: false,
      slidesToShow: 1,
      infinite: false,
    });

    jQuery('.animal-detail-banner-slider').slick({
      arrows: false,
      centerPadding: '0px',
      dots: true,
      slidesToShow: 1,
      infinite: false,
    });

    jQuery('.explore-wild-life-slider').slick({
      arrows: true,
      centerPadding: '250px',
      dots: false,
      slidesToShow: 3,
      centerMode: true,
      responsive: [
        {
          breakpoint: 1500,
          settings: {
            centerPadding: '80px',
          },
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2,
            centerPadding: '120px',
          },
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 1,
            centerPadding: '180px',
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            centerPadding: '0',
          },
        },
        {
          breakpoint: 250,
          settings: {
            slidesToShow: 1,
            centerPadding: '80px',
          },
        },
      ],
    });

    jQuery('.home .explore-the-diversity-card-wrapper').slick({
      arrows: true,
      dots: false,
      slidesToShow: 4,
      infinite: false,
      responsive: [
        {
          breakpoint: 1500,
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 650,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 250,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });

    jQuery('.home .explore-the-diversity-card-wrapper').slick({
      arrows: true,
      dots: false,
      slidesToShow: 4,
      infinite: false,
      responsive: [
        {
          breakpoint: 1500,
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 650,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 250,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });

    var $exploreDiversitySlider = jQuery(
      '.animaldetail-container .explore-the-diversity-card-wrapper'
    );

    $exploreDiversitySlider.on('init', function (event, slick) {
      var slidesCount = slick.slideCount;
      var slidesToShow = slidesCount <= 2 ? slidesCount : 4;
      if (slidesCount == 1) {
        var slidesToShow = 2;
      }
      slick.options.slidesToShow = slidesToShow;
    });

    $exploreDiversitySlider.slick({
      arrows: true,
      dots: false,
      slidesToShow: 4,
      infinite: false,
      responsive: [
        {
          breakpoint: 1500,
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 650,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 250,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });

    jQuery('.see-all-picture-item-wrapper').slick({
      arrows: true,
      dots: false,
      slidesToShow: 3,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 400,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 250,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });
    jQuery(
      '.animal-detail-banner-slider-wrapper .image-popup-vertical-fit'
    ).magnificPopup({
      type: 'image',
      mainClass: 'mfp-with-zoom',
      gallery: {
        enabled: true,
      },

      zoom: {
        enabled: true,
        duration: 300, // duration of the effect, in milliseconds
        easing: 'ease-in-out', // CSS transition easing function
        opener: function (openerElement) {
          return openerElement.is('img')
            ? openerElement
            : openerElement.find('img');
        },
      },
    });

    jQuery('.see-all-picture-wrapper .image-popup-vertical-fit').magnificPopup({
      type: 'image',
      mainClass: 'mfp-with-zoom',
      gallery: {
        enabled: true,
      },
      zoom: {
        enabled: true,
        duration: 300,
        easing: 'ease-in-out',
        opener: function (openerElement) {
          // Update this part to target the div with background image
          return openerElement.is('div')
            ? openerElement
            : openerElement.find('div');
        },
      },
    });

    jQuery('.modal-popup').each(function () {
      jQuery(this).on('click', function () {
        jQuery('.modal').show();
        jQuery('body').css('overflow', 'hidden');
        const imgSrc = jQuery(this).attr('src');
        jQuery('.modal .modal-content').attr('src', imgSrc);

        var bg_img = $(this)
          .css('background-image')
          .replace(/^url\(['"](.+)['"]\)/, '$1');
        jQuery('.modal .modal-content').attr('src', bg_img);
      });
    });

    jQuery('.modal-popup').each(function () {
      jQuery(this).on('click', function () {
        jQuery('.modal').show();
        jQuery('body').css('overflow', 'hidden');
        var imgSrc;
        if (jQuery(this).find('img').length > 0) {
          imgSrc = jQuery(this).find('img').attr('src');
        } else if (jQuery(this).is('div')) {
          var bg_img = jQuery(this)
            .css('background-image')
            .replace(/^url\(['"](.+)['"]\)/, '$1');
          imgSrc = bg_img;
        }
        jQuery('.modal .modal-content').attr('src', imgSrc);
      });
    });

    jQuery('.modal .close').on('click', function () {
      jQuery('.modal').hide();
      jQuery('body').css('overflow', 'auto');
    });

    // select
    var itemValue;

    const selectValue = document.querySelectorAll('#reason option');

    $('.select-dropdown__button').on('click', function () {
      $(this).toggleClass('active');
      $(this).next().toggleClass('active');
    });

    $('.mobile-hamburger-menu .menu-icon').on('click', function () {
      $('.menu-icon').toggleClass('open');
      $('.mobile-menu').toggleClass('open');
      $('body').toggleClass('fixed');
    });

    $('.mobile-menu ul li').on('click', function () {
      $(this).addClass('active').siblings().removeClass('active');
    });

    $('.mobile-menu ul li').each(function (i, elem) {
      if ($(this).find('ul').length > 0) {
        $(this).children('label').append('<i></i>');
      } else {
        $(this).addClass('no-ul');
      }
    });

    $('.select-dropdown__list .select-dropdown__list-item').on(
      'click',
      function () {
        itemValue = $(this).data('value');
        $(this)
          .parent('.select-dropdown__list')
          .prev()
          .children('span')
          .text($(this).text())
          .parent()
          .attr('data-value', itemValue);
        // $(".select-dropdown__button span")
        //   .text($(this).text())
        //   .parent()
        //   .attr("data-value", itemValue);
        $(this).parent('.select-dropdown__list').prev().toggleClass('active');
        $(this).parent('.select-dropdown__list').toggleClass('active');

        selectValue.forEach((ele) => {
          if (itemValue === ele.attributes.value.value) {
            ele.setAttribute('selected', 'selected');
          }
        });
      }
    );

    // var tab_name;
    // var tab_content_attr;

    // let tab_ele = document.querySelectorAll('.tab-contents .tab-content')

    // $('.tab-list li').on('click', function(){
    //   $(this).addClass('active').siblings().removeClass('active');
    //   tab_name = $(this).children('label').attr('data-value');
    // })

    // tab_ele.forEach(ele => {
    //   tab_content_attr = $(ele).attr('data-value')
    //   tab_name === tab_content_attr ? $(ele).addClass('active') : $(ele).removeClass('active');
    // })

    // for faqs
    $('.faqs .faq').on('click', function () {
      $(this).toggleClass('active').siblings().removeClass('active');
    });

    var windowWidth = window.innerWidth;

    if (windowWidth < 992) {
      $('.policy-inner .add-banner').insertAfter($('.banner-inner-page'));
    }

    if (windowWidth < 1200) {
      $('.see-all-picture-wrapper').removeClass('picture-gallery');
    }
  });
})(jQuery);

(function () {
  // document.addEventListener('DOMContentLoaded', function () {
  //   var label = document.querySelector('label[for="tab2"]');
  //   label.addEventListener('click', function () {
  //     window.scrollTo({ top: 0, behavior: 'smooth' });
  //   });
  // });

  document.addEventListener('DOMContentLoaded', function () {
    function scrollToLabel(labelId) {
      var label = document.querySelector('label[for="' + labelId + '"]');
      if (label) {
        var labelTop = label.getBoundingClientRect().top + window.scrollY;
        console.log('Scrolling to label', labelId);
        window.scrollTo({ top: labelTop - 70, behavior: 'smooth' });
      }
    }

    document.querySelectorAll('input[name="tabs"]').forEach(function (input) {
      input.addEventListener('click', function () {
        var labelId = this.getAttribute('id');
        scrollToLabel(labelId);
      });
    });
  });
})();
