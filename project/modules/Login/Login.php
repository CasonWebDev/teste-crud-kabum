<?php
class Login
{
    private $db;
    private $helper;

    private function __construct()
    {
        $core = Core::getInstance();
        $this->db = $core->loadModule('database');
        $this->helper = $core->loadModule('helper');
    }

    public static function getInstance() {
        static $inst = null;
        if($inst === null) {
            $inst = new Login();
        }
        return $inst;
    }

    public function criarUsuario()
    {
        $campos = $_POST;

        $this->validaNome($campos['nome']);
        $this->validaSenha($campos['senha'], $campos['resenha']);
        $this->validaEmail($campos['email']);

        $sql = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        $sql->bindValue(":nome", htmlspecialchars($campos['nome']));
        $sql->bindValue(":email", htmlspecialchars($campos['email']));
        $sql->bindValue(":senha", $this->helper->hash($campos['senha']));
        $sql->execute();

        $_SESSION['login'] = $campos['email'];

        echo json_encode(['sucesso' => true]);
    }

    private function validaSenha($senha, $confirmacao)
    {
        if($senha !== $confirmacao){
            echo json_encode(['erro' => 'Senha e confirmação devem ser iguais.']);
            die();
        }
    }

    private function validaNome($nome)
    {
        if(empty($nome)){
            echo json_encode(['erro' => 'Campo nome é obrigatório.']);
            die();
        }
    }

    private function validaEmail($email)
    {
        $sql = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $sql->bindValue(":email", htmlspecialchars($email));
        $sql->execute();

        if($sql->rowCount() > 0) {
            echo json_encode(['erro' => 'E-mail já cadastrado no sistema.']);
            die();
        }
    }
}
