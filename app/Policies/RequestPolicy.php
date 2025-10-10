<?php

namespace App\Policies;

use App\Models\Request as RequestModel;
use App\Models\User;

class RequestPolicy
{
    /**
     * Determine whether the user can update the request.
     * Requester can update if still pending.
     */
    public function update(User $user, RequestModel $request)
    {
        return $user->id === $request->requester_id
            && $request->status === 'pending';
    }

    /**
     * Determine whether the user can cancel the request.
     * Requester can cancel if still pending.
     */
    public function cancel(User $user, RequestModel $request)
    {
        return $user->id === $request->requester_id
            && $request->status === 'pending';
    }

    /**
     * Determine whether the user can accept the request.
     * Any authenticated user (not requester) can accept
     * if still pending and no donor assigned.
     */
    public function accept(User $user, RequestModel $request)
    {
        return $user->id !== $request->requester_id
            && $request->donor_id === null
            && $request->status === 'pending';
    }

    /**
     * Determine whether the user can complete the request.
     * Requester can mark as completed if it’s accepted.
     */
    public function complete(User $user, RequestModel $request)
    {
        return $user->id === $request->requester_id
            && $request->status === 'approved';
    }
}
