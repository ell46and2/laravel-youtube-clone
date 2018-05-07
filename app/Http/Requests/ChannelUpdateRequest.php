<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChannelUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $channelId = auth()->user()->channel->first()->id;

        return [
            'name' => 'required|string|max:255|unique:channels,name,' . $channelId,
            'slug' => 'required|alpha_num|max:255|unique:channels,slug,' . $channelId,
            'description' => 'max:1000'
        ];
    }

    public function messages()
    {
        return [
            'slug.unique' => 'That unique URL has been taken.'
        ];
    }
}
