<?php
/**
 * DELETE cancellationPoliciesCancellationPolicyIdDelete
 * Summary: Delete cancellation policy
 * Notes: Deletes a single cancellation policy based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/cancellation_policies/{cancellationPolicyId}', function ($request, $response, $args) {
    $cancellationPolicy = Models\CancellationPolicy::find($request->getAttribute('cancellationPolicyId'));
    $result = array();
    try {
        if (!empty($cancellationPolicy)) {
            $cancellationPolicy->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Cancellation policy could not be deleted. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canDeleteCancellationPolicy'));
/**
 * GET cancellationPoliciesCancellationPolicyIdGet
 * Summary: Fetch cancellation policy
 * Notes: Returns a cancellation policy based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cancellation_policies/{cancellationPolicyId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    $cancellationPolicy = Models\CancellationPolicy::Filter($queryParams)->find($request->getAttribute('cancellationPolicyId'));
    if (!empty($cancellationPolicy)) {
        $result['data'] = $cancellationPolicy;
        return renderWithJson($result);
    } else {
        return renderWithJson($result, 'No record found', '', 1, 404);
    }
})->add(new ACL('canViewCancellationPolicy'));
/**
 * PUT cancellationPoliciesCancellationPolicyIdPut
 * Summary: Update cancellation policy by its id
 * Notes: Update cancellation policy by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/cancellation_policies/{cancellationPolicyId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $cancellationPolicy = Models\CancellationPolicy::find($request->getAttribute('cancellationPolicyId'));
    $cancellationPolicy->fill($args);
    $result = array();
    try {
        $validationErrorFields = $cancellationPolicy->validate($args);
        if (empty($validationErrorFields)) {
            $cancellationPolicy->save();
            $result = $cancellationPolicy->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Cancellation policy could not be updated. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Cancellation policy could not be updated. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canUpdateCancellationPolicy'));
/**
 * GET cancellationPoliciesGet
 * Summary: Fetch all cancellation policies
 * Notes: Returns all cancellation policies from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/cancellation_policies', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $cancellationPolicies = Models\CancellationPolicy::Filter($queryParams)->paginate()->toArray();
        $data = $cancellationPolicies['data'];
        unset($cancellationPolicies['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $cancellationPolicies
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = $e->getMessage(), $fields = '', $isError = 1, 422);
    }
});
/**
 * POST cancellationPoliciesPost
 * Summary: Creates a new cancellation policy
 * Notes: Creates a new cancellation policy
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/cancellation_policies', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $cancellationPolicy = new Models\CancellationPolicy($args);
    $result = array();
    try {
        $validationErrorFields = $cancellationPolicy->validate($args);
        if (empty($validationErrorFields)) {
            $cancellationPolicy->save();
            $result = $cancellationPolicy->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Cancellation policy could not be added. Please, try again.', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Cancellation policy could not be added. Please, try again.', '', 1, 422);
    }
})->add(new ACL('canCreateCancellationPolicy'));
