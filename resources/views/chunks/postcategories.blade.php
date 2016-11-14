<li class="parent"  data-parent-value="{{ $category['category_id'] }}">

<input name="categories[]" id="categories[]" class="post-category custom" type="checkbox" @if( isset($category['selected']) && $category['selected'] == 'Selected') checked = 'checked' @endif value="{{ $category['category_id'] }}" > {{ $category['category_name'] }}</li>


@if (count($category['children']) > 0)
    <ul>
        @foreach($category['children'] as $category)
            @include('chunks.postcategories', $category)
        @endforeach
    </ul>
@endif