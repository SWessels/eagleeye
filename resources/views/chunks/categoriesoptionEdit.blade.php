<option  class="level" data-level="{{ $allcategory['category_level'] }}" @if(isset ($data['category']->parent_category_id) &&
 $data['category']->parent_category_id == $allcategory['category_id']){ selected="selected"  } @endif
value="{{ $allcategory['category_id'] }}">{{ $allcategory['category_name'] }} </option>

	@if (count($allcategory['children']) > 0)
	    @foreach($allcategory['children'] as $allcategory)
	        @include('chunks.categoriesoptionEdit', $allcategory)
	    @endforeach

	@endif--}}