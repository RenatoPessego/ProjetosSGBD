<?php
setlocale(LC_ALL, "pt_PT.utf-8");
global $pagina_atual;
$pagina_atual = get_site_url() . '/' . basename(get_permalink());
global $link;
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME)
OR die('Connection failed: ' . mysqli_connect_error());
function permissao($capability)
{
    if(is_user_logged_in())
    {
        if(current_user_can($capability))
        {
            return true;
        }
        else
        {
            echo "Não tem autorização para aceder a esta página";
            return false;
        }
    }
    else
    {
        echo "Utilizador não fez o login.";
        return false;
    }
}
function butaovoltar()
{
    echo "<script type='text/javascript'>document.write(\"<a href='javascript:history.back()' class='backLink'\ +title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>\");</script>
        <noscript>
        <a href='".$_SERVER['HTTP_REFERER']."‘ class='backLink' title='Voltar atr&aacute;s'>Voltar atr&aacute;s</a>
        </noscript>";
}
function valoresenum($connection, $table, $column)
{
    $query = " SHOW COLUMNS FROM `$table` LIKE '$column' ";
    $result = mysqli_query($connection, $query );
    $row = mysqli_fetch_array($result , MYSQLI_NUM );
    $regex = "/'(.*?)'/";
    preg_match_all( $regex , $row[1], $enum_array );
    $enum_fields = $enum_array[1];
    return( $enum_fields );
}
function editardados($table,$id)
{
    $estado = 'editar';
    $urlEdicao = "http://localhost/sgbd/edicao-de-dados/?estado=". $estado . "&tabela=" . $table . "&id=" . $id;
    echo "<a href='$urlEdicao'> [editar] </a>";
}
function ativardados($table,$id,$state)
{
    if ($state == 0)
    {
        $estado = 'ativar';
    }
    else
    {
        $estado = 'desativar';
    }
    $urlEdicao = "http://localhost/sgbd/edicao-de-dados/?estado=". $estado . "&tabela=" . $table . "&id=" . $id;
    if ($state == 0)
    {
        echo "<a href='$urlEdicao'> [ativar] </a>";
    }
    else
    {
        echo "<a href='$urlEdicao'> [desativar] </a>";
    }
}
function deletardados($table,$id)
{
    $estado = 'apagar';
    $urlEdicao = "http://localhost/sgbd/edicao-de-dados/?estado=". $estado . "&tabela=" . $table . "&id=" . $id;
    echo "<a href='$urlEdicao'> [apagar] </a>";
}
?>