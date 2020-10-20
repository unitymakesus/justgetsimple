@php
  $topic_list = wp_get_post_terms(get_the_id(), 'category', array('fields' => 'all'));
@endphp
<div class="meta">
  <span class="label label--publish">
    <svg class="meta-icon" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 299.998 299.998"><path d="M149.997 0C67.157 0 .001 67.158.001 149.995s67.156 150.003 149.995 150.003 150-67.163 150-150.003S232.836 0 149.997 0zm10.358 168.337c-.008.394-.067.788-.122 1.183-.039.296-.057.599-.124.89-.067.303-.182.602-.28.905-.117.366-.226.731-.379 1.076-.029.06-.039.124-.065.184-.226.482-.488.934-.775 1.362-.018.026-.042.052-.06.078-.327.48-.7.916-1.092 1.325-.109.112-.22.213-.335.319-.345.329-.708.63-1.094.905-.119.086-.233.176-.358.259-.495.324-1.014.609-1.554.843-.117.052-.239.083-.358.13a10.425 10.425 0 01-1.909.542c-.612.112-1.232.189-1.86.189-.127 0-.257-.039-.384-.044-.602-.023-1.198-.07-1.771-.192-.179-.039-.355-.117-.534-.166a10.53 10.53 0 01-1.554-.529c-.057-.029-.117-.034-.174-.06l-57.515-27.129c-5.182-2.443-7.402-8.626-4.959-13.808 2.443-5.179 8.626-7.402 13.808-4.959l42.716 20.144V62.249c0-5.729 4.645-10.374 10.374-10.374s10.374 4.645 10.374 10.374V168.15h.002c0 .062-.018.124-.018.187z"/></svg>
    <time class="date updated published" datetime="{{ get_post_time('c', true) }}" itemprop="datePublished">{{ get_the_date('F j, Y') }}</time>
  </span>

  @if (!empty($topic_list))
    @php
      foreach ($topic_list as &$topic) :
        $topic = '<span itemprop="about"><a href="' . get_term_link($topic->term_id) . '">' . $topic->name . '</a></span>';
      endforeach;
    @endphp
  <span class="label label--category">
    <svg class="meta-icon" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M14 23.5a.566.566 0 00-.545.417L2 52.5v1c0 .734-.047 1 .565 1h44.759c1.156 0 2.174-.779 2.45-1.813L60 24.5v-1H14z"/><path d="M12.731 21.5H54v-6.268a2.735 2.735 0 00-2.732-2.732H26.515l-5-7H2.732A2.736 2.736 0 000 8.232v41.796l10.282-26.717c.275-1.032 1.293-1.811 2.449-1.811z"/></svg>
    {!! implode(', ', $topic_list) !!}
  </span>
  @endif
</div>
