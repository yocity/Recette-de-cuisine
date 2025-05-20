<?php
//Yocoli Konan Jean Epiphane 1A TP10

if ($idP!='') {$action="index.php?action={$cible}&idP={$idP}";}
else {$action="index.php?action={$cible}";}
$corps =<<<EOT
  <form method="post" action="{$action}" name="form_user" >
  <table>
      <tr>
        <td><label> Nom</label></td>
        <td><input type="text" name="nom" size="" value="{$nom}"></td>
        <td class="w3-text-red">{$erreur["nom"]}</td>
      </tr>

      <tr>
      <td><label> Date de Naissance</label></td>
      <td><input type="text" name="dateN" size=""  placeholder="aaaa-mm-jj" value="{$dateN}"></td>
      <td class="w3-text-red">{$erreur["dateN"]}</td>
      </tr>
      <tr>
        <td><input type="submit" name="user_valider" value="Valider" size="" ></td>
        <td></td>
        <td></td>
      </tr>
    </table>
  </form>
EOT;
?>
