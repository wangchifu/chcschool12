<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Test;
use App\Models\UserGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the test.
     *
     * @param  \App\User  $user
     * @param  \App\Test  $test
     * @return mixed
     */
    public function view(User $user, Test $test)
    {
        //
    }

    /**
     * Determine whether the user can create tests.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->group_id === 1;
    }

    /**
     * Determine whether the user can update the test.
     *
     * @param  \App\User  $user
     * @param  \App\Test  $test
     * @return mixed
     */
    public function update(User $user, Test $test)
    {
        return $user->id === $test->user_id;
    }

    /**
     * Determine whether the user can delete the test.
     *
     * @param  \App\User  $user
     * @param  \App\Test  $test
     * @return mixed
     */
    public function delete(User $user, Test $test)
    {
        return $user->id === $test->user_id;
    }
}
