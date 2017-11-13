<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class GithubController extends Controller
{

    public function user(Request $request)
    {
        if(!env('GITHUB_ACCESS_TOKEN'))
            return [
                'error'=>'Please insert GITHUB_ACCESS_TOKEN in .env'
            ];
        $username = $request->input('username');
        try {
            $client = new Client();

            $userInfo = $client->request('GET', sprintf(
                    'https://api.github.com/users/%s?access_token=%s',
                    $username, 
                    env('GITHUB_ACCESS_TOKEN')
                    ));
            $body = \GuzzleHttp\json_decode($userInfo->getBody());
            if ($userInfo->getStatusCode() != 200)
                return [];

            $followers = $client->request('GET', sprintf(
                    'https://api.github.com/users/%s/followers?access_token=%s',
                    $username, env('GITHUB_ACCESS_TOKEN')
                    ));

            return [
                'user_info' => $body,
                'followers' => \GuzzleHttp\json_decode($followers->getBody())
            ];
        } catch (GuzzleException $e) {
            return [
                'error' => 'User not found.'
            ];
        }
    }

    public function followers(Request $request)
    {
        $page = $request->input('page');
        $username = $request->input('username');
        try {
            $client = new Client();

            return $followers = $client->request('GET', sprintf(
                    'https://api.github.com/users/%s/followers?page=%d&access_token=%s',
                    $username,
                    $page,
                    env('GITHUB_ACCESS_TOKEN')
            ));
        } catch (GuzzleException $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

}
