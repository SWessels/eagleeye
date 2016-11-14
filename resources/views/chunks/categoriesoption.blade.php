<option  class="level" data-level="{{ $category['category_level'] }}" @if(isset($_GET['category']) && $_GET['category'] == $category['category_id'])
{ selected="selected"  } @endif
value="{{ $category['category_id'] }}">{{ $category['category_name'] }} </option>

	@if (count($category['children']) > 0)
	    @foreach($category['children'] as $category)
	        @include('chunks.categoriesoption', $category)
	    @endforeach
	   
	@endif