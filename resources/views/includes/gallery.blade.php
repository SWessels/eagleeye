<div class="modal fade" id="full" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Adding images to the gallery</h4>
            </div>
            <div class="modal-body">

                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li >
                            <a href="#tab_5_1" data-toggle="tab"> Upload Files </a>
                        </li>
                        <li class="active">
                            <a href="#tab_5_2" data-toggle="tab"> Library </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab_5_1">
                            {{--<form action="upload.php" class="dropzone dropzone-file-area" id="my-dropzone">--}}
                            <form action="#" id="fileupload" enctype="multipart/form-data" >
                                <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                <input type="hidden" name="media_type" id="media_type" value="" >
                                <input type="hidden" name="parent_id" value="" id="parent_id" >
                                <input type="hidden" name="image_type" value="" id="image_type" >
                                <input type="hidden" name="slider_type" value="" id="slider_type" >
                                <input type="hidden" name="slider_id" value="" id="slider_id" >
                                <div align="center" style="border: 1px solid beige ; height: 200px ; padding-top: 40px;" class="img_append">
                                <div class="row fileupload-buttonbar">
                                    <div class="col-lg-7">
                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                      {{--  <span class="btn green ">--}}
                                        <span class="btn green fileinput-button ">
                                            <i class="fa fa-plus"></i>
                                            <span> Add files... </span>
                                            <input class="up" type="file" name="files[]" id="files" multiple> </span>
                                        {{--<button type="submit" class="btn blue start upload_files" >
                                            <i class="fa fa-upload "></i>
                                            <span> Submit </span>
                                        </button>--}}

                                    </div>

                                </div>
                        </div>


                            </form>
                        </div>
                        <div class="tab-pane active" id="tab_5_2" ng-app="angularTable"  >

                            <div ng-controller="listdata as data"   class="row col-md-9 library-section">


                                <div class="row  pull-right col-md-6">
                                    <div class=" col-md-12">
                                         <input  id="searchtrigger" class="form-control "  type="text" ng-model="searchText" ng-change="change(text)" placeholder="filter" />
                                    </div>

                                </div>

                                <div class="clearfix"></div>
                                <div class="top20"></div>
                                <div id="sel"   class="tiles show_media_img">
                                <div class="col-md-12" style="text-align: right;">
                                    <dir-pagination-controls
                                            max-size="8"
                                            direction-links="true"
                                            boundary-links="true"
                                            on-page-change="data.getData(newPageNumber)" >
                                    </dir-pagination-controls></div>

                                    <label  dir-paginate="x in data.users|itemsPerPage:data.itemsPerPage|filter:keywords" total-items="data.total_count" for="[[x.id]]"   id="label[[x.id]]" class="label-img check_resolve">
                                        <input  type="checkbox" name="checkimg[]" data-path="[[x.db_path]]"   id="[[x.id]]" class="checkimg custom" value="[[x.id]]" />
                                    <?php $p = '[[x.path]]'; ?>
                                          <div class="tile image" style="width:120px !important; height:184px !important"  >
                                          <div class="tile-body" style="width:120px !important; height:184px !important" >
                                              {!! HTML::image($p,'[[x.alt_text]]', array('class' => 'img_centered')) !!}
                                          </div>
                                          </div></label>




                                   </div>
                                </div>



                            <div class="col-md-3 product-description library-section">
                                <div class="detail-section" >

                                    </div>
                                <div class="row col-md-12 col-sm-12 top15">

                                   {{-- {!! Form::open(array('method' => 'patch' ,'action' => array('ProductsController@create'))) !!}--}}
                                    <div class="form-body">


                                        <div class="form-group">
                                            <label>URL</label>
                                            <input name="checkimg[path]" id="path" readonly type="text" class="form-control input-medium" placeholder="">

                                            <div class="form-group">
                                                <label>Uploaded By</label>
                                                <input name="uploaded_by" id="uploaded_by" readonly type="text" class="form-control input-medium" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input name="title" id="title" type="text" class="form-control" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label>Alt Text</label>
                                                <input name="alt_text" id="alt_text" type="text" class="form-control" placeholder="">
                                            </div>
                                            <div  class="form-group">
                                            <button id="save_title" class="btn btn-sm blue-soft btn-outline save_title">Save Changes</button> <span style="color: green" id="changes_save"></span></div>

                                        </div>


                                    </div>

                                   {{-- {{ Form::close() }}--}}

                                </div>


                            </div>
                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline " data-dismiss="modal">Close</button>
                <button type="button"  data-dismiss="modal" class="btn green attach_image">Attach</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
