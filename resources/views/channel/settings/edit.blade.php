@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Channel settings</div>

                <div class="card-body">
                    
                    <form action="{{ route('channel.update', $channel->slug) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }} 

                        <div class="form-group">
                            <label for="name">Name</label>

                            <input
                                id="name"
                                type="text"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                name="name"
                                value="{{ old('name') ? old('name') : $channel->name }}"
                                required
                                autofocus
                            >

                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="name">Unique URL</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="sizing-addon1">{{ config('app.url') }}/channel/</span>
                                </div>
                                
                                <input
                                    id="slug"
                                    type="text"
                                    class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                                    name="slug"
                                    value="{{ old('slug') ? old('slug') : $channel->slug }}"
                                    aria-describedby="sizing-addon1"
                                    required
                                    autofocus
                                >

                                 @if ($errors->has('slug'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div> 
                        </div>

                        <div class="form-group">
                            <label for="name">Description</label>

                            <textarea
                                class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                name="description"
                                id="description"
                            >{{ old('description') ? old('description') : $channel->description }}</textarea>

                            @if ($errors->has('description'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="image">Channel image</label>

                            <input class="form-control" type="file" name="image" id="image">
                        </div>

                        <button class="brn btn-primary" type="submit">Update</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
