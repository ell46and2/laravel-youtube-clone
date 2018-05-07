@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Search for "{{ Request::get('q') }}"</div>

                <div class="card-body">
                    @if($channels->count())
                       <h4>Channels</h4>

                        <div class="card mb-2">
                            @foreach($channels as $channel)
                                <div class="media">
                                    <div class="media-left">
                                        <a href="/channel/{{ $channel->slug }}">
                                            <img src="{{ $channel->getImage() }}" alt="{{ $channel->name }} image" class="media-object">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <a href="/channel/{{ $channel->slug }}" class="media-heading">
                                            {{ $channel->name }}
                                        </a>
                                        {{ $channel->subscriptionCount() }} {{ str_plural('subscriber',$channel->subscriptionCount() ) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($videos->count())
                       @foreach($videos as $video)
                           <div class="card mb-2 card-body bg-light">
                               @include('video.partials._video_result', compact('video'))
                           </div>
                       @endforeach
                    @else
                        <p>No videos found.</p>
                   @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
