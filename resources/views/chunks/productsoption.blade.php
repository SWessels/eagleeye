
<?php if(isset($product_array) && $product_array != '' ){
?>
<option  @if(in_array($product['id'] , $product_array) )
{ selected="selected"  } @endif
         value="{{ $product['id'] }}">{{ $product['name'] }} </option>
<?php
}else{ ?>
    <option
value="{{ $product['id'] }}">{{ $product['name'] }} </option>
 <?php
}
?>


