@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit video "{{ $video->title }}"</div>

                <div class="card-body">
                    <form action="/videos/{{ $video->uid }}" method="post">
                        
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="form-group">
                            <label for="name">Title</label>

                            <input
                                id="name"
                                type="text"
                                class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                name="title"
                                value="{{ old('title') ? old('title') : $video->title }}"
                                required
                                autofocus
                            >

                            @if ($errors->has('title'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Description</label>

                            <textarea
                                class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                name="description"
                                id="description"
                            >{{ old('description') ? old('description') : $video->description }}</textarea>

                            @if ($errors->has('description'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Visibility</label>

                           <select class="form-control" name="visibility" id="visibility">
                               @foreach(['public', 'unlisted', 'private'] as $visibility)
                                    <option value="{{ $visibility }}"{{ $video->visibility == $visibility ? ' selected="selected"' : '' }}>
                                        {{ ucfirst($visibility) }}
                                    </option>
                               @endforeach
                           </select>

                            @if ($errors->has('visibility'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('visibility') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="allow_notes"> 
                                <input type="checkbox" name="allow_votes" id="allow_votes"{{ $video->votesAllowed() ? ' checked="checked"' : '' }}> Allow votes
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="allow_comments"> 
                                <input type="checkbox" name="allow_comments" id="allow_comments"{{ $video->commentsAllowed() ? ' checked="checked"' : '' }}> Allow comments
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
