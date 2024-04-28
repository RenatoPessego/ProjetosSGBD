<?php 
require_once("custom/php/common.php");
// Verifica se o estado está definido na variável REQUEST
$executionState = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : '';
$host='http://' . $_SERVER['SERVER_NAME'].'/sgbd';
echo "<link rel='stylesheet' type='text/css' href=".$host."/custom/php/estilos.css>";
//verifica se tem login feito e se tem permissoes para gerir os dados que estao active
if (is_user_logged_in() && current_user_can('manage_records'))
{
    if(!isset($_REQUEST['estado']))
    {
        echo "
        <table class='table'><tr><th style='width:350px'>item</th><th style='width:80px'>id</th><th style='width:350px'>subitem</th><th style='width:80px'>id</th><th style='width:350px'>valores permitidos</th><th>estado</th><th style='width:150px'>ação</th></tr>
        ";
        //query para obter os items
        $queryItems= "SELECT name, id FROM item 
        WHERE state='active' 
        ORDER BY name";
        $resultItems = mysqli_query($link,$queryItems);
        $error = mysqli_error($link);
        if($error != "")
        {
            echo "
            Ocorreu um erro ao efetuar a query: ".$error;
        }
        else
        {
            if (mysqli_num_rows($resultItems) > 0) 
            {
                while($rowItem = $resultItems->fetch_assoc()) 
                {
                    $itemId = $rowItem["id"];
                    $itemName =$rowItem['name'];
                    $querySubItems = 
                    "SELECT subitem.id as subitemId, subitem.name as subitemName, subitem_allowed_value.id as allowedId, subitem_allowed_value.value as allowedValue, subitem_allowed_value.state as allowedState 
                    FROM subitem 
                    left outer join subitem_allowed_value 
                    on subitem_allowed_value.subitem_id = subitem.id 
                    WHERE value_type = 'enum' AND subitem.item_id =". $itemId . 
                    " ORDER BY subitem.id, subitem_allowed_value.id";
                    $resultSubItems = mysqli_query($link,$querySubItems);
                    $errorSubItems = mysqli_error($link);
                    if($errorSubItems != "")
                    {
                        echo "
                        Ocorreu um erro ao efetuar a query: ".$errorSubItems;
                    }
                    else
                    {
                        $numeroRows = mysqli_num_rows($resultSubItems);
                        //verifica se é apenas uma linha, se for nao muda o rowspan
                        if ($numeroRows==0)
                        {
                            echo "
                            <tr>
                                <td>" . $itemName. "</td>";
                        }
                        else{
                            echo "
                            <tr>
                                <td rowspan = " . $numeroRows . ">" . $itemName."</td>";
                        }
                        if ($numeroRows > 0)
                        {
                            $subitemId=0;
                            while ($rowSubitems = mysqli_fetch_assoc($resultSubItems))
                            {
                                if ($subitemId !=$rowSubitems['subitemId'])
                                {
                                    $subitemId=$rowSubitems['subitemId'];
                                    $queryNumValues ="Select count(subitem.id) as nValues from subitem, subitem_allowed_value
                                        where subitem.id = subitem_allowed_value.subitem_id and subitem.id =" . $subitemId;
                                    $resultNumValues = mysqli_query($link,$queryNumValues);
                                    $errorNumValues = mysqli_error($link);
                                    if($errorNumValues != "")
                                    {
                                        echo "
                                        Ocorreu um erro ao efetuar a query: ".$errorNumValues;
                                    }
                                    else
                                    {
                                        
                                        while ($rowNumValues = mysqli_fetch_assoc($resultNumValues))
                                        {
                                           
                                            $num = $rowNumValues['nValues'];
                                            //se tem values entõa faz rowspan
                                            if($num>0)
                                            {

                                                $tabela = 'subitem_allowed_value';
                                                $id = $rowSubitems['allowedId'];
                                                echo "<td rowspan=".$num .">".$subitemId."</td>
                                                <td rowspan=".$num ."><a href=" . $host . "/gestao-de-valores-permitidos?estado=introducao&subitem=".$subitemId.">[".$rowSubitems['subitemName']."]</a></td>";
                                                
                                                echo "
                                                <td>".$id."</td>
                                                <td>".$rowSubitems['allowedValue']."</td>
                                                <td>".$rowSubitems['allowedState']."</td>
                                                <td>";
                                                echo editardados($tabela,$id);
                                                echo ativardados($tabela,$id,'desativar');
                                                echo deletardados($tabela,$id);
                                                echo "</td>
                                                </tr>";
                                            }
                                            //se nao tem values entao nao faz rowspan
                                            else
                                            {
                                                echo "<td>".$subitemId."</td>
                                                <td><a href=" . $host . "/gestao-de-valores-permitidos?estado=introducao&subitem=".$subitemId.">[".$rowSubitems['subitemName']."]</a></td>";
                                                echo "<td colspan='4'>Não há valores permitidos definidos</td> 
                                                </tr>";
                                            }  
                                        }  
                                    }

                                }
                                //o id do subitem é o mesmo mas os allowed values sao outros
                                else
                                {
                                    $tabela = 'subitem_allowed_value';
                                    $id = $rowSubitems['allowedId'];
                                    echo"<tr>
                                    <td>".$id."</td>
                                    <td>".$rowSubitems['allowedValue']."</td>
                                    <td>".$rowSubitems['allowedState']."</td>
                                    <td>";
                                    echo editardados($tabela,$id);
                                    echo ativardados($tabela,$id,'desativar');
                                    echo deletardados($tabela,$id);
                                    echo "</td>
                                    </tr>";
                                }
                                
                            };    
                        }
                        else
                        {
                            echo "<td colspan=6> Não há subitems especificados cujo tipo de valor seja enum. Especificar primeiro novo(s) item(s) e depois voltar a esta opção.</td></tr>";
                        }
                    }
                }
            }
            else
                echo "<tr><td colspan=7>Nao existem items. Especificar primeiro novo(s) item(s) e depois voltar a esta opção</td></tr>";
        }
        echo "</table>";
    }
    else if ($_REQUEST['estado'] == 'introducao')
    {
        if (isset($_REQUEST['subitem'])) 
            {
                $_SESSION['subitem_id'] = $_REQUEST['subitem'];
            }
        // Exibe o formulário de introdução
        echo "
        <h3>Gestão de valores permitidos - introdução</h3>
        ";
        echo "
        <form method='post' action=''>
        ";
        echo "
        <label for='valor'>Valor:</label>
        ";
        echo "
        <input type='text' name='valor' required>
        ";
        echo "
        <input type='hidden' name='estado' value='inserir'> ";
        echo "
        <input type='button' name='voltar' value='Voltar' onclick='history.go(-1)'>
        ";
        echo "
        <input type='submit' value='Inserir valor permitido'>
        ";
        echo "
        </form>
        ";
    }
    else if ($_POST )
    {
        if ($_POST['estado']=='inserir')
        {
            echo "Gestão de valores permitidos - inserção";
            $subitemid =  $_SESSION['subitem_id'];
            $valor = $_POST['valor'];

            $sql = "INSERT INTO subitem_allowed_value (subitem_id, value, state) VALUES ('$subitemid', '$valor', 'active')";
            $sql_result = mysqli_query($link,$sql);
            if(!$sql_result)
            {
                echo "Erro: ".mysqli_error($link);
            }
            else
            {
                echo "
                <p class='success-message'>Inseriu os dados de novo valor permitido com sucesso.</p>
                ";
                echo 
                    "<p>Clique em Continuar para avançar.</p>
                    ";
                echo "<a href='gestao-de-valores-permitidos'>
                    <button class='button-style'>Continuar</button>
                </a>";
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
