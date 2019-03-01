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
      <p class="copyright">&copy; {!! current_time('Y') !!} {{ get_bloginfo('name', 'display') }}</p>
    </div>
    <div class="footer-center col m4 s12">
      @if (has_nav_menu('footer_links'))
        {!! wp_nav_menu(['theme_location' => 'footer_links', 'container' => FALSE, 'menu_class' => 'flex flex-center']) !!}
      @else
        <a href="{{ get_home_url() }}/privacy-policy/">Privacy Policy</a>
      @endif
    </div>
    <div class="footer-right col m4 s12">
      @include('partials.unity')
    </div>
  </div>

</footer>
