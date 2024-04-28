<?php
require_once("custom/php/common.php");

?>

<?php
global $pagina_atual;
global $link;
$resultado = null;

if(permissao('insert_values'))
{
    if (!isset($_REQUEST['estado']) )
    {
        echo
        "
        Introduza um dos nomes da criança a encontrar e/ou a data de nascimento dela
        <br>
        ";
        echo '
    <form>
    <label for="nome">nome:</label><br>
    <input type="text" id="nome" name="nome">
    <label for="data_de_nascimento">data de nascimento - (no formato AAAA-MM-DD):</label><br>
    <input type="text" id="data_de_nascimento" name ="data_de_nascimento" placeholder="(AAAA-MM-DD)">
    <input type="hidden" name="estado" value = "escolher_crianca"><br><br>
    <input type="submit" value="Submeter">
    </form>';
    }



     else if ($_REQUEST['estado'] == 'escolher_crianca')
     {
        echo "
        <h3>Inserção de valores - criança - escolher</h3>
        ";
         $nome = $_REQUEST['nome'];
         $data_de_nascimento = $_REQUEST['data_de_nascimento'];
         $continuar = true;
         if(!preg_match('/^[\p{L}]*$/u', $nome))
         {
            echo 
            "
            O nome a pesquisar não pode conter números ou caracteres especiais (que não sejam acentos)!
            ";
            $continuar = false;
         }

         if($data_de_nascimento != "" && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_de_nascimento))
         {
            echo
            "
            A data não é do tipo AAAA-MM-DD
            ";
            $continuar = false;
         }
         if($data_de_nascimento != "" && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_de_nascimento))
         {
            $data_separada = explode('-', $data_de_nascimento);
            if (checkdate($data_separada[1], $data_separada[2], $data_separada[0])) 
            {
                $continuar = true;
            } 
            else 
            {
                echo
                "
                A data não é valida!
                ";
                $continuar = false;
            }
         }
         
         if($continuar){
         $query = "SELECT name, birth_date, id FROM child";
         if ($nome != "" && $data_de_nascimento != "")
         {
             $query .= " WHERE name LIKE '$nome%' 
             AND birth_date LIKE '$data_de_nascimento'";
         }
         else if ($nome != "")
         {
             $query .= " WHERE name LIKE '$nome%'";
         }
         else if ($data_de_nascimento != "")
         {
             $query .= " WHERE birth_date LIKE '$data_de_nascimento'";
         }
         $resultado = mysqli_query($link, $query);
     if ($resultado != null)
     {

         while ($linhas = $resultado->fetch_assoc() )
         {
             echo "
             <a href='insercao-de-valores?estado=escolher_item&crianca=$linhas[id]'>
             ";
             echo "
             [$linhas[name]] ($linhas[birth_date])
             ";
             echo "</a><br>";
         }
         echo
         "
         <br>
         ";
         butaovoltar();
     }
    }
    else{
        echo"<br> Valores introuduzidos não são válidos! <br>";
        butaovoltar();
    }
    }
    else if($_REQUEST['estado'] == 'escolher_item')
     {
         $_SESSION['child_id'] = $_REQUEST['crianca'];
         $query2 = "SELECT item.id AS item_id, 
         item_type.name AS tipo_item_nome, 
         item.name AS item_nome 
         FROM item_type 
         INNER JOIN item on item_type.id = item.item_type_id 
         WHERE item.state LIKE 'active';";
         $resultado2 = mysqli_query($link, $query2);
         $ultimo_tipo_item = "";
         $ultimo_item_nome = "";
         $primeiroitem = true;
         if($resultado2 != null)
         {
             echo "
             <h3>Inserção de valores - escolher item</h3>
             ";
             echo"
             <ul>
             ";
             while ($linhas2 = $resultado2->fetch_assoc())
             {
                 if ($ultimo_item_nome != "$linhas2[tipo_item_nome]")
                 {
                    if(!$primeiroitem)
                    {
                       echo
                       "
                       </ul>
                       ";
                    }
                     echo "
                     <li><h5>$linhas2[tipo_item_nome]</h5></li>
                     ";
                     $ultimo_item_nome = "$linhas2[tipo_item_nome]";
                     echo "
                     <a href='insercao-de-valores?estado=introducao&item_id=$linhas2[item_id]'>
                     ";
                     echo "
                     <ul>
                     <li>$linhas2[item_nome]</li>
                     ";
                     $ultimo_tipo_item = "$linhas2[item_nome]";
                     echo "</a>";
                     $primeiroitem = false;
                 }
                 else if($ultimo_item_nome == "$linhas2[tipo_item_nome]")
                 {
                     if($ultimo_tipo_item != "$linhas2[item_nome]")
                     {
                         echo "
                         <a href='insercao-de-valores?estado=introducao&item_id=$linhas2[item_id]'>
                         ";
                         echo "
                        <li>$linhas2[item_nome]</li>
                         ";
                         $ultimo_tipo_item = "$linhas2[item_nome]";
                         echo "</a>";
                     }
                 }

             }
             echo
             "
             </ul>
             ";
         }
         echo"
         <br>
         ";
         butaovoltar();
     }
     else if($_REQUEST['estado'] == 'introducao')
     {
         $_SESSION['item_id'] = $_REQUEST['item_id'];
         $query3 = "SELECT item.name AS item_name, item.item_type_id 
         AS item_type_id FROM item WHERE item.id = $_SESSION[item_id]";
         $resultado3 = mysqli_query($link, $query3);
         while ($linhas3 = $resultado3->fetch_assoc())
         {
             $_SESSION['item_name'] = "$linhas3[item_name]";
             $_SESSION['item_type_id'] = "$linhas3[item_type_id]";
         }
         echo "
         <h3>Inserção de valores - $_SESSION[item_name]</h3>
         ";
         $query4 = "SELECT * FROM subitem WHERE item_id = $_SESSION[item_id]
          AND state = 'active' ORDER BY form_field_order";
         $resultado4 = mysqli_query($link, $query4);
         echo 
         "
         <form>
         <action = 'insercao-de-valores?estado=validar&item=$_SESSION[item_id]'>
         <input type='hidden' name='estado' value = 'validar'>";
         if(mysqli_num_rows($resultado4) != 0)
         {
         while($linhas_subitem = $resultado4 -> fetch_assoc())
         {
               $input = "";
               switch("$linhas_subitem[value_type]")
               {
                   case 'text':
                       $input = "$linhas_subitem[form_field_type]";
                       break;
                   case 'int':
                       $input = "text";
                       break;
                   case 'double':
                       $input = "text";
                       break;
                   case 'boolean':
                       $input = "radio";
                       break;
                   case 'enum':
                        $input = "$linhas_subitem[form_field_type]";
                       //radio, checkbox, select box, de acordo com o tipo de campo da BD form_field_name, opcoes obtidas a partir de uma query à tabela subitem_allowed_values;
                       break;
               }
               $unidade ="";
               if ("$linhas_subitem[unit_type_id]" != null)
               {
                   $query5 = "SELECT name FROM subitem_unit_type WHERE id = $linhas_subitem[unit_type_id]";
                   $resultado5 = mysqli_query($link, $query5);
                   while($linhas_unidades = $resultado5 -> fetch_assoc())
                   {
                       $unidade = "$linhas_unidades[name]";
                   }
               }
               $obrigatorio = "";
               if("$linhas_subitem[mandatory]")
               {
                   $obrigatorio = "required";
               }
               
               if($input == "text" || $input == "textbox" )
               {
                echo "$linhas_subitem[name]";
               echo 
               "
               <br>
               ";
               echo 
               "
               <input type = $input name =$linhas_subitem[name] $obrigatorio>
               $unidade <br>
               <br>
               ";
               }
               else if($input == "radio" && $linhas_subitem["value_type"] == "boolean")
               {
                $nome_subitem = str_replace(' ', '_', $linhas_subitem["name"]);
                echo
                "
                <input type = $input name =$nome_subitem value = true $obrigatorio>
                Verdadeiro
                <br>
                <input type = $input name =$nome_subitem value = false $obrigatorio>
                Falso
                <br>
                ";
               }
               else if($input == "radio")
               {
                    $query_enum = "SELECT * FROM subitem_allowed_value WHERE subitem_id = $linhas_subitem[id]";
                    $resultado_enum = mysqli_query($link, $query_enum);
                    echo 
                    "
                    $linhas_subitem[name]
                    ";
                    echo 
                    "
                    <br>
                    ";
                    $nome_subitem = str_replace(' ', '_', $linhas_subitem["name"]);
                    while($linhas_enum = $resultado_enum -> fetch_assoc())
                    {
                        echo
                        "
                        <input type = $input name =$nome_subitem value = $linhas_enum[value] $obrigatorio>
                        ";
                        echo 
                        "
                        $linhas_enum[value]
                        ";
                        echo 
                        "
                        <br>
                        ";
                    }
                    echo
                    "
                    <br>
                    ";
               }
               else if($input == "selectbox")
               {
                    $query_enum = "SELECT * FROM subitem_allowed_value WHERE subitem_id = $linhas_subitem[id]";
                    $resultado_enum = mysqli_query($link, $query_enum);
                    echo 
                    "
                    $linhas_subitem[name]
                    ";
                    echo 
                    "
                    <br>
                    ";
                    $nome_subitem = str_replace(' ', '_', $linhas_subitem["name"]);
                    echo
                    "
                    <label for=$linhas_subitem[name] </label>
                    <select name = $nome_subitem id = $nome_subitem >
                    <option value=  > </option>
                    ";
                    while($linhas_enum = $resultado_enum -> fetch_assoc())
                    {
                        echo
                        "
                            <option value=$linhas_enum[value]>$linhas_enum[value]</option>
                        ";
                        echo
                        "
                        <br>
                        ";
                    }
                    echo
                    "
                    </select>
                    ";
                    echo
                    "
                    <br>
                    <br>
                    ";
               }
               else if($input == "checkbox")
               {
                    $query_enum = "SELECT * FROM subitem_allowed_value WHERE subitem_id = $linhas_subitem[id]";
                    $resultado_enum = mysqli_query($link, $query_enum);
                    echo 
                    "
                    $linhas_subitem[name]
                    ";
                    echo 
                    "
                    <br>
                    ";
                    $nome_subitem = str_replace(' ', '_', $linhas_subitem["name"]);
                    while($linhas_enum = $resultado_enum -> fetch_assoc())
                    {
                        echo
                        "
                        <input type = $input name = $nome_subitem value = $linhas_enum[value]>
                        ";
                        echo 
                        "
                        $linhas_enum[value]
                        ";
                        echo 
                        "
                        <br>
                        ";
                    }
                    echo
                    "
                    <br>
                    ";
               }
               }

               echo '
                <input type="submit" value="Submeter">
               </form>';
                echo "
                <br>
                ";
                butaovoltar();
            }
            else
            {
                echo
                "
                Este item não possuí subitems.
                ";
                echo"<br>";
                butaovoltar();
            }
     }
     else if($_REQUEST['estado'] == 'validar')
     {
        $invalido = false;
        $query_validar = "SELECT * FROM subitem WHERE item_id = $_SESSION[item_id]
         AND state = 'active' ORDER BY form_field_order";
        $resultado_validar = mysqli_query($link, $query_validar);
         echo 
         "
         <h3>Inserção de valores - $_SESSION[item_name] - Validar</h3>
         ";
         echo 
         "
         <br>
         ";
         while($linhas_validar = $resultado_validar -> fetch_assoc())
         {
            $nome_temp = "$linhas_validar[name]";
            $nome_temp = str_replace(' ', '_', $nome_temp);
            $valortemp = $_REQUEST["$nome_temp"];
            if($linhas_validar["mandatory"])
            {
                if($valortemp == null || $valortemp == ""){
                    $invalido = true;
                    echo
                    "
                    $nome_temp é obrigatório, mas não recebeu nenhum valor!
                    ";
                }
                else
                {
                    $invalido =$invalido;
                }
            }
            if("$linhas_validar[value_type]" == "int")
            {

                if(!preg_match('/^[0-9]+$/', $valortemp))
                {
                    echo 
                    "
                    O valor de $nome_temp tem de ser um int!
                    ";
                    echo 
                    "
                    <br>
                    ";
                    $invalido = true;
                }
                else if($valortemp <= 0)
                {
                    echo 
                    "
                    O valor de $nome_temp tem de ser maior que 0!
                    ";
                    echo 
                    "
                    <br>
                    ";
                    $invalido = true;
                }
            }
            else if("$linhas_validar[value_type]" == "double")
            {
                if(!preg_match('/^[0-9]+(\.[0-9]+)?$/', $valortemp))
                {
                    echo 
                    "
                    O valor de $nome_temp tem de ser um double!
                    ";
                    echo 
                    "
                    <br>
                    ";
                    $invalido = true;
                }
                else if($valortemp <= 0)
                {
                    echo "O valor de $nome_temp tem de ser maior que 0!";
                    echo "<br>";
                    $invalido = true;
                }
            }
            else if("$linhas_validar[value_type]" == "text")
            {
                if(!preg_match('/^[a-zA-Z\s]+$/', $valortemp))
                {
                    echo"
                        O valor de $nome_temp tem de ser um texto!
                    ";
                    echo "
                    <br>
                    ";
                    $invalido = true;
                }
            }
            else if("$linhas_validar[value_type]" == "enum")
            {
                $tabela_valores_possiveis = "SELECT value FROM subitem_allowed_value 
                WHERE subitem_allowed_value.subitem_id = $linhas_validar[id]";
                $valores_possiveis = mysqli_query($link, $tabela_valores_possiveis);
                $valor_permitido = false;
                while($linhas_valores_possiveis = $valores_possiveis ->fetch_assoc()) 
                {
                    if($valortemp == $linhas_valores_possiveis["value"])
                    {
                        $valor_permitido = true;
                        break;
                    }
                    else
                    {
                        continue;
                    }
                }
                if($valor_permitido == false)
                {
                    $invalido = true;
                    echo
                    "
                        O valor selecionado não é permitido!
                    ";
                    echo"
                    <br>
                    ";
                }
                else if($valor_permitido == true)
                {
                    $invalido = false;
                }
            }
            else if("$linhas_validar[value_type]" == "boolean")
            {
                if($valortemp != true && $valortemp != false)
                {
                    echo
                    "
                        O valor de $nome_temp tem de ser um booleano!
                    ";
                }
            }
         }
         if($invalido)
         {
         butaovoltar();
         }
         else if($invalido == false)
         {
            $query_validar2 = "SELECT * FROM subitem 
            WHERE item_id = $_SESSION[item_id]
            AND state = 'active'
            ORDER BY form_field_order";
           $resultado_validar2 = mysqli_query($link, $query_validar2);
            while($linhas_validar2 = $resultado_validar2 ->fetch_assoc())
            {
                $nome_temp2 = "$linhas_validar2[name]";
                $nome_temp2 = str_replace(' ', '_', $nome_temp2);
                $valortemp2 = $_REQUEST["$nome_temp2"];
                $unidade2 ="";
               if ("$linhas_validar2[unit_type_id]" != null)
               {
                   $queryunidades = "SELECT name FROM subitem_unit_type 
                   WHERE id = $linhas_validar2[unit_type_id]";
                   $resultadounidades = mysqli_query($link, $queryunidades);
                   while($linhas_unidades2 = $resultadounidades -> fetch_assoc())
                   {
                       $unidade = "$linhas_unidades2[name]";
                   }
               }
                echo 
                "
                    $linhas_validar2[name]: $valortemp2 $unidade
                ";
                echo
                "
                <br>
                ";
            }
            echo
            "
            <br>
            ";
            echo "
            Estamos prestes a inserir os dados acima na base de dados.
            ";
            echo
            "
            <br>
            ";
            echo
            "
            Confirma que os dados estão corretos e pretende submeter os mesmo?
            ";
            echo
            "
            <br>
            ";
            $query_validar3 = "SELECT * FROM subitem 
            WHERE item_id = $_SESSION[item_id]
            AND state = 'active' ORDER BY form_field_order";
           $resultado_validar3 = mysqli_query($link, $query_validar3);
           echo"
           <form>
           ";
           while($linhas_validar3 = $resultado_validar3 -> fetch_assoc())
            {
            $nome_temp3 = "$linhas_validar3[name]";
            $nome_temp3 = str_replace(' ', '_', $nome_temp3);
            $valortemp3 = $_REQUEST["$nome_temp3"];
            echo
            "
                <input type='hidden' name=$nome_temp3 value = $valortemp3>
            ";
            }
            echo"
            <input type = 'hidden' name = 'estado' value = 'inserir'>
            <input type = 'submit' value = 'Submeter'>
            </form>
            ";
            echo
            "
            <br>
            ";
            butaovoltar();
         }
     }
     else if($_REQUEST['estado'] == 'inserir')
     {
        echo "
        <h3>Inserção de valores - $_SESSION[item_name] - inserção</h3>
        ";
        $query_subitem_id = "SELECT * FROM subitem 
            WHERE item_id = $_SESSION[item_id]
            AND state = 'active' ORDER BY form_field_order";
        $resultado_subitem_id = mysqli_query($link, $query_subitem_id);
        $data_atual = date("Y-m-d");
        $horario = date("H:i:s");
        while($linhas_subitem_id = $resultado_subitem_id -> fetch_assoc()){
            $utilizador = wp_get_current_user();
            $id_crianca = $_SESSION["child_id"];
            $nome_subitem = $linhas_subitem_id["name"];
            
            $query_inserir = "INSERT INTO `value` (`child_id`, `subitem_id`, `value`, `date`, `time`, `producer`)
            VALUES ('$id_crianca', '$linhas_subitem_id[id]', '$_REQUEST[$nome_subitem]', '$data_atual','$horario','$utilizador->user_login')";
            if (mysqli_query($link, $query_inserir)) 
                    {
                        echo "
                        Inseriu os valores corretamente!
                        ";
                    }
                    else
                    {
                        echo "Erro: " . $query_inserir . "<br>" . mysqli_error($link);
                        echo"<br>";
                        butaovoltar();
                    }
        }
        echo "
        <br>
        ";
        echo
        "
        Clique em Voltar para voltar ao início da inserção de valores ou
         em Escolher item se quiser continuar a inserir valores associados a esta criança.
        ";
        echo
        "
        <form>
        <input type = 'hidden' name = 'estado' value = 'escolher_item'>
        <input type = 'hidden' name = 'crianca' value = '$_SESSION[child_id]'>
        <input type = 'submit' value = 'Escolher'>
        </form>
        ";

        echo
        "
        <br>
        ";
        echo
        "
        <form>
        <input type = 'submit' value = 'Voltar'>
        </form>
        ";
     }

    }
?>