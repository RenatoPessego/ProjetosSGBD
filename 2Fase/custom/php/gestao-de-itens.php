<?php
global $link;
require_once("custom/php/common.php");
if(permissao('manage_items'))
{
    if(!isset($_REQUEST['estado']))
    {
        echo '
        <table>
            <tr>
                <th>tipo de item</th>
                <th>id</th>
                <th>nome do item</th>
                <th>estado</th>
                <th>ação</th>
            </tr>';
        $itemtype = "SELECT *
              FROM item_type
              ORDER BY item_type.id";
        $resultadoit = mysqli_query($link, $itemtype);
        while ($itemtypes = mysqli_fetch_assoc($resultadoit))
        {
            $queryselect = 'SELECT * 
                   FROM item
                   WHERE item.item_type_id = ' . $itemtypes['id'];
            $resultadoselect = mysqli_query($link, $queryselect);
            $nrows = mysqli_num_rows($resultadoselect);
            echo '
            <tr>
                <td rowspan = ' . $nrows . '>' . $itemtypes['name'] . '</td>';
            if ($nrows == 0)
            {
                echo '<td> Não há itens. </td> </tr';
            }
            else
            {
                while ($dentro = mysqli_fetch_assoc($resultadoselect))
                {
                    echo '<td>' . $dentro['id'] . '</td>
                    <td>' . $dentro['name'] . '</td>
                    <td>' . $dentro['state'] . '</td>
                    <td> ';
                    editardados('item', $dentro['id']);  
                    if ($dentro['state'] == 'active'){
                        ativardados('item',$dentro['id'],1);
                    }
                    else{
                        ativardados('item',$dentro['id'],0);
                    }
                    deletardados('item',$dentro['id']);
                    echo '</td>
                    </tr>';
                };
            }
        }
        echo '</table>'; // estado_s é o estado de inserir ou não
        echo '<br> Gestão de Itens - Introdução
        <form>
        Nome: <input type="text" name="nome">
        Tipo: <br>';
        $tipoitems = "SELECT *
                      FROM item_type";
        $resultadoti = mysqli_query($link,$tipoitems);
        while ($listatipoitems = mysqli_fetch_assoc($resultadoti))
        {
            echo '<input type="radio" name= "tipo" value="' . $listatipoitems['name'] . '" >"'. $listatipoitems['name'] .'"<br>';
        }
        echo '
        Estado: <br>
        <input type="radio" name="estado" value="active">Ativo <br>
        <input type="radio" name="estado" value="inactive">Inativo <br>
        <input type="hidden" name="estado_s" value="inserir">
        <input type="submit" name="submit" value="Submit">
        </form> ';
    }
    else if ($_REQUEST['estado_s'] == 'inserir')
    {
        echo '<br> Gestão de Itens - Inserção <br>';
        $nomeI = $_REQUEST['nome'];
        $tipoI = $_REQUEST['tipo'];
        $estadoI = $_REQUEST['estado'];
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
                        $insercao = "INSERT INTO item(name,item_type_id, state) VALUES ('$nomeI','$iditemtype','$estadoI')";
                        $insercao_result = mysqli_query($link,$insercao);
                        if(!$insercao_result)
                        {
                            echo 'Erro: '  . mysqli_error($link);
                        }
                        else
                        {
                            echo '<br>Inseriu os dados de item com sucesso.';
                            echo "<a href='".htmlspecialchars($_SERVER["PHP_SELF"])."'>Continuar</a>";
                        }
                    }
                    break;
            }
        }
    }
}
?>