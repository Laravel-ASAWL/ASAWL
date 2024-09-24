<?php

namespace App\Policies;

use App\Models\User;

class PostPolicy
{
  // Protección de acceso mediante Policy de autorización
  public function update(User $user, Post $post): bool
  {
      return $user->id === $post->user_id;
  }
}
