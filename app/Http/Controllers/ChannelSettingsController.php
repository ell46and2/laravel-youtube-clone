<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChannelUpdateRequest;
use App\Jobs\UploadImage;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelSettingsController extends Controller
{
    public function edit(Channel $channel)
    {
    	$this->authorize('edit', $channel);

    	return view('channel.settings.edit', compact('channel'));
    }

    public function update(ChannelUpdateRequest $request, Channel $channel)
    {
    	$this->authorize('update', $channel);

    	$channel->update(request()->only(['name', 'slug', 'description']));

    	if($request->file('image')) {
			// move to temp location and give the filename a unique id 
			$request->file('image')->move(storage_path() . '/uploads', $fileId = uniqid(true));
			
			// dispatch job 
			$this->dispatch(new UploadImage($channel, $fileId));
		}

    	// We can't just redirect back incase the slug has been changed
    	return redirect()->route('channel.edit', $channel->slug);
    }
}
