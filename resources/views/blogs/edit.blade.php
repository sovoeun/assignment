
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">Blogs New &nbsp; <a href="{{URL('blogs')}}">Go to list</a></div>

        <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/blogs/edit') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="description" class="col-md-4 control-label">Description</label>

                    <div class="col-md-6">
                        <textarea rows="4" cols="50" id="description" class="form-control" name="description">
                                {{$blog->description}}
                        </textarea>
                    	<input type="hidden" name="id" value="{{$blog->id}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="file" class="col-md-4 control-label">File</label>

                    <div class="col-md-6">
                        <input id="file" type="file" name="file">
                        <img src="{{$blog->file}}" width="200">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-file"></i> Save
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
