@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                   <div class="media">
                       <div class="media-left">
                           <img src="{{ $channel->getImage() }}" alt="{{ $channel->name }} image" class="media-object">
                       </div>
                       <div class="media-body">
                           {{ $channel->name }}

                           <ul class="list-inline">
                               <li>
                                   <subscribe-button channel-slug="{{ $channel->slug }}"></subscribe-button>
                               </li>
                               <li>
                                   {{ $channel->totalVideoViews() }} video {{ str_plural('view', $channel->totalVideoViews()) }}
                               </li>
                           </ul>

                           @if($channel->description)
                               <hr>
                               <p>{{ $channel->description }}</p>
                           @endif
                       </div>
                   </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Videos</div>

                <div class="card-body">
                    @if($videos->count())
                        @foreach($videos as $video)
                            <div class="well">
                                @include('video.partials._video_result', compact('video'))
                            </div>
                        @endforeach

                        {{ $videos->links() }}
                    @else
                        <p>{{ $channel->name }} has no videos</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
