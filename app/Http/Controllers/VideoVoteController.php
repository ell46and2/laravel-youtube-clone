<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVoteRequest;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoVoteController extends Controller
{
    public function show(Request $request, Video $video)
    {
    	// set defaults
    	$response = [
    		'up' => null,
    		'down' => null,
    		'can_vote' => $video->votesAllowed(),
    		'user_vote' => null,
    	];
    	
    	// check if votes are allowed
    	if($video->votesAllowed()) {
    		$response['up'] = $video->upVotes()->count();
    		$response['down'] = $video->downVotes()->count();
    	}
    	
    	// check user vote 
    	if($request->user()) {
    		$voteFromUser = $video->voteFromUser($request->user())->first();
    		$response['user_vote'] = $voteFromUser ? $voteFromUser->type : null;
    	}

    	return response()->json([
    		'data' => $response
    	], 200);
    }

    public function create(CreateVoteRequest $request, Video $video)
    {
    	$this->authorize('vote', $video);

    	$currentVote = $video->voteFromUser($request->user())->first();
    	if($currentVote) {
    		$currentVote->delete();
    	}

    	$video->votes()->create([
    		'type' => $request->type,
    		'user_id' => $request->user()->id,
    	]);

    	return response()->json(null, 200);
    }

    public function remove(Request $request, Video $video)
    {
    	$this->authorize('vote', $video);

    	$video->voteFromUser($request->user())->first()->delete();

    	return response()->json(null, 200);
    }
}
