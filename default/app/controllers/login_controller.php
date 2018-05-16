<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
Load::model('users');

class LoginController extends AppController {

    public function index() {
        if (Input::hasPost("email", "pass")) {
            $pwd = Input::post("pass");
            $usuario = Input::post("email");
            $auth = new Auth("model", "class: users", "email: $usuario", "pass: $pwd");
            if ($auth->authenticate()) {               
                Redirect::to("/");                
            } else {
                Flash::error("Usuario o clave incorrecta.");
            }
        }
        View::select('index', 'login-mt');
    }

    public function logout() {
        Auth::destroy_identity();
        Router::redirect("/");
    }
}
