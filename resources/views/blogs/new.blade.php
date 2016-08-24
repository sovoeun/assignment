
<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">Blogs New &nbsp; <a href="{{URL('blogs')}}">Go to list</a></div>

        <div class="panel-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/blogs/create') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="description" class="col-md-4 control-label">Description</label>

                    <div class="col-md-6">
                        <textarea rows="4" cols="50" id="description" class="form-control" name="description">
                                {{ old('description') }}
                        </textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="file" class="col-md-4 control-label">File</label>

                    <div class="col-md-6">
                        <input id="file" type="file" name="file">
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
