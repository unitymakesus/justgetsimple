<article class="{{ $class }}">
  @if (has_post_thumbnail())
    {!! the_post_thumbnail('medium_large') !!}
  @else
    <div class="overlay"></div>
  @endif

  <div class="entry-summary" itemprop="description">
    @if (is_single())
      <h3 class="entry-title h4" itemprop="name"><a class="a11y-link-wrap" href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h3>
    @else
      <h2 class="entry-title h3" itemprop="name"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h2>
      @include('partials/entry-meta')
      @php the_excerpt(140) @endphp
    @endif
  </div>
</article>
