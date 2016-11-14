@extends('layouts.master')

@section('css')

    @endsection

    @section('content')

            <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <span>Blogberichten</span>
            </li>
        </ul>

    </div>
    <br>

    {{-- pae content --}}

    <div class="row">
        <div class="col-md-12">
            @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    {{ Session::get('flash_message') }}
                </div>
                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                        <!-- Begin: life time stats -->
                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-Products"></i>Blogberichten</div>
                        <div class="actions">


                            <a href="{{ route('posts.create') }}" class="btn orders btn-info">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-xs"> Nieuw Blogbericht</span>
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-6 npl">
                                        <a href="{{ url('posts') }}"> Alle <span class="badge badge-success">{{$postCount['all']}}</span></a>  | <a href="{{ url('posts?status=publish') }}">Gepubliceerd</a> <span class="badge badge-primary">{{ $postCount['published'] }}</span> | <a href="{{ url('posts?status=draft') }}">Concept</a> <span class="badge badge-primary">{{$postCount['draft']}}</span> | <a href="{{ url('posts?status=deleted') }}">Verwijderen <span class="badge badge-primary"> {{$postCount['deleted']}}</span> </a>


                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-12 npl">

                                        <form  {{action('PostsController@index')}} class="form-horizental">
                                        <div class="col-sm-3 pull-right npr">

                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                                    <span class="input-group-btn">
                                                        <button class="btn red" type="submit">Zoeken</button>
                                                    </span>
                                                </div>
                                                <!-- /input-group -->
                                            </div>

                                        </div>
                                         </form>
                                            <div class="clearfix"></div>
                                </div>
                            </div>
                                  <form role="form" name="post_delform" id="post_delform" action=" {{action('PostsController@delete')}} " method="post">
                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                 <div class="row">
                                                     <div class="col-md-12">
                                                             <div class="col-sm-2 npl">
                                                                    <select  name="remove" id="remove" class="form-control input-sm">
                                                                      <option value="">Acties</option>
                                                                     <option value="1">Work</option>
                                                                    <option value="rm">Verwijderen</option>
                                                                    </select>
                                                             </div>
                                                             <div class="col-sm-5 npall">
                                                    <button type="submit" class="btn btn-sm">Toepassen</button>
                                                </div>



                                                     </div>

                                                    <div class="clearfix"></div>

                                                 </div>

                                      <div class="row">
                                          <div class="col-md-12">
                                              <div class="pull-right">
                                                  @if(count($data['posts']) > 0)

                                                      {!! $data['posts']->appends(Input::except('page'))->links() !!}

                                                  @endif
                                              </div>
                                          </div>

                                          <div class="clearfix"></div>

                                      </div>
                            <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                                <thead>
                                <tr role="row" class="heading">
                                    <th width="5%">
                                        <input type="checkbox" class="group-checkable" id="ck">
                                    </th>

                                    <th width="20%"> Titel </th>
                                    <th width="20%"> Auteur </th>
                                    <th width="15%"> Categorieën  </th>
                                    <th width="15%"> Tags </th>
                                    <th width="15%"> Opmerkingen </th>
                                    <th width="15%"> Datum </th>

                                </tr>

                                </thead>
                                <tbody>
                                @if(count($data['posts']) > 0)

                                    @foreach($data['posts'] as $post)



                                        <tr>
                                            <td>
                                                <input name="del[]"  id="del['{!! $post['id'] !!}']" value="{!! $post['id'] !!}"
                                                       type="checkbox" class="checkboxes">

                                            </td>

                                            <td>
                                                <a href="{{ route('posts.edit',$post['id']) }}" data-hover="dropdown"
                                                   data-delay="500" data-close-others="true"> {!! $post['title'] !!}  </a>
                                                <div class="product_menu" id="">
                                                    <span><a href="{{ route('posts.edit',$post['id']) }}">Bewerken</a> </span>
                                                    <span><a href="{{ route('posts.edit',$post['id']) }}">Snel bewerken</a> </span>
                                                    <span><a href="{{ route('posts.destroy',$post['id']) }}"   data-method="delete" data-token="{{csrf_token()}}" data-confirm="Weet je het zeker?">Verwijderen</a> </span>
                                                    <span><a href="#">Kopieer</a> </span></div>

                                            </td>
                                            <td>{!! $post->getauthorName($post['author']) !!}    </td>
                                            <td> <?php  $i = 1 ;  ?>
                                                @foreach($post['PostCategories'] as $categories)
                                                    {{ $categories['name'] }}@if($i++ != count($post['PostCategories'])),@endif
                                                @endforeach</td>
                                            <td><?php  $t = 1 ;  ?> @foreach($post['PostTags'] as $tags)
                                                    {{ $tags['name'] }}@if($t++ != count($post['PostTags'])),@endif
                                                @endforeach</td>
                                            <td>


                                            </td>
                                            <td>{!! date('d M Y', strtotime($post['published_at'])) !!} </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td align="center" colspan="8"><h4>Geen berichten gevonden</h4></td>
                                    </tr>
                                @endif


                                </tbody>
                            </table>
                            @if(count($data['posts']) > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                        {!! $data['posts']->appends(Input::except('page'))->links() !!}
                                        {{--{!! $data['products']->links() !!}--}}
                                            </div>
                                    </div>
                                </div>
                            @endif
                                  </form>
                        </div>

                    </div>
                </div>
                <!-- End: life time stats -->
        </div>
    </div>

    <div class="modal fade in" id="quick-edit-model" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modal Title</h4>
                </div>
                <div class="modal-body"> Modal body goes here </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="button" class="btn green">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    </div>

    {{-- end of page content --}}
@endsection

@section('scripts')

@endsection
