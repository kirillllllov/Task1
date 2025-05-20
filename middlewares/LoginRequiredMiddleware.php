<?php
class LoginRequiredMiddleware extends BaseMiddleware
{
  public function apply(BaseController $controller, array $context)
  {
    if (session_status() != PHP_SESSION_ACTIVE) {
      session_set_cookie_params(60 * 60 * 10);
      session_start();
      array_push($_SESSION['messages'], $_SERVER['REQUEST_URI']);
    }

    $is_logged = isset($_SESSION['is_logged']) ? $_SESSION['is_logged'] : false;
    if (!$is_logged) {
      error_log("NOT LOGGED");
      error_log("is_logged = " . $is_logged);
      header("Location: /login");
      exit;
    } else {
      error_log("IS LOGGED");
    }
  }
}
