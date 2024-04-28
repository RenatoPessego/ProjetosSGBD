<?php
require_once("custom/php/common.php");
//verifica se tem login feito e se tem permissoes para gerir os dados
if (is_user_logged_in() && current_user_can('manage_records'))
{
    
    $host='http://' . $_SERVER['SERVER_NAME'].'/sgbd';
    echo "<link rel='stylesheet' type='text/css' href=".$host."/custom/php/estilos.css>";
    if (!isset($_REQUEST['estado']) )
    {
    //efetua query à base de dados
    $query = "select * from child order by name";
    $result = mysqli_query($link,$query);
    $error = mysqli_error($link);
       if($error != "")
    {
            echo "<p class='error-message'>
                Ocorreu um erro ao efetuar a query: ".$error . "</p>
                ";
    }
    else
    {
    //se existirem dados devolvidos
    if (mysqli_num_rows($result) > 0) 
    {
        // percore os valores devolvidos e cria uma linha da tabela para cada registo
        echo "
        <div id='divTabela'><table class='table'><tr><th>Ação</th><th>Nome</th><th>Data de Nascimento</th><th>Enc. de educação</th><th>Telefone do enc.</th><th>E-mail</th><th>Registos</th></tr>
        ";
        while($row = $result->fetch_assoc()) 
        {
        //query para obter os valores do item e subitems
        $queryRegisto = "select item.name as item,value.date as data, value.producer as utilizador,subitem.name as subitem, value.value as valor 
        from value, subitem,item 
        where value.subitem_id = subitem.id and subitem.item_id = item.id and item.state= 'active' and subitem.state='active'  and value.child_id=".$row["id"]." 
        order by item.name, value.date";
        $resultRegisto =  mysqli_query($link,$queryRegisto);
        //percore o resultado e constroe a string com dados
        $registo ="";
        $item="";
        $subitem="";
        $data ="";
        $valor ="";
        if (mysqli_num_rows($resultRegisto) > 0)
        {
        while($rowRegisto = $resultRegisto->fetch_assoc())
        {
            if($item!=$rowRegisto["item"])
            {
                //verifica se é primeiro item, se nao for coloca um </br>
                if($item !="")
                {
                    $registo = $registo."</br>";
                }
                $item = $rowRegisto["item"];
                $subitem = $rowRegisto["subitem"];
                $data = $rowRegisto["data"];
                $valor = $rowRegisto["valor"];
                $registo = $registo .strtoupper($item).":</br>[editar][apagar] - <strong>".$data."</strong> (".$rowRegisto["utilizador"].") - <strong>".$subitem."</strong> (".$valor.")";
                
            }
            if($item == $rowRegisto["item"] && $data!=$rowRegisto["data"] || $valor != $rowRegisto["valor"])
            {
                $data = $rowRegisto["data"];
                $subitem = $rowRegisto["subitem"];
                $valor = $rowRegisto["valor"];
                $registo = $registo ."</br>[editar][apagar] - <strong>".$data."</strong> (".$rowRegisto["utilizador"].") - <strong>".$subitem."</strong> (".$valor.")";
            }
        }
    }
    else
    {
        $registo = "Sem registos";
        }

        echo "<tr><td><a href=" . $host . "/gestao-de-registos?estado=editar&id=".$row['id'] . ">[editar]</a><a href=" . $host . "/gestao-de-registos?estado=apagar&id=".$row['id'] . ">[apagar]</td><td>".$row["name"]."</td><td>".$row["birth_date"]."</td><td>".$row["tutor_name"]."</td><td>".$row["tutor_phone"]."</td><td>".$row["tutor_email"]."</td><td>$registo</td></tr>";
    }
        echo "
        </table></div>
        ";
        
    } 
    else 
    {
        echo "
        Não há crianças
        ";
    }
    }
    // introduzir dados básicos de crianças
    echo "</br>";
        
    echo "
    <form id='formDados' method='post' action=''>
    ";
    echo "
        <h3>Dados de registo - introdução</h3>
    ";
    echo "
    <label for='NomeC'>Nome Completo:</label></br><input type='text' id='NomeC' name='NomeC' required></br>
    ";
    echo "
    <label for='DataN'>Data de Nascimento (AAAA-MM-DD):</label></br><input type='text' id='DataN' name='DataN' required></br>
    ";
    echo "
    <label for='NomeCompE'>Nome Completo do Encarregado de Educação:</label></br><input type='text' id='NomeCompE' name='NomeCompE' required></br>
    ";
    echo "
    <label for='Telefone'>Telefone do Encarregado de Educação:</label></br><input type='text' id='Telefone' name='Telefone' required></br>
    ";
    echo "
    <label for='email'>Endereço de email do tutor (opcional):</label></br><input type='text' id='email' name='email'></br>
    ";
    echo "
    <input type='hidden' name='estado' id='estado' value='validar'></br><input type='submit' value='Submeter'></form>";
    }
    // Verifica se todas as variáveis associadas aos campos obrigatórios do formulário foram recebidas 
    else if ($_REQUEST['estado'] == 'validar' || $_REQUEST['estado'] == 'validarEdicao')
    {
        $Id ="";
        // Armazenar os dados do formulário
        if ($_REQUEST['estado'] == 'validarEdicao')
            $Id = $_POST['Id'];
        $NomeC = $_POST['NomeC'];
        $DataN = $_POST['DataN'];
        $NomeCompE = $_POST['NomeCompE'];
        $Telefone = $_POST['Telefone'];
        $email = $_POST['email']; 
        if (!isset($email))
            $email="";
            
        $continuar = true;
        //formulario de validação de dados
        echo "<h3> Dados de registo - validação</h3></br>";
        // Verificar se todos os campos obrigatórios foram preenchidos
        if(empty($NomeC) || empty($DataN) || empty($NomeCompE) || empty($Telefone))
        {
            echo "
            <p>Preencha todos os campos obrigatórios</p>
            ";
           $continuar = false;
           echo "
           <button onclick='history.go(-1)'>Voltar</button>
           ";
        }
        else
        {
            // Validar o formato do telefone e da data de nascimento
            if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $DataN))
            {
                echo "
                <p>Por favor, insira a data de nascimento no formato correto (AAAA-MM-DD)</p>
                ";
                $continuar = false;
                echo "
                <button onclick='history.go(-1)'>Voltar</button>
                ";
            }
            else if(!preg_match("/^[0-9]{9}$/", $Telefone))
            {
                echo "
                <p>Por favor, insira um número de telefone válido</p>
                ";
                $continuar = false;
                echo "
                <button onclick='history.go(-1)'>Voltar</button>
                ";
            }
            //falta validação email
            else if ($email!="")
            {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    echo "
                    <p class='error-message'>Por favor, insira um endereço de e-mail válido</p>
                    ";
                    $continuar = false;
                    echo "
                    <button onclick='history.go(-1)'>Voltar</button>
                    ";
                }
            }
        }
        if ($continuar)
        {
        
        echo "
        <form id='formInserir' method='post' action=''>
        ";
        echo "
        Estamos prestes a inserir os dados abaixo na base de dados. Confirma que os dados estão correctos e pretende submeter os mesmos? </br>
        ";         
        echo " 
            </br>
            <label for='NomeC'>Nome Completo:</label></br><input type='text' id='NomeC' name='NomeC' value = '$NomeC' readonly></br>
            ";
        echo "
            <label for='DataN'>Data de Nascimento (AAAA-MM-DD):</label></br><input type='text' id='DataN' name='DataN' value='$DataN' readonly></br>
            ";
        echo "
            <label for='NomeCompE'>Nome Completo do Encarregado de Educação:</label></br><input type='text' id='NomeCompE' name='NomeCompE' value= '$NomeCompE' readonly></br>
            ";
        echo "
            <label for='Telefone'>Telefone do Encarregado de Educação:</label></br><input type='text' id='Telefone' name='Telefone' value='$Telefone' readonly></br>
            ";
        echo "
            <label for='email'>Endereço de email do tutor (opcional):</label></br><input type='text' id='email' name='email' readonly value='$email'></br>
            ";
        $valor ="";
        if ($_REQUEST['estado'] == 'validar')
            $valor = "inserir";
        else if ( $_REQUEST['estado'] == 'validarEdicao')
            $valor = "update";
        
        echo "
        <input type='hidden' name='Id' id='Id' value=$Id></br>
        <input type='hidden' name='estado' id='estado' value=".$valor."></br>
        <input type='button' name='voltar' value='Voltar' onclick='history.go(-1)'>
        <input type='submit' value='Submeter'></form> 
        ";
        
        }
    }
    else if ($_POST )
    {
        if ($_POST['estado']=='inserir' || $_POST['estado']=='update' )
        {
            $Id =  $_POST['Id'];
            $tipo  = $_POST['estado'];
            // Armazenar os dados do formulário
            $NomeC = $_POST['NomeC'];
            $DataN = $_POST['DataN'];
            $NomeCompE = $_POST['NomeCompE'];
            $Telefone = $_POST['Telefone'];
            $email = $_POST['email'];
            $sql ="";
            echo "
            <h3>Dados de registo - inserção</h3>
            ";
            // Se a validação for bem sucedida, inserir os dados na base de dados
            //verifica se é edicao ou se é inserção de um novo
            if ($_POST['estado']=='inserir')
                $sql = "INSERT INTO child (name, birth_date, tutor_name, tutor_phone, tutor_email) VALUES ('$NomeC', '$DataN', '$NomeCompE', '$Telefone', '$email')";
            else if ($_POST['estado']=='update')
                $sql = "UPDATE child SET child.name = '$NomeC' ,birth_date='$DataN' ,tutor_name='$NomeCompE', tutor_phone='$Telefone' ,tutor_email= '$email' WHERE id = '$Id';";
            $sql_result = mysqli_query($link,$sql);
            if(!$sql_result)
            {
                echo "Erro: ".mysqli_error($link);
            }
            else
            {
                echo "
                <p class='success-message'>Inseriu os dados de registo com sucesso.</p>
                ";
                echo 
                    "<p>Clique em Continuar para avançar.</p>
                    ";
                echo "<a href='gestao-de-registos'>
                    <button>Continuar</button>
                </a>";
            }
        }
        else if ($_POST['estado']=='apagarRegisto')
        {
            $Id =  $_POST['Id'];
            $sql ="";
            echo "
            <h3>Dados de registo - apagar</h3>
            ";
            //inicia a transacao
            mysqli_begin_transaction($link);
            try
            {
                $sqlValue = "DELETE FROM value WHERE child_id =$Id;";
                $sqlChild = "DELETE FROM child WHERE id =$Id;";
                //corre as query
                $sql_resultValue = mysqli_query($link,$sqlValue);
                $sql_resultChild = mysqli_query($link,$sqlChild);
                // Se correr tudo bem faz commit da transação
                mysqli_commit($link);
                //verifica se ocorreu algum erro
                if(!$sql_resultValue || !$sqlChild)
                {
                    echo "Erro: ".mysqli_error($link);
                }
                else
                {
                    echo "
                    <p class='success-message'>Apagou os dados de registo com sucesso.</p>
                    ";
                    echo 
                        "<p>Clique em Continuar para avançar.</p>
                        ";
                    echo "<a href='gestao-de-registos'>
                        <button>Continuar</button>
                    </a>";
                }
            }
            catch (Exception $e) {
                // Se ocorrer um erro, volta a tras (rollback)
                mysqli_rollback($link);
                echo "Erro na transação ao apagar registo: " . $e->getMessage();
            }
            // Fechar ligação
            mysqli_close($link);
        }
    }
    else if ($_REQUEST['estado'] == 'editar')
    {
        echo "
            <h3>Dados de registo - edição</h3>
        ";
        $id = $_REQUEST['id'];
        //efetua query à base de dados
        $query = "select * 
            from child
            where id = ".$id;
        $result = mysqli_query($link,$query);
        $error = mysqli_error($link);
            if($error != "")
        {
                echo "
                Ocorreu um erro ao efetuar a query: ".$error;
        }
        else
        {
        //se existirem dados devolvidos
        if (mysqli_num_rows($result) > 0) 
        {
            while($rowRegisto = $result->fetch_assoc())
            {
            $Id =$rowRegisto["id"];
            $NomeC = $rowRegisto["name"];
            $DataN = $rowRegisto["birth_date"];
            $NomeCompE = $rowRegisto["tutor_name"];
            $Telefone = $rowRegisto["tutor_phone"];
            $email = $rowRegisto["tutor_email"];
            echo "
            <form id='formEditar' method='post' action=''>
            ";
                 
            echo "
                </br>
                <label for='Id'>Id:</label></br><input type='text' id='Id' name='Id' value = '$Id' readonly></br>
                ";
            echo "
                <label for='NomeC'>Nome Completo:</label></br><input type='text' id='NomeC' name='NomeC' value = '$NomeC'></br>
                ";
            echo "
                <label for='DataN'>Data de Nascimento (AAAA-MM-DD):</label></br><input type='text' id='DataN' name='DataN' value='$DataN'></br>
                ";
            echo "
                <label for='NomeCompE'>Nome Completo do Encarregado de Educação:</label></br><input type='text' id='NomeCompE' name='NomeCompE' value= '$NomeCompE'></br>
                ";
            echo "
                <label for='Telefone'>Telefone do Encarregado de Educação:</label></br><input type='text' id='Telefone' name='Telefone' value='$Telefone'></br>
                ";
            echo "
                <label for='email'>Endereço de email do tutor (opcional):</label></br><input type='text' id='email' name='email' value='$email'></br>
                ";
            echo "
            <input type='hidden' name='estado' id='estado' value='validarEdicao'></br>
            <input type='button' name='voltar' value='Voltar' onclick='history.go(-1)'>
            <input type='submit' value='Submeter'></form>
            ";
            }
        }
    }
    }
    else if ($_REQUEST['estado'] == 'apagar')
    {
        echo "
            <h3>Dados de registo - apagar</h3>
        ";
        $id = $_REQUEST['id'];


        echo "Tem a certeza que deseja remover o registo com id:". $id ."?";
         $query = "select * 
            from child
            where id = ".$id;
        $result = mysqli_query($link,$query);
        $error = mysqli_error($link);
            if($error != "")
        {
                echo "
                Ocorreu um erro ao efetuar a query: ".$error;
        }
        else
        {
        //se existirem dados devolvidos
        if (mysqli_num_rows($result) > 0) 
        {
            while($rowRegisto = $result->fetch_assoc())
            {
            $Id =$rowRegisto["id"];
            $NomeC = $rowRegisto["name"];
            $DataN = $rowRegisto["birth_date"];
            $NomeCompE = $rowRegisto["tutor_name"];
            $Telefone = $rowRegisto["tutor_phone"];
            $email = $rowRegisto["tutor_email"];
            echo "
            <form id='formApagar' method='post' action=''>
            ";
            echo "
                </br>
                <label for='Id'>Id:</label></br><input type='text' id='Id' name='Id' value = '$Id' readonly></br>
                ";
            echo "
                <label for='NomeC'>Nome Completo:</label></br><input type='text' id='NomeC' name='NomeC' value = '$NomeC' readonly></br>
                ";
            echo "
                <label for='DataN'>Data de Nascimento (AAAA-MM-DD):</label></br><input type='text' id='DataN' name='DataN' value='$DataN' readonly></br>
                ";
            echo "
                <label for='NomeCompE'>Nome Completo do Encarregado de Educação:</label></br><input type='text' id='NomeCompE' name='NomeCompE' value= '$NomeCompE' readonly></br>
                ";
            echo "
                <label for='Telefone'>Telefone do Encarregado de Educação:</label></br><input type='text' id='Telefone' name='Telefone' value='$Telefone' readonly></br>
                ";
            echo "
                <label for='email'>Endereço de email do tutor (opcional):</label></br><input type='text' id='email' name='email' value='$email' readonly></br>
                ";
            echo "
            <input type='hidden' name='estado' id='estado' value='apagarRegisto'></br>
            <input type='button' name='voltar' value='Voltar' onclick='history.go(-1)'>
            <input type='submit' value='Apagar'></form>
            ";
            }
        }
    }
    }
    
}
else
{
    echo "
    Nao tem autorização para aceder a esta página
    ";
}
?>