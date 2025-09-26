<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncomingNumberChecker
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
        $routeName = $request->route()->getName();
        $userId = auth()->id();

        if ($this->isRouteAllowed($routeName, $userId, $request)) {
            return $next($request);
        }

        return abort(403);
    }

    /**
     * The function checks if a given route is allowed for a specific user based on their ID and
     * request.
     * 
     * @param routeName The name of the route that is being checked for permission.
     * @param userId The userId parameter represents the ID of the user for whom the route access is
     * being checked.
     * @param request The "request" parameter is an object that represents the HTTP request being made.
     * It typically contains information such as the request method (GET, POST, etc.), headers, query
     * parameters, and request body.
     * 
     * @return a boolean value. If the route name is either 'dialer.index' or 'dialerpad' and the user
     * is allowed access based on the user ID and request, then it returns true. Otherwise, it returns
     * false.
     */
    private function isRouteAllowed($routeName, $userId, $request)
    {
        if (in_array($routeName, ['dialer.index', 'dialerpad'])) {
            if ($this->isUserAllowed($userId, $request, $routeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The function checks if a user is allowed based on their role and the requested route or
     * department.
     * 
     * @param userId The user ID of the user making the request.
     * @param request The request parameter is an object that contains the request data. It likely
     * includes information such as the user's number, the department, and other relevant details.
     * @param routeName The route name is a string that represents the current route or endpoint being
     * accessed in the application. It is used to determine the logic for checking if the user is
     * allowed to access the route.
     * 
     * @return a boolean value. It returns true if the user is an admin or a customer, or if the user
     * is an agent and is in the specified department. Otherwise, it returns false.
     */
    /**
     * The function checks if a user is allowed based on their role and the requested route or
     * department.
     * 
     * @param userId The user ID of the user making the request.
     * @param request The request parameter is an object that contains the request data. It likely
     * includes information such as the user's number, the department, and other relevant details.
     * @param routeName The route name is a string that represents the current route or endpoint being
     * accessed in the application. It is used to determine the logic for checking if the user is
     * allowed to access the route.
     * 
     * @return a boolean value. It returns true if the user is an admin or a customer, or if the user
     * is an agent and is in the specified department. Otherwise, it returns false.
     */
    private function isUserAllowed($userId, $request, $routeName)
    {
        // Allow admin and customer users by default
        if (is_admin($userId) || is_customer($userId)) {
            return true;
        }

        // Additional checks for agents
        if (is_agent($userId)) {
            $departmentId = null;

            if ($routeName === 'dialerpad') {
                $departmentId = $this->getDepartmentIdFromProvider($userId, $request->my_number);
            } elseif ($routeName === 'dialer.index') {
                if (isset($request->department)) {
                    $departmentId = $request->department;
                } else {
                    return true;
                }
            }

            return $departmentId ? agent_is_in_department(get_agent_id($userId), $departmentId) : false;
        }

        return false;
    }



    /**
     * The function retrieves the department ID from a provider based on the user ID and a given
     * number.
     * 
     * @param userId The userId parameter is the unique identifier of the user. It is used to retrieve
     * the provider associated with the user.
     * @param myNumber The parameter `` is likely a unique identifier for a specific number or
     * account associated with the user. It is used as an input to the `getProvider()` function to
     * retrieve information about the provider associated with that number.
     * 
     * @return the department ID from the provider if it exists, otherwise it returns null.
     */
    private function getDepartmentIdFromProvider($userId, $myNumber)
    {
        $provider = getProvider($userId, $myNumber);

        return $provider ? find_department_number_is_available($provider->id)->id : null;
    }
}
