<?php
require_once("custom/php/common.php");
?>

<?php
global $pagina_atual;
global $link;
if(permissao('manage_unit_types'))
{
    if (!isset($_POST['estado']))
    {
        $query1 = "SELECT subitem_unit_type.name AS unidade, subitem_unit_type.id AS unidade_id,
       item.name AS item_nome, subitem.name as subitem_nome
       FROM subitem_unit_type LEFT JOIN subitem ON subitem_unit_type.id = subitem.unit_type_id 
       LEFT JOIN item ON subitem.item_id = item.id";
        $resultado1 = mysqli_query($link, $query1);
        echo "<table>
        <thead>
        <tr>
        <th>id</th>
        <th>unidade</th>
        <th>subitem</th>
        <th>ação</th>
        </tr>";

        if($resultado1 -> num_rows == 0)
        {
            echo "
            Não há tipos de unidades
            ";
        }
        else
        {
            $ultimo_id = 0;
            while ($linhas_unidades = $resultado1->fetch_assoc())
            {
                if($ultimo_id != $linhas_unidades["unidade_id"])
                {
                    echo"<tr>";
                    echo "<td> $linhas_unidades[unidade_id] </td>";
                    $ultimo_id = $linhas_unidades["unidade_id"];
                    echo"<td> $linhas_unidades[unidade] </td>";
                    $querytemp = "SELECT item.name AS item_nome_temp, subitem.name AS subitem_nome_temp FROM item 
                    INNER JOIN subitem ON item.id = subitem.item_id INNER JOIN subitem_unit_type
                    ON subitem.unit_type_id = subitem_unit_type.id WHERE subitem_unit_type.id = $ultimo_id";
                    $resultadotemp = mysqli_query($link, $querytemp);
                    echo "<td>";
                    $i = 0;
                    while($linhas_temp = $resultadotemp ->fetch_assoc())
                    {
                        if($i < mysqli_num_rows($resultadotemp) - 1)
                        {
                            echo "$linhas_temp[subitem_nome_temp] ($linhas_temp[item_nome_temp]), ";
                        }
                        else
                        {
                            echo "$linhas_temp[subitem_nome_temp] ($linhas_temp[item_nome_temp])";
                        }
                        $i = $i + 1;
                    }
                    echo "</td>";
                    echo"
                    <td>
                    ";
                    editardados('subitem_unit_type', $ultimo_id);
                    deletardados('subitem_unit_type', $ultimo_id);
                    echo
                    "
                    </td>
                    ";
                    echo"</tr>";
                }
            }

        }
        echo "</thead> </table>";
        echo"<tbody>";
        echo "<h3>Gestão de unidades - introdução</h3>";
        echo'<form method = "post">
        <label>nome:</label><br>
        <input type="text" id="nome" name="nome"><br>
        <input type="hidden" name="estado" value = "inserir"><br><br>
        <input type="submit" value="Inserir tipo de unidade">
        </form>';
        echo "</tbody>";

    }
    else if($_POST['estado'] = 'inserir')
    {
        echo '
        <h3>Gestão de unidades - inserção</h3>
        ';
        $inserir = "INSERT INTO subitem_unit_type (name) VALUES ('$_POST[nome]')";
        $novaunidade = $_POST["nome"];
        $unidade_valida = false;
        echo
        "
            Inseriu a unidade: $novaunidade
        ";
        echo"
        <br>
        ";
        if(preg_match('/^\s*$/u', $novaunidade))
        {
            $unidade_valida = false;
            echo
            "
                A unidade não pode ser vazia!
            ";
        }
        else if (!preg_match('/^[a-zA-Z0-9\/]*[a-zA-Z]+[a-zA-Z0-9\/]*$/', $novaunidade))
        {
            $unidade_valida = false;
            echo 
            "
            A unidade tem de ser alfanumérica!
            ";
        }
        else
        {
            $unidade_valida = true;
        }
        if($unidade_valida)
        {
        if (mysqli_query($link, $inserir)) 
        {
            echo 
            "
            Inseriu os dados de um novo tipo de unidade com sucesso! 
            ";
            echo 
            "
            Clique em continuar para avançar!
            ";
            echo 
            "
            <br>
            ";
            echo 
            "
            <a href='gestao-de-unidades'>
            ";
            echo 
            "
            Continuar
            ";
        }
        else
        {
            echo "Erro: " . $inserir . "<br>" . mysqli_error($link);
            echo
            "
            <br>
            ";
            butaovoltar();
        }
        }
        else
        {
            echo 
            "
            <br>
            ";
            echo
            "
            Por favor volte atrás e tente novamente!
            ";
            echo
            "
            <br>
            ";
            butaovoltar();
        }
    }
}