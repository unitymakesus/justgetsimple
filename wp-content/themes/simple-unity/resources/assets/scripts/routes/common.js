import prefersReducedMotion from '../util/prefersReducedMotion';

export default {
  init() {
    // Add a class to the body for disabling CSS-based animations.
    document.body.className += ' ' + (prefersReducedMotion() ? 'prefers-reduced-motion' : 'prefers-motion');

    // missing forEach on NodeList for IE11
    if (window.NodeList && !NodeList.prototype.forEach) {
      NodeList.prototype.forEach = Array.prototype.forEach;
    }
  },
  finalize() {
    // Media query
    var smDown = window.matchMedia( '(max-width: 768px)' );

    // Show a11y toolbar
    function showA11yToolbar() {
      $('body').addClass('a11y-tools-active');
      $('#a11y-tools-trigger + label i').attr('aria-label', 'Hide accessibility tools');

      // Enable focus of tools using tabindex
      $('.a11y-tools').each(function() {
        var el = $(this);
        $('input', el).attr('tabindex', '0');
      });
    }

    // Hide a11y toolbar
    function hideA11yToolbar() {
      $('body').removeClass('a11y-tools-active');
      $('#a11y-tools-trigger + label i').attr('aria-label', 'Show accessibility tools');

      // Disable focus of tools using tabindex
      $('.a11y-tools').each(function() {
        var el = $(this);
        $('input', el).attr('tabindex', '-1');
      });
    }

    // Toggle a11y toolbar
    $('#a11y-tools-trigger').on('change', function() {
      if (smDown.matches) {
        if ($(this).prop('checked')) {
          showA11yToolbar();
        } else {
          hideA11yToolbar();
        }
      }
    });

    // Make a11y toolbar keyboard accessible
    $('.a11y-tools').on('focusout', 'input', function() {
      setTimeout(function () {
        if (smDown.matches) {
          if ($(':focus').closest('.a11y-tools').length == 0) {
            $('#a11y-tools-trigger').prop('checked', false);
            hideA11yToolbar();
          }
        }
      }, 200);
    });

    // Controls for changing text size
    $('#text-size input[name="text-size"]').on('change', function() {
      let tsize = $(this).val();
      $('html').attr('data-text-size', tsize);
      document.cookie = 'data_text_size=' + tsize + ';max-age=31536000;path=/';
    });

    // Controls for changing contrast
    $('#toggle-contrast input[name="contrast"]').on('change', function() {
      let contrast = $(this).is(':checked');
      $('html').attr('data-contrast', contrast);
      document.cookie = 'data_contrast=' + contrast + ';max-age=31536000;path=/';
    });

    /**
     * Toggle navigation.
     */
    // Toggle mobile nav
    $('#menu-trigger').on('click', function() {
      $('body').toggleClass('mobilenav-active');

      // Toggle aria-expanded value.
      $(this).attr('aria-expanded', (index, attr) => {
        return attr == 'false' ? 'true' : 'false';
      });

      // Toggle icon.
      $(this).find('i').text((i, text) => {
        return text == 'menu' ? 'close' : 'menu';
      });

      // Toggle aria-label text.
      $(this).attr('aria-label', (index, attr) => {
        return attr == 'Show navigation menu' ? 'Hide navigation menu' : 'Show navigation menu';
      });
    });

    /**
     * Flyout menus (hover behavior).
     */
    let menuItems = document.querySelectorAll('li.menu-item-has-children');
    menuItems.forEach((menuItem) => {
      $(menuItem).on('mouseenter', function() {
        $(this).addClass('open');
      });
      $(menuItem).on('mouseleave', function() {
        $(menuItems).removeClass('open');
      });
    });

    /**
     * Flyout menus (keyboard behavior).
     */
    menuItems.forEach((menuItem) => {
      $(menuItem).find('.menu-toggle').on('click', function(event) {
        let expanded = this.getAttribute('aria-expanded') === 'true' || false;
        this.setAttribute('aria-expanded', !expanded);
        $(menuItem).toggleClass('open');

        event.preventDefault();
        return false;
      });
    });

    /**
     * Form label controls
     */
    $('.wpcf7-form-control-wrap').children('input[type="text"], input[type="email"], input[type="tel"], textarea').each(function() {
      // Remove br
      $(this).parent().prevAll('br').remove();

      // Set field wrapper to active
      $(this).on('focus', function() {
        $(this).parent().prev('label').addClass('active');
      });

      // Remove field wrapper active state
      $(this).on('blur', function() {
        var val = $.trim($(this).val());

        if (!val) {
          $(this).parent().prev('label').removeClass('active');
        }
      });
    });

    $('.wpcf7-form-control-wrap').find('.has-free-text').each(function() {
      var $input = $(this).find('input[type="radio"], input[type="checkbox"]');

      $input.on('focus', function() {
        $input.parent().addClass('active');
      })
    });

    /**
     * Show back to top if past first window
     */
   const scrollToTopButton = document.getElementById('back-to-top');
   if (scrollToTopButton) {
     const scrollFunc = () => {
        let y = window.scrollY;
        const h = window.innerHeight;

        if (y > h) {
          scrollToTopButton.className = 'back-to-top show';
        } else {
          scrollToTopButton.className = 'back-to-top';
        }
      };
      window.addEventListener('scroll', scrollFunc);

      /**
       * Scroll to top of window if person clicks back to top button
       */
      const scrollToTop = () => {
        // Let's set a variable for the number of pixels we are from the top of the document.
        const c = document.documentElement.scrollTop || document.body.scrollTop;

        // If that number is greater than 0, we'll scroll back to 0, or the top of the document.
        if (c > 0) {
          window.requestAnimationFrame(scrollToTop);
          window.scrollTo(0, c - c / 10);
        }
      };

      scrollToTopButton.onclick = function(e) {
        e.preventDefault();
        scrollToTop();

        // Set proper focus after scrolling to top
        var $target = $('body');
        $target.focus(); // Setting focus
        if ($target.is(':focus')){ // Checking if the target was focused
          return false;
        } else {
          $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
          $target.focus(); // Setting focus
        }
        return false;
      }
    }
  },
};
