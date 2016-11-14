<option  class="level" data-level="" @if(isset($data['menu_id']) && $data['menu_id']  == $menu['id'])
{ selected="selected"  } @endif
         value="{{ $menu['id'] }}">{{ $menu['title'] }} </option>