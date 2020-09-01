<article class="excerpt">
  @if (has_post_thumbnail())
    {!! the_post_thumbnail('large') !!}
  @else
    <div class="overlay"></div>
  @endif

  <div class="entry-summary" itemprop="description">
    <h2 class="entry-title h3" itemprop="name"><a href="{{ get_permalink() }}">{!! get_the_title() !!}</a></h2>
    @include('partials/entry-meta')

    @php the_excerpt(140) @endphp
  </div>
</article>
