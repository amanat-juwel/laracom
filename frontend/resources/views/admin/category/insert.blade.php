<div class="modal fade" id="insert_category" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title">Insert Sub-Category</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form" action="{{url('/sub-category-insert')}}" id="insert_form" method="post">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="">Sub-Category Name</label>
                                            <input type="text" name="name" id="name" placeholder="Sub-Category Name" class="form-control" /> 
                                            @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name')}}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for=""> Description</label>
                                            <textarea rows="3" name="description" class="form-control" id="description" placeholder="Details about category"></textarea>
                                            @if($errors->has('description'))
                                            <span class="text-danger">{{ $errors->first('description')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success pull-left">Save</button>
                                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                                  
                                    </div>
                                    </form>
                            </div>
                        </div>
                    </div>