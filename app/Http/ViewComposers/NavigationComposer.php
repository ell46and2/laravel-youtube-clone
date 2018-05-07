<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
	public function compose(View $view)
	{
		if(!Auth::check()) {
			return;
		}

		// Share with the view the users channel
		// (change to channels and get all if a user can have multiple channels)
		$view->with('channel', Auth::user()->channel->first());
	}
}