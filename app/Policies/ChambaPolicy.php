<?php

namespace App\Policies;

use App\Models\Chamba;
use App\Models\User;

class ChambaPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function update(User $user, Chamba $chamba)
    {
        return $user->isWorker();
    }
    public function destroy(User $user, Chamba $chamba)
    {
        return $user->isWorker();
    }
}
