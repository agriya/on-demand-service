<?php
//Category
$app->GET('/api/v1/categories', function ($request, $response, $args) {
    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        $categories = Models\Category::Filter($queryParams)->paginate()->toArray();
        $data = $categories['data'];
        unset($categories['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $categories
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
$app->GET('/api/v1/categories/{categoryId}', function ($request, $response, $args) {
    $result = array();
    $queryParams = $request->getQueryParams();
    try {
        $categories = Models\Category::Filter($queryParams)->find($request->getAttribute('categoryId'));
        if (!empty($categories)) {
            $result['data'] = $categories;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Sorry! No record found', $e->getMessage(), '', 1, 422);
    }
});
$app->POST('/api/v1/categories', function ($request, $response, $args) {
    global $authUser;
    $result = array();
    $args = $request->getParsedBody();
    try {
        $categories = new Models\Category($args);
        $validationErrorFields = $categories->validate($args);
        if (empty($validationErrorFields)) {
            if ($categories->save()) {
                if (!empty($args['form_field_groups'])) {
                    foreach ($args['form_field_groups'] as $formFieldGroups) {
                        $formFieldGroup = new Models\FormFieldGroup;
                        $formFieldGroup->name = $formFieldGroups['name'];
                        $formFieldGroup->slug = Inflector::slug(strtolower($formFieldGroups['name']), '-');
                        $formFieldGroup->class = 'Category';
                        $formFieldGroup->foreign_id = $categories->id;
                        $formFieldGroup->save();
                        if (!empty($formFieldGroups['form_fields'])) {
                            foreach ($formFieldGroups['form_fields'] as $formFields) {
                                $formField = new Models\FormField($formFields);
                                $formField->class = 'Category';
                                $formField->form_field_group_id = $formFieldGroup->id;
                                $formField->foreign_id = $categories->id;
                                $formField->save();
                            }
                        }
                    }
                }
                if (!empty($args['image'])) {
                    saveImage('Category', $args['image'], $categories->id);
                }
                $result['data'] = $categories->toArray();
                return renderWithJson($result);
            }
        } else {
            return renderWithJson($result, 'Category could not be added. Please, try again.', '', $validationErrorFields, 1, 422);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Category could not be added. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canPostCategory'));
$app->PUT('/api/v1/categories/{categoryId}', function ($request, $response, $args) {
    $args = $request->getParsedBody();
    $categories = Models\Category::find($request->getAttribute('categoryId'));
    $result = array();
    try {
        if (!empty($categories)) {
            $categories->fill($args);
            $validationErrorFields = $categories->validate($args);
            if (empty($validationErrorFields)) {
                if ($categories->save()) {
                    if (!empty($args['form_field_groups'])) {
                        foreach ($args['form_field_groups'] as $formFieldGroups) {
                            $formFieldGroup = new Models\FormFieldGroup;
                            if (!empty($formFieldGroups['id'])) {
                                $formFieldGroup = Models\FormFieldGroup::where('id', $formFieldGroups['id'])->first();
                            }                            
                            $formFieldGroup->name = $formFieldGroups['name'];
                            $formFieldGroup->slug = Inflector::slug(strtolower($formFieldGroups['name']), '-');
                            $formFieldGroup->class = 'Category';
                            $formFieldGroup->foreign_id = $categories->id;
                            $formFieldGroup->save();
                            if (!empty($formFieldGroups['form_fields'])) {
                                foreach ($formFieldGroups['form_fields'] as $formFields) {
                                    $formField = new Models\FormField($formFields);
                                    if (!empty($formFields['id'])) {
                                        $formField = Models\FormField::where('id', $formFields['id'])->first();
                                        $formField->fill($formFields);
                                    }
                                    $formField->class = 'Category';
                                    $formField->form_field_group_id = $formFieldGroup->id;
                                    $formField->foreign_id = $categories->id;
                                    $formField->save();
                                }
                            }
                        }
                    }
                    if (!empty($args['image'])) {
                        saveImage('Category', $args['image'], $categories->id);
                    }
                    $result['data'] = $categories->toArray();
                    return renderWithJson($result);
                }                
            } else {
                return renderWithJson($result, 'Category could not be updated. Please, try again.', '', $validationErrorFields, 1, 422);
            }
        } else {
            return renderWithJson($result, 'Category not found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Category could not be updated. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canUpdateCategory'));
$app->DELETE('/api/v1/categories/{categoryId}', function ($request, $response, $args) {
    $categories = Models\Category::find($request->getAttribute('categoryId'));
    $result = array();
    try {
        if (!empty($categories)) {
            $categories->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', '', 1, 404);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Category could not be deleted. Please, try again.', $e->getMessage(), '', 1, 422);
    }
})->add(new ACL('canDeleteCategory'));
