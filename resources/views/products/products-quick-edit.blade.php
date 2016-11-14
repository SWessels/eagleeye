<table class="table quick_edit_table">
                                    <tbody>
                                    <tr>
                                        <td width="33.3333%">
                                            <div class="form-group" id="qe_form_group_name">
                                                <label class="col-md-12 control-label">Naam</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="qe_product_name" id="qe_product_name_{{ $product->id }}" value="{{ $product->name }}" class="form-control input-sm" placeholder="Enter Name">
                                                    <span class="help-block" id="qe_er_product_name_{{ $product->id }}">  </span>
                                                </div>
                                            </div>
                                            <div class="form-group" id="qe_form_group_slug">
                                                <label class="col-md-12 control-label">Slug</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="qe_product_slug" id="qe_product_slug_{{ $product->id }}" value="{{ $product->slug }}" class="form-control input-sm qe_make_slug" placeholder="Enter Slug" onchange="makeSlug({{ $product->id }})">
                                                    <span class="help-block" id="qe_er_product_slug_{{ $product->id }}">  </span>
                                                </div>
                                            </div>
                                            @if($product->product_type == 'simple')
                                                <div class="form-group" id="qe_form_group_sku">
                                                    <label class="col-md-12 control-label">SKU</label>
                                                    <div class="col-md-12">
                                                        <input type="text" name="qe_product_sku" id="qe_product_sku_{{ $product->id }}" value="{{ $product->sku }}" class="form-control input-sm" placeholder="Enter SKU" onchange="checkSkuOnChange({{ $product->id }})">
                                                        <span class="help-block" id="qe_er_product_sku_{{ $product->id }}">  </span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group" id="qe_form_group_date">
                                                <label class="col-md-12 control-label">Datum</label>
                                                <div class="col-md-12">
                                                    <?php
                                                        $dateExp = explode(' ',$product->published_at);

                                                        $datePart = $dateExp[0];
                                                        $datePartExp = explode('-',$datePart);
                                                        $yy = $datePartExp[0];
                                                        $mm = $datePartExp[1];
                                                        $dd = $datePartExp[2];

                                                        $timePart = $dateExp[1];
                                                        $timePartExp = explode(':',$timePart);
                                                        $hr = $timePartExp[0];
                                                        $min = $timePartExp[1];
                                                        ?>

                                                        <select style="width:80px; padding:0px; float:left;" class="form-control input-sm" name="mm" id="mm_{{ $product->id }}"  >
                                                            @for($i = 1 ; $i<= 12; $i++)
                                                                {{ $i = sprintf("%02d", $i) }}
                                                                <option value="{{ $i }}" <?php if($i == $mm)  { echo "selected" ;} ?>   >{{ $i }}- {{  date("M", mktime(0, 0, 0, $i, 10)) }}</option>
                                                            @endfor
                                                        </select>

                                                        <input style="width:20px; padding:0px; display: inline; margin: 0 4px; " class="form-control input-sm" size="2" type="text" name="dd" id="dd_{{ $product->id }}" value="{{$dd}}" >,
                                                        <input style="width:40px; padding:0px; display: inline; margin: 0 4px;" class="form-control input-sm" size="4" type="text" name="yy" id="yy_{{ $product->id }}" value="{{$yy}}" >@
                                                        <input style="width:20px; padding:0px; display: inline; margin: 0 4px;"  class="form-control input-sm"  size="2" type="text" name="hr" id="hr_{{ $product->id }}" value="{{$hr}}" >:
                                                        <input style="width:20px; padding:0px; display: inline; margin: 0 4px;" class="form-control input-sm" size="2" type="text" name="min" id="min_{{ $product->id }}" value="{{$min}}" >

                                                        <span class="help-block" id="qe_er_product_date_{{ $product->id }}">  </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h3>Productgegevens</h3>
                                                </div>
                                            </div>

                                            <div class="form-group" id="qe_form_group_regular_price">
                                                <label class="col-md-12 control-label">Reguliere prijs</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="qe_regular_price" id="qe_regular_price_{{ $product->id }}" value="{{ moneyForField($product->regular_price) }}" class="form-control input-sm" placeholder="Regular Price">
                                                    <span class="help-block" id="qe_er_regular_price_{{ $product->id }}">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group" id="qe_form_group_sale_price">
                                                <label class="col-md-12 control-label">Sale prijs</label>
                                                <div class="col-md-12">
                                                    <input type="text" name="qe_sale_price" id="qe_sale_price_{{ $product->id }}" value="{{ moneyForField($product->sale_price) }}" class="form-control input-sm" placeholder="Sale Price">
                                                    <span class="help-block" id="qe_er_sale_price_{{ $product->id }}">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group" >
                                                <label class="col-md-12 control-label">Zichtbaarheid</label>
                                                <div class="col-md-12">
                                                    <select name="qe_visible" id="qe_visible_{{ $product->id }}" class="form-control input-sm" >
                                                        <option value="visible"   @if($product->visibilty == 'visible' ) selected = "selected" @else  @endif>Visible</option>
                                                        <option value="hidden"   @if($product->visibilty == 'hidden' ) selected = "selected" @else  @endif>Hidden</option>

                                                    </select>
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group" id="qe_form_group_{{ $product->id }}">
                                                <label class="col-md-12 control-label">Voorraadstatus</label>
                                                <div class="col-md-12">
                                                    <select class="form-control input-sm"  name="qe_stock_status" id="qe_stock_status_{{ $product->id }}">
                                                        <option value="in" @if($product->inventories && $product->inventories->stock_status == 'in') selected="selected" @endif>In voorraad</option>
                                                        <option value="out" @if($product->inventories && $product->inventories->stock_status == 'out') selected="selected" @endif>Niet in voorraad</option>
                                                    </select>
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>



                                        </td>
                                        <td width="33.3333%">

                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Product tags</label>
                                                <div class="col-md-12">
                                                    <textarea name="qe_tags" class="form-control" id="qe_tags_{{ $product->id }}" rows="5" cols="35">@foreach($product->tags as $tag) {{ $tag->name }}, @endforeach</textarea>
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Status</label>
                                                <div class="col-md-12">
                                                    <select name="qe_product_status" id="qe_product_status_{{ $product->id }}" class="form-control input-sm" >
                                                        <option value="publish"    @if($product->status == 'publish' ) selected = "selected"  @endif>Gepubliceerd</option>
                                                        <option value="deleted"  @if($product->status == 'deleted' ) selected = "selected" @endif >Verwijderd</option>
                                                        <option value="draft" @if($product->status == 'draft' ) selected = "selected" @endif >Concept</option>
                                                    </select>
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Upsells</label>
                                                <div class="col-md-12" id="col_qe_product_upsells_{{ $product->id }}">
                                                    <select name="qe_product_upsells[]" multiple id="qe_product_upsells_{{ $product->id }}" class="form-control input-sm select2 up_sells" >
                                                    </select>
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Cross Sells</label>
                                                <div class="col-md-12" id="col_qe_product_crosssells_{{ $product->id }}">
                                                    <select name="qe_product_crosssells[]" multiple id="qe_product_crosssells_{{ $product->id }}" class="form-control input-sm select2 cross_sells" >
                                                    </select>
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>

                                        </td>
                                        <td width="33.3333%">
                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Categorieën</label>
                                                <div class="col-md-12">
                                                    <ul class="all-product-categories">
                                                        @if (count($categories) > 0)

                                                                @foreach ($categories  as $category)
                                                                    @include('chunks.categoriesforquickedit', array('category' => $category, 'selected' => $product->categories))
                                                                @endforeach

                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-12 control-label">Color Swatches</label>
                                                <div class="col-md-12">

                                                    <div class="col-md-9">
                                                        <select class="form-control select2" id="color_swatches_products">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button class="btn btn-default btn-sm" type="button"
                                                                onclick="addSwatch({{ $product->id  }})">Toevoegen
                                                        </button>
                                                    </div>
                                                    <div class="color_swatches_div">
                                                    @if(count($product->color_swatches)>0)
                                                        @foreach($product->color_swatches as $color)
                                                            <div class="col-md-12 " id="rm_swatch_{{ $color['id'] }}">{{ $color['name'] }}
                                                                <a href="javascript:;" class="pull-right" onclick="deleteSwatch('{{ $color['id'] }}', '{{ $product->id }}')">Verwijderen</a>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="col-md-12" id="no_color_swatch">
                                                            <strong>Geen color swatches gevonden</strong>
                                                        </div>
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>


                                        </td>
                                    </tr>

                                    </tbody>
                                </table>