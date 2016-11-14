<tr>
    <td><input name="del[]" id="del['{{ $category['category_id']}}']" value="{{ $category['category_id'] }}"
               type="checkbox" class="checkboxes">{{--<input type="text" name="parent_id"  value="{{ $category['parent_id'] }}"  />--}}</td>

    <td align="left"> @for($i = 1; $i<=$category['category_level']; $i++) - @endfor


        <a href="  {{ route('PostCategories.edit',$category['category_id']) }}">
            {{ $category['category_name'] }} </a></td>
    <td> {{ $category['description'] }} </td>
    <td> {{ $category['category_slug'] }}</td>
    <td> {{ $category['product_count']  }} </td>
    <!--<td>
		<div class="actions">
			<div class="btn-group">
				<a class="btn btn-xs orders btn-default dropdown-toggle" href="javascript:;" data-toggle="dropdown">
					<span class="hidden-xs"> Actions </span>
					<i class="fa fa-angle-down"></i>
				</a>
				<div class="dropdown-menu pull-right">
					<li>
						<a href="{{ URL::to('categories/' . $category['category_id'] . '/edit') }}"> <i class="fa fa-pencil"></i> Edit </a>
					</li>
				</div>
			</div>
		</div>
	</td>-->
</tr>

@if (count($category['children']) > 0)
    @foreach($category['children'] as $category)
        @include('chunks.postcategorieslist', $category)
    @endforeach

@endif