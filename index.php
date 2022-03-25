<?php
/****************************** 
API REST - CRUD - TESTE TÉCNICO
Desenvolvido por Dyego Pimentel
*******************************/ 

# Importa crud_class
require_once "crud_class.php";

header("Content-Type: application/json");
$data = null;

# Variáveis com informações padrões para utilização da API
$fn = $_REQUEST["fn"] ?? null;
$id = $_REQUEST["id"] ?? 0;
$name = $_REQUEST["name"] ?? null;
$contact = $_REQUEST["contact"] ?? null;
$exam = $_REQUEST["exam"] ?? null;

$patient = new Patient;
$patient->setId($id);



# CREATE - Verifica se todos os dados foram informados e cria a instancia no banco de dados
if ($fn === "create" && $name !== null && $contact !== null && $exam !== null ){
    # Recebe as variáveis do paciente
    $patient->setName(urldecode($name));
    $patient->setContact(urldecode($contact));
    $patient->setExam(urldecode($exam));

    # Cria o paciente no banco de dados
    $data["patient"] = $patient->create();
    # Informa o resultado da ação
    $response = 200;
} 

# READ - Caso o id seja informado busca o paciente, caso contrário busca todos os pacientes do banco de dados
if ($fn === "read"){
    # Busca o paciente no banco de dados
    $data["patient"] = $patient->read();
}

# UPDATE - Busca paciente no banco de dados e atualiza os dados.
if ($fn === "update" && $id > 0 && $name !== null && $contact !== null && $exam !== null){
    # Recebe as variáveis do paciente
    $patient->setId(urldecode($id));
    $patient->setName(urldecode($name));
    $patient->setContact(urldecode($contact));
    $patient->setExam(urldecode($exam));

    # Cria o paciente no banco de dados
    $data["patient"] = $patient->update();
}

# DELETE - Busca paciente no banco de dados e deleta.
if ($fn === "delete" && $id > 0){
    # Cria o paciente no banco de dados
    $data["patient"] = $patient->delete();
    
}


# Retorna o resultado 
if ($data !== null) {
    if ($response === 200) {
        echo "Response 200: Ok \n";
        die(json_encode($data));
    } else if ($response === 404) {
        echo "Response 404: Not Found \n";
        die();
    } else {
        die(json_encode($data));
    }
    
} else if ($data === null) {
        
    if ($response === 404) {
        echo "Response 404: Not Found \n";
        echo "Insira os parâmetros para utilizar a API, caso tenha dúvidas consulte a documentação.";    
        die();
    } else {
        echo "Insira os parâmetros para utilizar a API, caso tenha dúvidas consulte a documentação.";
    }

}
    
        
