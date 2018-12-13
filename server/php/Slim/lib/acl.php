<?php
class ACL
{
    //To set the user scope
    public function __construct($scope)
    {
        $this->scope = $scope;
    }
    //To check user has correct auth access
    public function __invoke($request, $response, $next)
    {
        global $authUser;
        $token = "";
        if (isset($request->getHeaders() ['HTTP_AUTHORIZATION']) && !empty($request->getHeaders() ['HTTP_AUTHORIZATION'])) {
            $token = $request->getHeaders() ['HTTP_AUTHORIZATION'][0];
            if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
                $token = $matches[1];
            }
        }
        if (!empty($token)) {
            if (((empty($authUser) || (!empty($authUser['role_id']) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin)) && !in_array($this->scope, $authUser['scope']))) {
                $result = array(
                    'error' => true,
                    'message' => 'Authorization Failed'
                );
                return $response->withJson($result, 401);
            } else {
                $response = $next($request, $response);
            }
        } else {
            $result = array(
                'error' => true,
                'message' => 'Authorization Failed'
            );
            return $response->withJson($result, 401);
        }
        return $response;
    }
}
