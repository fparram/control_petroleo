<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
Load::model('users');

class LoginController extends AppController {

    public function index() {
        if (Input::hasPost("email", "password")) {
            $pwd = Input::post("password");
            $usuario = Input::post("email");
            $auth = new Auth("model", "class: users", "email: $usuario", "password: ".  Util::encriptarClave($pwd));
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
