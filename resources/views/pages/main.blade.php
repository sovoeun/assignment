@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Main Pages &nbsp;</div>

                <div class="panel-body">
                @if(Session::has('password_messages'))
                    <div class="alert alert-success">
                        {{ Session::pull('password_messages') }}
                    </div>
                @endif
                    @if(Entrust::hasRole('seller'))
                        @include('pages.seller')
                    @elseif(Entrust::hasRole('buyer'))
                        @include('pages.buyer')
                    @elseif(Entrust::hasRole('supplier'))
                        @include('pages.supplier')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
