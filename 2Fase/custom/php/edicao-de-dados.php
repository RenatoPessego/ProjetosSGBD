<?php
require_once("custom/php/common.php");
global $link;
$estadoR = $_GET['estado'];
$tabelaR = $_GET['tabela'];
$idR = $_GET['id'];
if(permissao('edit_values'))
{
   if ($_GET['estado'] == 'editar')
   {
        switch($tabelaR)
        {
            case 'item':
                $querydados = "SELECT *
                               FROM $tabelaR
                               WHERE id = '$idR'";
                $resultadodados = mysqli_query($link,$querydados);
                while ($dadosdoitem = mysqli_fetch_assoc($resultadodados))
                {
                    echo 'Altere os dados conforme o necessário. <br> <form>
                    Nome: <br>
                    <input type = "text" name="nome" value="' . $dadosdoitem['name'] . '">';
                    $tipoitems = "SELECT *
                                  FROM item_type";
                    $resultadoti = mysqli_query($link,$tipoitems);
                    echo 'Tipo: <br>';
                    while ($listatipoitems = mysqli_fetch_assoc($resultadoti))
                    {
                        if ($dadosdoitem['item_type_id'] == $listatipoitems['id'])
                        {
                            echo '<input type="radio" name= "tipo" value="' . $listatipoitems['name'] . '" checked >"'. $listatipoitems['name'] .'"<br>';
                        }
                        else
                        {
                            echo '<input type="radio" name= "tipo" value="' . $listatipoitems['name'] . '" >"'. $listatipoitems['name'] .'"<br>';
                        }
                    }
                    echo 'Estado: <br>';
                    if ($dadosdoitem['state'] == 'active')
                    {
                        echo '
                        <input type="radio" name="estadoi" value="active" checked>Ativo <br>
                        <input type="radio" name="estadoi" value="inactive">Inativo <br>';
                    }
                    else if ($dadosdoitem['state'] == 'inactive')
                    {
                        echo '
                        <input type="radio" name="estadoi" value="active">Ativo <br>
                        <input type="radio" name="estadoi" value="inactive" checked>Inativo <br>';
                    }
                    echo '
                    <input type="hidden" name="estado" value="inserirI">
                    <input type="hidden" name="tabela" value="' . $tabelaR . '">
                    <input type="hidden" name="id" value="' . $idR . '">
                    <input type="submit" name="submit" value="Submit">
                    </form> ';
                }
            case 'subitem':
                $querydados = "SELECT *
                               FROM $tabelaR
                               WHERE id = '$idR'";
                $resultadodados = mysqli_query($link,$querydados);
                while ($dadosdoitem = mysqli_fetch_assoc($resultadodados))
                {
                    echo 'Altere os dados conforme o necessário. <br> <form>
                    Nome: <br>
                    <input type = "text" name="nome" value="' . $dadosdoitem['name'] . '">
                    Tipo: <br> ';
                    $valorestipo = valoresenum($link,'subitem','value_type');
                    $numvalorestipo = count($valorestipo);
                    for($i = 0; $i < $numvalorestipo; $i++)
                    {
                        if ($dadosdoitem['value_type'] == $valorestipo[$i])
                        {
                            echo '<input type="radio" name= "valor_tipo" value="' . $valorestipo[$i] . '" checked >"'. $valorestipo[$i] .'"<br>';
                        }
                        else
                        {
                            echo '<input type="radio" name= "valor_tipo" value="' . $valorestipo[$i] . '" >"'. $valorestipo[$i] .'"<br>';
                        }
                        
                    }
                    echo ('Item: <br>
                    <select name="item">');
                     $queryitems = 'SELECT * 
                                    FROM item ';
                    $items = mysqli_query($link, $queryitems);
                    while ($listaitems = mysqli_fetch_assoc($items))
                    {
                        if ($dadosdoitem['item_id'] == $listaitems['id'])
                        {
                            echo '<option value="' . $listaitems['name'] . '" selected >'. $listaitems['name'] .'  </option> <br>';
                        }
                        else
                        {
                            echo '<option value="' . $listaitems['name'] . '" >'. $listaitems['name'] .'</option> <br>';
                        }
                    }
                    echo('</select>'); 
                    echo('Tipo do campo do formulário: <br>');
                    $valorestipocf = valoresenum($link,'subitem','form_field_type');
                    $numvalorestipocf = count($valorestipocf);
                    for($i = 0; $i < $numvalorestipocf; $i++)
                    {
                        if ($dadosdoitem['form_field_type'] == $valorestipocf[$i])
                        {
                            echo '<input type="radio" name= "valor_tipo_cf" value="' . $valorestipocf[$i] . '" checked >"'. $valorestipo[$i] .'"<br>';
                        }
                        else
                        {
                            echo '<input type="radio" name= "valor_tipo_cf" value="' . $valorestipocf[$i] . '" >"'. $valorestipo[$i] .'"<br>';
                        }
                    }
                    echo ('Unidade do subitem: <br>
                        <select name="subitem_unit_type">');
                    $querysubunits = 'SELECT * FROM subitem_unit_type ';
                    $resultsubunits = mysqli_query($link, $querysubunits);
                    $selected = 0;
                    while ($listaunidades = mysqli_fetch_assoc($resultsubunits))
                    {
                        if ($dadosdoitem['unit_type_id'] == $listaunidades['id'])
                        {
                            echo '<option value="' . $listaunidades['name'] . '" selected >'. $listaunidades['name'] .' </option> <br>';
                            $selected = 1;
                        }
                        else
                        {
                            echo '<option value="' . $listaunidades['name'] . '" >'. $listaunidades['name'] .'</option> <br>';
                        }
                    }
                    if ($selected == 0)
                    {
                        echo ('<option value = "" > selected </option>');
                    }
                    else 
                    {
                        echo ('<option value = "" >  </option>');
                    }
                    echo('</select>
                            Ordem: <input type="text" name="ordem" value="' . $dadosdoitem['form_field_order'] . '"> 
                            Obrigatório: <br>');
                    if ($dadosdoitem['mandatory'] == 1)
                        {
                            echo '
                            <input type="radio" name="obrigatorio" value="sim" checked>Sim <br>
                            <input type="radio" name="obrigatorio" value="nao">Não <br>';
                        }
                    else if ($dadosdoitem['mandatory'] == 0)
                        {
                            echo '
                            <input type="radio" name="obrigatorio" value="sim">Sim <br>
                            <input type="radio" name="obrigatorio" value="nao" checked>Não <br>';
                        }
                    echo 'Estado: <br>';
                        if ($dadosdoitem['state'] == 'active')
                        {
                            echo '
                            <input type="radio" name="estadoi" value="active" checked>Ativo <br>
                            <input type="radio" name="estadoi" value="inactive">Inativo <br>';
                        }
                        else if ($dadosdoitem['state'] == 'inactive')
                        {
                            echo '
                            <input type="radio" name="estadoi" value="active">Ativo <br>
                            <input type="radio" name="estadoi" value="inactive" checked>Inativo <br>';
                        }    
                    echo'
                        <input type="hidden" name="estado" value="inserirS">
                        <input type="hidden" name="tabela" value="' . $tabelaR . '">
                        <input type="hidden" name="id" value="' . $idR . '">
                        <input type="submit" name="submit" value="Submit">
                        </form>';   
                }
            case 'subitem_unit_type':
                $querydados = "SELECT *
                               FROM $tabelaR
                               WHERE id = '$idR'";
                $resultadodados = mysqli_query($link,$querydados);
                while ($dadosdoitem = mysqli_fetch_assoc($resultadodados))
                {
                    echo 'Altere os dados conforme o necessário. Id: ' . $idR . ' <br> <form>
                    Nome: <br>
                    <input type = "text" name="nome" value="' . $dadosdoitem['name'] . '">
                    <input type="hidden" name="estado" value="inserirU">
                    <input type="hidden" name="tabela" value="' . $tabelaR . '">
                    <input type="hidden" name="id" value="' . $idR . '">
                    <input type="submit" name="submit" value="Submit">
                    </form>';
                }         
        }    
   }
   else if ($_GET['estado'] == 'ativar')
   { 
        echo 'Deseja ativar o elemento?';
        echo '<form>
        <input type="hidden" name="estado" value="ativar2">
        <input type="hidden" name="tabela" value="' . $tabelaR . '">
        <input type="hidden" name="id" value="' . $idR . '">
        <input type="submit" name="Sim" value="Sim"> 
        </form>';
        butaovoltar();
   }
   else if ($_GET['estado'] == 'ativar2')
   {
        $queryativar = "UPDATE $tabelaR
                        SET state = 'active'
                        WHERE id = '$idR'";
        $resultadoQA = mysqli_query($link,$queryativar);
        if ($resultadoQA)
        {
            echo '<br>Atualizou o estado com sucesso.';
            echo "<a href='".htmlspecialchars($_SERVER["PHP_SELF"])."'>Continuar</a>";
        }
        else
        {
            echo 'Erro: '  . mysqli_error($link);
        }                             
   }
   else if ($_GET['estado'] == 'desativar')
   {
        echo 'Deseja desativar o elemento?';
        echo '<form>
        <input type="hidden" name="estado" value="desativar2">
        <input type="hidden" name="tabela" value="' . $tabelaR . '">
        <input type="hidden" name="id" value="' . $idR . '">
        <input type="submit" name="Sim" value="Sim"> 
        </form>';
        butaovoltar();
   }
   else if ($_GET['estado'] == 'desativar2')
   {
        $queryativar = "UPDATE $tabelaR
                        SET state = 'inactive'
                        WHERE id = '$idR'";
        $resultadoQA = mysqli_query($link,$queryativar);
        if ($resultadoQA)
        {
            echo '<br>Atualizou o estado com sucesso.';
            echo "<a href='".htmlspecialchars($_SERVER["PHP_SELF"])."'>Continuar</a>";
        }
        else
        {
            echo 'Erro: '  . mysqli_error($link);
        }                             
   }
   else if ($_GET['estado'] == 'inserirI')
   {
        $nomeI = $_REQUEST['nome'];
        $tipoI = $_REQUEST['tipo'];
        $estadoI = $_REQUEST['estadoi'];
        if (empty($nomeI))
        {
            echo '<br>Por favor insira um nome.<br>';
            butaovoltar();
        }
        else
        {
            switch($estadoI)
            {
                case $estadoI != 'active' && $estadoI != 'inactive':
                    echo '<br> Por favor insira um estado.<br>';
                    butaovoltar();
                    break;
                default:
                    if (empty($tipoI))
                    {
                        echo '<br> Por favor insira um tipo.<br>';
                        butaovoltar();
                        break;
                    }
                    else
                    {
                        $iditemtype = NULL;
                        $tipoitems = "SELECT *
                                      FROM item_type
                                      WHERE item_type.name = '$tipoI'";
                        $resultadoti = mysqli_query($link,$tipoitems);
                        while ($finditemtype = mysqli_fetch_assoc($resultadoti))
                        {
                            $iditemtype = $finditemtype['id'];
                        }
                        $atualizacao = "UPDATE item
                                        SET name = '$nomeI', state = '$estadoI', item_type_id = '$iditemtype'
                                        WHERE id = '$idR'";
                        $atualizacao_result = mysqli_query($link,$atualizacao);                
                        if(!$atualizacao_result)
                        {
                            echo 'Erro: '  . mysqli_error($link);
                        }
                        else
                        {
                            echo '<br>Editou os dados de item com sucesso.';
                            echo "<a href='".htmlspecialchars($_SERVER["PHP_SELF"])."'>Continuar</a>";
                        }
                    }
            }
        }
   }
   else if ($_GET['estado'] == 'inserirS')
   {
    $nomeI = $_REQUEST['nome'];
    $tipovalorI = $_REQUEST["valor_tipo"];
    $itemI = $_REQUEST["item"];
    $tipoformularioI = $_REQUEST["valor_tipo_cf"];
    $tipounidadesubI = $_REQUEST["subitem_unit_type"];
    $ordemI = $_REQUEST["ordem"];
    $obrigatorioI = $_REQUEST["obrigatorio"];
    $estadoI = $_REQUEST["estadoi"];
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
                            $nomedoformulario = substr($itemI,0,3) . "-" . $idR . "-" . $nomeI;
                            $nomedoformulario = preg_replace('/ /i', '_', $nomedoformulario);
                            if ($tipounidadesubI == NULL)
                            {
                                $atualizacao = "UPDATE subitem
                                SET name = '$nomeI', item_id = '$itemid', value_type = '$tipovalorI', form_field_name = '$nomedoformulario', form_field_type = '$tipoformularioI',
                                mandatory = '$obrigatorioI', form_field_order = '$ordemI', state = '$estadoI'
                                WHERE id = '$idR'";
                            }
                            else
                            {
                                $atualizacao = "UPDATE subitem
                                SET name = '$nomeI', item_id = '$itemid', value_type = '$tipovalorI', form_field_name = '$nomedoformulario', form_field_type = '$tipoformularioI',
                                mandatory = '$obrigatorioI', form_field_order = '$ordemI', state = '$estadoI', unit_type_id = $tipounidadesubI
                                WHERE id = '$idR'";
                            }
                            $atualizacao_result = mysqli_query($link,$atualizacao);
                            if ($atualizacao_result)
                                {
                                    echo '<br>Atualizou os dados de subitem com sucesso.';
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
   else if ($_GET['estado'] == 'inserirU')
   {
    $nomeI = $_REQUEST['nome'];
    if (empty($nomeI))
    {
        echo '<br>Por favor insira um nome.<br>';
        butaovoltar();
    }
    else
    {
        $atualizacao = "UPDATE subitem_unit_type
                        SET name = '$nomeI'
                        WHERE id = '$idR'";
        $atualizacao_result = mysqli_query($link,$atualizacao);
        if ($atualizacao_result)
            {
                echo '<br>Atualizou os dados da unidade com sucesso.';
                echo "<a href='".htmlspecialchars($_SERVER["PHP_SELF"])."'>Continuar</a>";
            }
            else
            {
                echo 'Erro: '  . mysqli_error($link);
            }  
        }                
    }
    else if ($_GET['estado'] == 'apagar')
    {
        echo 'Deseja eliminar o elemento?';
        echo '<form>
        <input type="hidden" name="estado" value="apagar2">
        <input type="hidden" name="tabela" value="' . $tabelaR . '">
        <input type="hidden" name="id" value="' . $idR . '">
        <input type="submit" name="Sim" value="Sim"> 
        </form>';
        butaovoltar();
    }
    else if ($_GET['estado'] == 'apagar2')
    {
        switch ($tabelaR){
            case 'subitem_unit_type': 
                $queryitemsdependentes = "SELECT *
                                          FROM subitem
                                          WHERE subitem.unit_type_id = '$idR'";
                $resultadoitemsdependentes = mysqli_query($link,$queryitemsdependentes);
                while ($itemsdependentes = mysqli_fetch_assoc($resultadoitemsdependentes))
                {
                    $itemsid = $itemsdependentes['id'];
                    $querydesliga = "SET FOREIGN_KEY_CHECKS=0;";
                    $queryliga = "SET FOREIGN_KEY_CHECKS=1;";
                    mysqli_query($link,$querydesliga);
                    $queryremoveunit = "UPDATE subitem
                                       SET unit_type_id = NULL
                                       WHERE id = '$itemsid'";
                    mysqli_query($link,$queryitemsdependentes);
                    mysqli_query($link,$queryliga);                   
                } 
                $queryremoveitem = "DELETE FROM subitem_unit_type WHERE id = '$idR'";
                $resultadoremoveitem = mysqli_query($link,$queryremoveitem);
                if ($resultadoremoveitem)
                {
                    echo '<br>Removeu o tuplo com sucesso.';
                    echo "<a href='".htmlspecialchars($_SERVER["PHP_SELF"])."'>Continuar</a>";
                }
                else
                {
                    echo 'Erro: '  . mysqli_error($link);
                }  
        }                            

    }
}               

