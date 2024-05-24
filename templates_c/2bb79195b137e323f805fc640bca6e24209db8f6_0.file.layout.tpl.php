<?php
/* Smarty version 4.3.0, created on 2023-05-22 22:27:09
  from '/home/WebDiP_01/kglazer/projekt/templates/layout.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_646bd01dd7f9f4_97155904',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2bb79195b137e323f805fc640bca6e24209db8f6' => 
    array (
      0 => '/home/WebDiP_01/kglazer/projekt/templates/layout.tpl',
      1 => 1684787229,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_646bd01dd7f9f4_97155904 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="hr">
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['naziv']->value;?>
</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Karla Glazer">
        <meta name="keywords" content="nekretnine">
        <meta name="description" content="22.5.2023.">
    </head>
    <body>
        <nav>
            <ul id="navigation">
                <li><a href="index.php">Početna</a></li>
                <li><a href="popis.php">Popis nekretnina</a></li>
            </ul>
        </nav>
    </body>
<?php }
}
