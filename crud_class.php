<?php
 /* Esta class é responsável por configurar as variáveis necessárias para o 
 * funcionamento da API, acessar o banco de dados e realizar as ações de CRUD.
 */

class Patient{

    # Variaveis 
    private $id = 0;
    private $name = null;
    private $contact = null;
    private $exam = null;

    #
    public function setId(int $id) :void
    {
        $this->id = $id;
    }

    public function getId() :int 
    {
        return $this->id;
    }


    #
    public function setName(string $name) :void
    {
        $this->name = $name;
    }

    public function getName() :string 
    {
        return $this->name;
    }

    #
    public function setContact(string $contact) :void
    {
        $this->contact = $contact;
    }

    public function getContact() :string 
    {
        return $this->contact;
    }

    #
    public function setExam(string $exam) :void
    {
        $this->exam = $exam;
    }

    public function getExam() :string 
    {
        return $this->exam;
    }

    # Função de Conexão ao banco de dados
    private function connection() :\PDO
    {
        return new \PDO("mysql:host=localhost;dbname=NOME_DO_BANCO","USUÁRIO","SENHA");
    }


    # Função responsável por entrar e inserir os dados no banco
    public function create() :array
    {
        $conn = $this->connection();
        $stmt = $conn->prepare("INSERT INTO patient VALUES (NULL,:_name,:_contact,:_exam)");
        $stmt->bindValue(":_name",$this->getName(),\PDO::PARAM_STR);
        $stmt->bindValue(":_contact",$this->getContact(),\PDO::PARAM_STR);
        $stmt->bindValue(":_exam",$this->getExam(),\PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $this->setId($conn->lastInsertId());
            return $this->read();
        }
        return [];

    }

    # Função responsável por entrar e ler os dados no banco
    public function read() :array
    {   # Conecta no banco de dados
        $conn = $this->connection();
        # Se o id não for informado, seleciona todos
        if ($this->getId() === 0){
            $stmt = $conn->prepare("SELECT * FROM patient ORDER BY id DESC");
                
            if ($stmt->execute()) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        # Se o id for informado, retorna apenas o paciente desejado
        } else if ($this->getId() > 0){
            $stmt = $conn->prepare("SELECT * FROM patient WHERE id = :_id");
            $stmt->bindValue(":_id",$this->getId(),\PDO::PARAM_INT);
                
            if ($stmt->execute()) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        return [];

    }

    # Função responsável por entrar e atualizar os dados no banco
    public function update() :array
    {
        $conn = $this->connection();
        $stmt = $conn->prepare("UPDATE patient SET name = :_name, phone = :_contact, exam = :_exam WHERE id = :_id");
        $stmt->bindValue(":_id",$this->getId(),\PDO::PARAM_STR);
        $stmt->bindValue(":_name",$this->getName(),\PDO::PARAM_STR);
        $stmt->bindValue(":_contact",$this->getContact(),\PDO::PARAM_STR);
        $stmt->bindValue(":_exam",$this->getExam(),\PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $this->read();
        }
        return [];

    }

    # Função responsável por entrar e deletar os dados no banco
    public function delete() :array
    {
        $patient_del = $this->read();
        $conn = $this->connection();
        $stmt = $conn->prepare("DELETE FROM patient WHERE id = :_id");
        $stmt->bindValue(":_id",$this->getId(),\PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $patient_del;
        }
        return [];
    }
}