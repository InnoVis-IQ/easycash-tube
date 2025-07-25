<?php

// For add'active' class for activated route nav-item
function active_class($path, $active = 'active') {
  return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

// For checking activated route
function is_active_route($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

// For add 'show' class for activated route collapse
function show_class($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

function actionButtons($data, $buttons = ['show', 'edit', 'destroy']){
    $route = explode(".",Route::currentRouteName());
    $actions = "";
    if(in_array('show', $buttons)){
        $actions .= "<a href='".route("$route[0].show",$data['id'])."' title='view' class='btn btn-warning mx-1' style='float:left'><i class='fa-regular fa-eye'></i></a>";
    }
    if(in_array('edit', $buttons)){
        $actions .= "<a href='".route("$route[0].edit",$data['id'])."' title='edit' class='btn btn-info mx-1' style='float:left'><i class='fa-regular fa-pen-to-square'></i></a>";
    }
    if(in_array('destroy', $buttons)){
      $actions .= "
          <form action='".route("$route[0].destroy",$data['id'])."' method='POST' style='float:left'>
              <input type='hidden' name='_method' value='DELETE'>
              <input type='hidden' name='_token' value='".csrf_token()."' />
              <button type='submit' class='btn btn-danger mx-1'><i class='fa-regular fa-trash-can'></i></button>
          </form>
      ";
    }
    return $actions;
}
