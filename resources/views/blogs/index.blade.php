@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Blogs Lists &nbsp; <a href="javascript:;" id="btn_create">Create New</a></div>

                <div class="panel-body">
                    
                    <table class="table table-bordered">
                    	<thead>
                            <tr>
                                <td colspan="4">
                                    <form method="GET" role="form">
                                        <div class="col-sm-8">
                                            <input type="text" name="q" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="submit" value="Search" class="btn btn-defualt">
                                        </div>
                                    </form>
                                </td>
                            </tr>
                    		<tr>
                    			<th>ID</th>
                    			<th>DECRIPTION</th>
                    			<th>FILE NAME</th>
                    			<th>ACTION</th>
                    		</tr>
                    	</thead>
                    	<tbody>
                    		@if(count($blogs) > 0)
                    			@foreach($blogs as $val)
                    				<tr>
	                    				<td>{{ $val->id }}</td>
                                        <td><img src="{{ $val->file }}" width="80" class="img-thumbnail"/></td>
	                    				<td>{{ $val->description }}</td>
	                    				
	                    				<td>
	                    					<a href="javascript:;" class="btn_edit" data-id="{{$val->id}}">Edit</a>
	                    					<a href="{{URL('blogs/delete?id=').$val->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
	                    				</td>
	                    			</tr>
                    			@endforeach
                    		@endif
                    	</tbody>
                    </table>
                    {{ $blogs->appends(['sort' => 'votes'])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Large modal -->
<button type="button" class="btn btn-primary hidden btn_popup" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#btn_create').on('click', function(){
            $.ajax({
                url: "{{URL('blogs/ajax')}}",
                data: {action:'get_create'},
                type: "GET",
                success: function(data){
                    $('.modal-content').html(data);
                    $('.btn_popup').trigger('click');
                },
                error: function(){
                    alert('errors');
                }
            });
        });

        $('.btn_edit').on('click', function(){
            id = $(this).data('id');
            $.ajax({
                url: "{{URL('blogs/ajax')}}",
                data: {action:'get_edit', id:id},
                type: "GET",
                success: function(data){
                    $('.modal-content').html(data);
                    $('.btn_popup').trigger('click');
                },
                error: function(){
                    alert('errors');
                }
            });
        });
    });
</script>
@endsection
