<li class="parent" data-parent-value="{{ $category['category_id'] }}">
	<input class="product-category custom qe_category_{{ $product->id }}" type="checkbox" name="qe_category"  value="{{ $category['category_id'] }}_{{ $category['category_name'] }}" @foreach($selected as $pcat) @if($pcat->name == $category['category_name'])  checked="checked" @endif @endforeach> {{ $category['category_name'] }}
</li>
	@if (count($category['children']) > 0)
	    <ul>
	    @foreach($category['children'] as $category)
	        @include('chunks.categories', $category)
	    @endforeach
	    </ul> 
	@endif