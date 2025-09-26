<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\DocumentKyc;
use App\Models\Agent;

class DocumentsVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isKYCRequired()) {
            return $next($request);
        }

        $userId = Auth::id();

        if ($this->isUserKYCVerified($userId)) {
            return $next($request);
        }

        return $this->handleKYCVerificationFailure($userId);
    }

    /**
     * The function checks if KYC (Know Your Customer) is required based on a configuration value.
     * 
     * @return a boolean value indicating whether KYC (Know Your Customer) is required.
     */
    private function isKYCRequired()
    {
        return teleman_config('kyc') === "YES";
    }

    /**
     * The function checks if a user is KYC verified based on their user ID, taking into account if
     * they are an admin, agent, or regular user.
     * 
     * @param userId The `userId` parameter represents the unique identifier of a user. It is used to
     * determine whether the user has completed the KYC (Know Your Customer) verification process.
     * 
     * @return a boolean value.
     */
    private function isUserKYCVerified($userId)
    {
        if (is_admin($userId)) {
            return true;
        }

        if (is_agent($userId)) {
            return $this->isAgentOwnerKYCVerified($userId);
        }

        return kyc_verified($userId);
    }

    /**
     * The function checks if the agent's owner has completed the KYC verification process.
     * 
     * @param agentId The agentId parameter is the ID of the agent for whom we want to check if the
     * owner's KYC (Know Your Customer) is verified.
     * 
     * @return the result of the function call `kyc_verified()`.
     */
    private function isAgentOwnerKYCVerified($agentId)
    {
        $ownerId = Agent::where('user_id', $agentId)->first()->assined_for_customer_id;
        return kyc_verified($ownerId);
    }

    /**
     * The function handles the failure of KYC verification by displaying a warning message and
     * redirecting the user to the appropriate page.
     * 
     * @param userId The userId parameter represents the unique identifier of the user for whom the KYC
     * verification is being handled.
     * 
     * @return a redirect to a specific route.
     */
    private function handleKYCVerificationFailure($userId)
    {
        if (is_agent($userId)) {
            smilify('warning', 'Account KYC is not verified. Please inform the owner.');
            return redirect()->route('dialer.index');
        }

        smilify('warning', 'You have to verify your KYC document to access this feature.');
        return redirect()->route('dashboard.kyc.index');
    }

    // ENDS
}
