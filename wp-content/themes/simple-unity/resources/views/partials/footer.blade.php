@php
  $footer_color = get_theme_mod( 'footer_color' );
  $text_color = get_theme_mod( 'footer_text_color' );
@endphp
<footer class="content-info page-footer" role="contentinfo" style="background-color: {{ $footer_color }}">
  <div class="footer-content row flex space-between align-center">
    <div class="footer-left col m4 s12">
      @php dynamic_sidebar('footer-left') @endphp
    </div>
    <div class="footer-center col m4 s12">
      @php dynamic_sidebar('footer-center') @endphp
    </div>
    <div class="footer-right col m4 s12">
      @php dynamic_sidebar('footer-right') @endphp
    </div>
  </div>

  <div class="footer-copyright row flex space-between">
    <div class="footer-left col m4 s12">
      <p class="copyright">&copy; {!! current_time('Y') !!} {!! get_bloginfo('name', 'display') !!}</p>
    </div>
    <div class="footer-center col m4 s12">
      @if (has_nav_menu('footer_links'))
        {!! wp_nav_menu(['theme_location' => 'footer_links', 'container' => FALSE, 'menu_class' => 'footer-links flex flex-center justify-center']) !!}
      @else
        <a href="{{ get_home_url() }}/privacy-policy/">Privacy Policy</a>
      @endif
    </div>
    <div class="footer-right col m4 s12">
      <a href="https://justgetsimple.com/" target="_blank" class="unity-link" rel="noopener nofollow">
        <span class="screen-reader-text">{{ __('Simple by Unity', 'sage') }}</span>
        {{ \BladeSvgSage\svg_image('simple-by-unity', ['class' => 'svg-unity-logo']) }}
      </a>
    </div>
  </div>
</footer>
