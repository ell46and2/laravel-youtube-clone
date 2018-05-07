@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if($subscriptionVideos->count())
                        @foreach($subscriptionVideos as $video)
                            <div class="well">
                                @include('video.partials._video_result', compact('video'))
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
