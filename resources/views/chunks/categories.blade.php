<li class="parent" data-parent-value="{{ $category['category_id'] }}">
	<input class="product-category custom" type="checkbox" value="{{ $category['category_id'] }}" @if(isset($category['selected']))   {{ $category['selected'] }} @endif> {{ $category['category_name'] }}</li>
	@if (count($category['children']) > 0)
	    <ul>
	    @foreach($category['children'] as $category)
	        @include('chunks.categories', $category)
	    @endforeach
	    </ul> 
	@endif