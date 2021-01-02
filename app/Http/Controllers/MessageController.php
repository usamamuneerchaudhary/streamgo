<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $authors = User::all();

        return view('message.create', compact('authors'));
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'body' => 'required|min:3|max:255',
                'author_id' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => $e
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $message = new Message();
            $message->body = $request->body;
            $message->author_id = $request->author_id;
            $message->save();
        } catch (QueryException $exception) {
            return response()->json([
                'error' => true,
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'error' => false,
            'message' => 'Message Created successfully'
        ], Response::HTTP_OK);
    }
}
