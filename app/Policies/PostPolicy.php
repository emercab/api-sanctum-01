<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Response;

class PostPolicy{
  public function modify(User $user, Post $post): Response
  {
    return $user->id === $post->user_id
      ? Response::allow()
      : Response::deny('You are not authorized to modify this post');
  }


  public function delete(User $user, Post $post): Response
  {
    return $user->id === $post->user_id
      ? Response::allow()
      : Response::deny('You are not authorized to delete this post');
  }
}
