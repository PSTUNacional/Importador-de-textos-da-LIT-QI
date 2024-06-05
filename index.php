<?php

// É preciso estar de acordo com o servidor
require('../wp-load.php');

/*
*
* Isso é para forcar o login no WordPress do site,
* já que o importador deve ficar disponível apenas
* aos admins do WP que receberá o novo texto.
*
*/

if (!is_user_logged_in()) {
  header('Location: https://www.pstu.org.br/wp-login.php?redirect_to=https%3A%2F%2Fwww.pstu.org.br%2Fimportador');;
} else {
}
?>
<html>
  <head>
  <title>Importador de textos da LIT-QI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    body {
      margin: 0px;
      font-family: sans-serif;
    }

    .cabecalho {
      width: 100%;
      padding-top: 128px;
      padding-bottom: 128px;
      background: rgb(33, 33, 34);
      background: linear-gradient(0deg, rgba(33, 33, 34, 1) 0%, rgba(66, 66, 69, 1) 100%);
      text-align: center;
      color: white;
    }

    .cabecalho h1 {
      text-transform: uppercase;
      margin-bottom: 32px;
    }

    .cabecalho p {
      margin-bottom: 32px;
    }

    input {
      width: 100%;
      height: 48px;
      border-radius: 24px;
      border: 0px;
      padding: 16px;
      box-sizing: border-box;
      outline: none;
    }

    button {
      border-radius: 24px;
      margin: 24px auto;
      width: auto;
      background-color: #cc0000;
      border: 0px;
      padding: 16px 32px;
      color: white;
      font-weight: 900;
      font-size: 16px;
      text-transform: uppercase;
    }

    button:hover {
      background-color: #ee0000;
    }

    .status {
      width: auto;
      height: 90px;
      position: relative;
      cursor: pointer;
      margin: 8px;
    }

    .status input,
    .editoria input {
      position: absolute;
      display: none;
    }

    .caixa {
      padding: 16px;
      position: absolute;
      vertical-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-itens: center;
      border-radius: 4px;
      box-sizing: border-box;
      box-shadow: 0px 0px 0px 0px rgba(255, 255, 255, 0.5);
      transition: all 0.3s;
      border: 1px solid #999;
      width: 100%;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .caixa h3 {
      margin-top: 8px;
      margin-bottom: 0px;
    }

    .icon_container {
      border: 1px solid #999;
      margin: auto;
      width: 24px;
      height: 24px;
      border-radius: 50%;
    }

    .icon_container i {
      opacity: 0;
      color: #cc0000;
      transition: all 0.6s;
    }

    .status:hover .caixa,
    .editoria:hover .pill {
      box-shadow: 0px 0px 0px 4px rgba(255, 255, 255, 0.5);
    }

    .status input:checked+.caixa,
    .editoria input:checked+.pill {
      background-color: rgba(255, 255, 255, 1);
    }

    .status input:checked+.caixa i {
      color: #cc0000;
      opacity: 1;
      transition: all 0.6s;
    }

    .status input:checked+.caixa .icon_container {
      border: 1px solid transparent;
      transition: all 0.6s;
    }

    .status input:checked+.caixa h3,
    .editoria input:checked+.pill h4 {
      color: #cc0000;
      transition: all 0.6s;
    }

    .pill {
      padding: 8px 16px;
      position: absolute;
      vertical-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-itens: center;
      border-radius: 32px;
      box-sizing: border-box;
      box-shadow: 0px 0px 0px 0px rgba(255, 255, 255, 0.5);
      transition: all 0.3s;
      border: 1px solid #999;
      width: 100%;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .pill h4 {
      margin: 0;
    }

    .editoria {
      width: auto;
      height: 36px;
      position: relative;
      cursor: pointer;
      margin: 8px;
    }
  </style>
</head>

<body>
  <div class="cabecalho">
    <div style="max-width:480px; margin:auto; display:flex; flex-direction:column; align-content:center; justify-content:center; padding:24px;"> <img src="importador.png" style="max-width:280px; height:auto; opacity:0.5; margin-bottom:24px; margin-left:auto; margin-right:auto;" />
      <h1>Importador de textos da LIT-QI</h1>

      <!-- A ação do Formulário é disparada para IMPORTADOR.PHP -->
      <form action="importador.php" method="POST">
        <input type="text" name="furl" />
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));margin-top:16px">
          <label class="status" onclick=""><input type="radio" name="fstatus" value="draft" checked="checked" /><span class="caixa"><span class="icon_container"><i class="material-icons">check_circle</i></span>
              <h3>Rascunho</h3>
            </span>
          </label>
          <label class="status" onclick=""><input type="radio" name="fstatus" value="publish" /><span class="caixa"><span class="icon_container"><i class="material-icons">check_circle</i></span>
              <h3>Publicado</h3>
            </span>
          </label>
        </div>
        <h3 style="border-bottom:1px solid #999; padding-bottom:8px;">Escolha uma Editoria</h3>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));margin-top:8px">
          <label class="editoria" onclick=""><input type="radio" name="feditoria" value="internacional" checked="checked" /><span class="pill">
              <h4>Internacional</h4>
            </span>
          </label>
          <label class="editoria" onclick=""><input type="radio" name="feditoria" value="socialismo" /><span class="pill">
              <h4>Socialismo</h4>
            </span>
          </label>
          <label class="editoria" onclick=""><input type="radio" name="feditoria" value="debate" /><span class="pill">
              <h4>Debate</h4>
            </span>
          </label>
        </div>
        <button id="importa" type="submit" onclick="load();"><i class="material-icons" style="display:inline-flex; vertical-align:middle;line-height:16px">import_export</i> Importar!</button>
        <img id="loading" src="loading.gif" style="margin-top:24px;margin-bottom:24px;width:auto;height:50px;display:none;" />
      </form>
      <p style="line-height:1.5em;">Cole o link da matéria do site da LIT-QI e importe para o site do partido. Você será encaminhado para a página de edição da matéria. <b><u>É importante revisá-la e checar as palavras-chave.</u></b></p>
    </div>
  </div>
  <script>
    function load() {
      document.getElementById("importa").style.display = "none";
      document.getElementById("loading").style.display = "inline-flex";
    };
  </script>
</body>

</html>