@if (get_theme_mod('related_posts') && $related_posts->have_posts())
  <section id="related-posts" class="related-posts">
    <h2 class="h3">{{ __('Related Posts', 'sage') }}</h2>
    <div class="related-posts__grid">
      @while ($related_posts->have_posts())
        @php $related_posts->the_post(); @endphp
        @include('partials.content-'.get_post_type(), [
          'class' => 'related-posts__post excerpt',
        ])
      @endwhile
    </div>
  </section>
@endif
