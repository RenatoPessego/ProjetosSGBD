<?php
global $link;
require_once("custom/php/common.php");
if(permissao('manage_subitems'))
{
    if(!isset($_REQUEST['estado']))
    {
        echo '
        <table>
            <tr>
                <th>item</th>
                <th>id</th>
                <th>subitem</th>
                <th>tipo de valor</th>
                <th>nome do campo no formulário</th>
                <th>tipo do campo no  formulário</th>
                <th>tipo de unidade</th>
                <th>ordem do campo no formulário</th>
                <th>obrigatório</th>
                <th>estado</th>
                <th>ação</th>
            </tr>
            ';
        $aitems = "SELECT *
              FROM item
              ORDER BY item.id";
        $resultadoitems = mysqli_query($link, $aitems);
        while ($items = mysqli_fetch_assoc($resultadoitems))
        {
            $querysubitem = 'SELECT * 
                   FROM subitem
                   WHERE subitem.item_id = ' . $items['id'];
            $resultado_subitem = mysqli_query($link, $querysubitem);
            $nrows = mysqli_num_rows($resultado_subitem);
            if ($nrows == 0)
            {
                echo '
            <tr>
                <td>' . $items['name'] . '</td>;
                <td colspan = 9> Não há subitens. </td> 
            </tr';
            }
            else
            {
                echo '
            <tr>
                <td rowspan = ' . $nrows . '>' . $items['name'] . '</td>';
                while ($dentro = mysqli_fetch_assoc($resultado_subitem))
                {
                    echo '<td>' . $dentro['id'] . '</td>
                    <td>' . $dentro['name'] . '</td>
                    <td>' . $dentro['value_type'] . '</td>
                    <td>' . $dentro['form_field_name'] . '</td>
                    <td>' . $dentro['form_field_type'] . '</td>';
                    if ($dentro['unit_type_id'] != NULL)
                    {
                        $queryunittype = 'SELECT subitem_unit_type.name
                                      FROM subitem_unit_type
                                      WHERE subitem_unit_type.id = ' . $dentro['unit_type_id'];
                        $resultadounittype = mysqli_query($link, $queryunittype);
                        $tabelaunittype = mysqli_fetch_assoc($resultadounittype);
                        echo '<td>' . $tabelaunittype['name'] . '</td>';
                    }
                    else
                    {
                        echo '<td> - </td>';
                    }
                    echo '<td>' . $dentro['form_field_order'] . '</td>';
                    if ($dentro['mandatory'] == 0)
                    {
                        echo '<td> não </td>';
                    }
                    else
                    {
                        echo '<td> sim </td>';
                    }
                    echo '<td>' . $dentro['mandatory'] . '</td>
                    <td>' . $dentro['state'] . '</td>
                    <td> '; 
                    editardados('subitem',$dentro['id']); 
                    if ($dentro['state'] == 'active'){
                        ativardados('subitem',$dentro['id'],1);
                    }
                    else{
                        ativardados('subitem',$dentro['id'],0);
                    }
                    deletardados('subitem',$dentro['id']);
                    echo '
                    </td>
                    </tr>';
                }
            }
        }
        echo '</table>';
        echo '<br> Gestão de subitems - Introdução
        <form>
        Nome: <input type="text" name="nome">
        Tipo: <br> ';
        $valorestipo = valoresenum($link,'subitem','value_type');
        $numvalorestipo = count($valorestipo);
        for($i = 0; $i < $numvalorestipo; $i++)
        {
            echo '<input type="radio" name= "valor_tipo" value="' . $valorestipo[$i] . '" >"'. $valorestipo[$i] .'"<br>';
        }
        echo ('Item: <br>
              <select name="item">');
        $queryitems = 'SELECT * 
                       FROM item ';
        $items = mysqli_query($link, $queryitems);
        while ($listaitems = mysqli_fetch_assoc($items))
        {
            echo '<option value="' . $listaitems['name'] . '" >'. $listaitems['name'] .'</option> <br>';
        }
        echo('</select>');
        echo('Tipo do campo do formulário: <br>');
        $valorestipocf = valoresenum($link,'subitem','form_field_type');
        $numvalorestipocf = count($valorestipocf);
        for($i = 0; $i < $numvalorestipocf; $i++)
        {
            echo '<input type="radio" name= "valor_tipo_cf" value="' . $valorestipocf[$i] . '" >"'. $valorestipocf[$i] .'"<br>';
        }
        echo ('Unidade do subitem: <br>
              <select name="subitem_unit_type">');
        $querysubunits = 'SELECT * FROM subitem_unit_type ';
        $resultsubunits = mysqli_query($link, $querysubunits);
        while ($listaunidades = mysqli_fetch_assoc($resultsubunits))
        {
            echo '<option value="' . $listaunidades['name'] . '" >'. $listaunidades['name'] .'</option> <br>';
        }
        echo ('<option value = "" >  </option>');
        echo('</select>
                Ordem: <input type="text" name="ordem"> 
                Obrigatório: <br>
                <input type="radio" name="obrigatorio" value="sim">Sim <br>
                <input type="radio" name="obrigatorio" value="nao">Não <br>
                <input type="hidden" name="estado" value="inserir">
                <input type="submit" name="submit" value="Submit">
                </form>'
        );
    }
    else if ($_REQUEST['estado'] == 'inserir')
    {
        echo '<br> Gestão de subitens - Inserção <br>';
        $nomeI = $_REQUEST['nome'];
        $tipovalorI = $_REQUEST["valor_tipo"];
        $itemI = $_REQUEST["item"];
        $tipoformularioI = $_REQUEST["valor_tipo_cf"];
        $tipounidadesubI = $_REQUEST["subitem_unit_type"];
        $ordemI = $_REQUEST["ordem"];
        $obrigatorioI = $_REQUEST["obrigatorio"];
        $nomedoformulario = NULL;
        if (empty($nomeI))
        {
            echo '<br>Por favor insira um nome.<br>';
            butaovoltar();
        }
        else
        {
            if (empty($tipovalorI))
            {
                echo '<br>Por favor insira um tipo de valor.<br>';
                butaovoltar();
            }
            else
            {
                if (empty($itemI))
                {
                    echo '<br>Por favor insira um item.<br>';
                    butaovoltar();
                }
                else
                {
                    if (empty($tipoformularioI))
                    {
                        echo '<br>Por favor insira um tipo de valor.<br>';
                        butaovoltar();
                    }
                    else
                    {
                        if (!is_int($ordemI) && $ordemI < 0)
                        {
                            echo '<br>Por favor insira uma ordem correta.<br>';
                            butaovoltar();
                        }
                        else
                        {
                            if (empty($obrigatorioI))
                            {
                                echo '<br>Por favor a obrigatoriedade do subitem.<br>';
                                butaovoltar();
                            }
                            else
                            {
                                if ($tipounidadesubI == "")
                                {
                                    $tipounidadesubI = NULL;
                                }
                                else
                                {
                                    $querysubitemtype = "SELECT * 
                                                         FROM subitem_unit_type 
                                                         WHERE subitem_unit_type.name = '$tipounidadesubI'";
                                    $resultadosubitemtype= mysqli_query($link, $querysubitemtype);
                                    while ($subitemtype1 = mysqli_fetch_assoc($resultadosubitemtype))
                                    {
                                        $tipounidadesubI = $subitemtype1['id'];
                                    };
                                }
                                $queryitem = "SELECT * 
                                              FROM item 
                                              WHERE item.name = '$itemI'";
                                $resultadoitem= mysqli_query($link, $queryitem);
                                while ($item1 = mysqli_fetch_assoc($resultadoitem))
                                {
                                    $itemid = $item1['id'];
                                };
                                if ($obrigatorioI == "sim")
                                {
                                    $obrigatorioI = 1;
                                }
                                else
                                {
                                    $obrigatorioI = 0;
                                }
                                if ($tipounidadesubI == NULL)
                                {
                                    $insercao = "INSERT INTO subitem (name,item_id,value_type,form_field_type,form_field_order,mandatory,state) 
                                             VALUES ('$nomeI','$itemid','$tipovalorI','$tipoformularioI','$ordemI','$obrigatorioI','active')";
                                }
                                else
                                {
                                    $insercao = "INSERT INTO subitem (name,item_id,value_type,form_field_type,unit_type_id,form_field_order,mandatory,state) 
                                             VALUES ('$nomeI','$itemid','$tipovalorI','$tipoformularioI','$tipounidadesubI','$ordemI','$obrigatorioI','active')";
                                }
                                $insercao_result = mysqli_query($link,$insercao);
                                if(!$insercao_result)
                                {
                                    echo 'Erro: '  . mysqli_error($link);
                                }
                                else
                                {
                                    $iddosub = mysqli_insert_id($link);
                                    $nomedoformulario = substr($itemI,0,3) . "-" . $iddosub . "-" . $nomeI;
                                    $nomedoformulario = preg_replace('/ /i', '_', $nomedoformulario);
                                    $atualiza = "UPDATE subitem
                                                SET form_field_name = '$nomedoformulario'
                                                WHERE  id = '$iddosub'";
                                    $resultado = mysqli_query($link,$atualiza);
                                    if ($resultado)
                                    {
                                        echo '<br>Inseriu os dados de subitem com sucesso.';
                                        echo "<a href='".htmlspecialchars($_SERVER["PHP_SELF"])."'>Continuar</a>";
                                    }
                                    else
                                    {
                                        echo 'Erro: '  . mysqli_error($link);
                                    }             
                                    
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>