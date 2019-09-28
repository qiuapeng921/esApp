@extends('layouts.main')
@section('content')
    <div class="col-xs-12 col-sm-9">
        <div class="jumbotron" style="height: 500px;overflow: auto">
            <p>This is an example to show the potential of an offcanvas layout pattern in Bootstrap. Try some
                responsive-range viewport sizes to see it in action.</p>
            <p>This is an example to show the potential of an offcanvas layout pattern in Bootstrap. Try some
                responsive-range viewport sizes to see it in action.</p>
            <p>This is an example to show the potential of an offcanvas layout pattern in Bootstrap. Try some
                responsive-range viewport sizes to see it in action.</p>
        </div>
        <div class="row" style="width: 75%">
            <div class="col-xs-6 col-lg-4">
                <label>
                    <textarea class="form-control input-lg" style="width: 500px;" rows="3" id="content"></textarea>
                </label>
                <button type="button" class="btn btn-success media-right" onclick="say('send')">发送</button>
            </div>
        </div>
    </div>
@endsection