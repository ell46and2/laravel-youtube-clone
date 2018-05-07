@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-faded">Videos</div>

                <div class="card-body">
                    @if($videos->count())
                        @foreach($videos as $video)
                            <div class="bg-light rounded">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <a href="">
                                                <img 
                                                    class="img-fluid"
                                                    src="{{ $video->getThumbnail() }}" 
                                                    alt="{{ $video->title }} thumbnail"
                                                >
                                            </a>
                                        </div>
                                        <div class="col-sm-9">
                                            <a href="/videos/{{ $video->uid }}">{{ $video->title }}</a>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <p class="text-muted">
                                                        @if(!$video->isProcessed())
                                                            Processing ({{ $video->processedPercentage() ? $video->processedPercentage() . '%' : 'Starting up' }})
                                                        @else
                                                            <span>{{ $video->created_at->toDateTimeString() }}</span>
                                                        @endif
                                                    </p>
                                                    
                                                    <form action="/videos/{{ $video->uid }}" method="post">
                                                        <a class="btn btn-sm btn-primary" href="{{ route('video.edit', $video) }}">Edit</a>
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                                    </form>
                                                    
                                                </div>
                                                <div class="col-sm-6">
                                                    <p>{{ ucfirst($video->visibility) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{ $videos->links() }}
                    @else
                        <p>You have no videos.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
