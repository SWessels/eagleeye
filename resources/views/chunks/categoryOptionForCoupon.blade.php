<?php if(isset($category_array) &&$category_array != '') {?>
<option  class="level" data-level="{{ $category['category_level'] }}" @if(in_array($category['category_id'], $category_array))
{ selected="selected"  } @endif
         value="{{ $category['category_id'] }}">{{ $category['category_name'] }} </option>

@if (count($category['children']) > 0)
    @foreach($category['children'] as $category)
        @include('chunks.categoryOptionForCoupon', $category)
    @endforeach

@endif

<?php }
else{ ?>
<option  class="level" data-level="{{ $category['category_level'] }}"
         value="{{ $category['category_id'] }}">{{ $category['category_name'] }} </option>

@if (count($category['children']) > 0)
    @foreach($category['children'] as $category)
        @include('chunks.categoryOptionForCoupon', $category)
    @endforeach

@endif

<?php
}
?>