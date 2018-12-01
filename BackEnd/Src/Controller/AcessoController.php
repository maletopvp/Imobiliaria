<?php

use Core\Controller;
use Validate\Acesso\Login as LoginValidate;

class AcessoController extends Controller
{
    private $controller = 'Acesso';

    public function __construct()
    {
        // Herdando Construct
        parent::__construct();
        $this->Acesso = parent::loadModel("Acesso");
    }

    public function index()
    {
        $acessos = $this->Acesso->list();
        $count = $this->Acesso->countItems();
        $bootstrapHelper = parent::loadHelper("Bootstrap");
        $styleHelper = parent::loadHelper("Style");
        $linkHelper = parent::loadHelper("Link");
        require_once parent::loadView($this->controller, $this->currentAction);
    }

    public function login()
    {
        $login = isset($_POST['login']) ? trim($_POST['login']) : null;
        $password = isset($_POST['password']) ? trim($_POST['password']) : null;

        $loginValidate = new LoginValidate();
        $loginValidate->validate();

        if (!$loginValidate->hasErrors()) {
            $this->Acesso->nm_login = $login;
            $this->Acesso->nm_password = $password;
            if ($this->Acesso->login()) {
                $_SESSION['login'] = $login;
                $this->redirectUrl();
            } else {
                echo 'Usuário ou senha inválidos.';
                require_once parent::loadView($this->controller, $this->currentAction);
            }
            exit;
        }
        if (empty($_SESSION)) {
            require_once parent::loadView($this->controller, $this->currentAction);
        } else {
            $this->redirectUrl(' ');
        }
        exit;
    }
    
    public function logout()
    {
        $this->Acesso->logout();
        $this->redirectUrl($this->controller . '/login');
    }

    public function add()
    {
    }

    public function edit(array $param)
    {
    }

    public function view(array $param)
    {
    }

    public function disable(array $param)
    {
        $cd_acesso = $param[0];
        if ($cd_acesso != "") {
            $this->Acesso->cd_acesso = $cd_acesso;
            $this->Acesso->disable();
            $this->redirectUrl();
            exit;
        } else {
            echo 'É necessário um código';
            $this->redirectUrl();
            exit;
        }
    }

    public function enable(array $param)
    {
        $cd_acesso = $param[0];
        if ($cd_acesso != "") {
            $this->Acesso->cd_acesso = $cd_acesso;
            $this->Acesso->enable();
            $this->redirectUrl();
            exit;
        } else {
            echo 'É necessário um código';
            $this->redirectUrl();
            exit;
        }
    }
}
