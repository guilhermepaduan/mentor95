<?php
  include './controller/userController.php';
  $user = new UserController;



  /**
   * Formulário
   */
  $form = '
  <h1>Escolha uma opção para se cadastrar:</h1>

  <form id="formRegister" method="POST" action="">
    <div class="tag-cloud form-step active" id="tag-cloud-register">
      <ul>
        <li>
          <input type="radio" name="target" id="1" value="Quero conhecer o mundo da programação" ' . (isset($_POST) && $_POST['target'] == 'Quero conhecer o mundo da programação' ? 'checked': '') . '/>
          <label for="1" class="active"><span></span>Quero conhecer o mundo da programação</label>
        </li>
        <li>
          <input type="radio" name="target" id="2" value="Quero aprender uma nova linguagem" ' . (isset($_POST) && $_POST['target'] == 'Quero aprender uma nova linguagem' ? 'checked': '') . '/>
          <label for="2"><span></span>Quero aprender uma nova linguagem</label>
        </li>
        <li>
          <input type="radio" name="target" id="4" value="Quero ter orientação para qual lado seguir" ' . (isset($_POST) && $_POST['target'] == 'Quero ter orientação para qual lado seguir' ? 'checked': '') . '/>
          <label for="4"><span></span>Quero ter orientação para qual lado seguir</label>
        </li>
        <li>
          <input type="radio" name="target" id="5" value="Quero ter orientação em qual tecnologia usar" ' . (isset($_POST) && $_POST['target'] == 'Quero ter orientação em qual tecnologia usar' ? 'checked': '') . '/>
          <label for="5"><span></span>Quero ter orientação em qual tecnologia usar</label>
        </li>
        <li>
          <input type="radio" name="target" id="6" value="Quero mudar de área dentro da programação" ' . (isset($_POST) && $_POST['target'] == 'Quero mudar de área dentro da programação' ? 'checked': '') . '/>
          <label for="6"><span></span>Quero mudar de área dentro da programação</label>
        </li>
      </ul>

      <div class="user-btn">
        <div>
          <input type="checkbox" name="mentor" id="mentor" value="YES" ' . (isset($_POST) && $_POST['mentor'] == 'YES' ? 'checked': '') . '/>
          <label for="mentor"><span></span>Estou aqui para ajudar</label>
        </div>
        <a href="#" id="form-next-btn" ' . (empty($_POST) ? 'class="disabled"' : '') . '>Avançar</a>
      </div>
    </div>

    <div class="user-infos form-step" id="user-infos-register">
      <div class="form-group">
        <label for="name">Nome e sobrenome</label>
        <input type="text" id="name" name="name" required minlength="2" class="form-control" value="' . (isset($_POST) && $_POST['name'] != '' ? $_POST['name']: '') . '">
      </div>

      <div class="form-group">
        <label for="age">Idade:</label>
        <input type="text" id="age" name="age" required maxlength="2" class="form-control" value="' . (isset($_POST) && $_POST['age'] != '' ? $_POST['age']: '') . '">
      </div>

      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required class="form-control" value="' . (isset($_POST) && $_POST['email'] != '' ? $_POST['email']: '') . '">
      </div>

      <div class="form-group">
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required minlength="2" class="form-control">
      </div>

      <div class="user-btn">
        <a href="#" id="form-prev-btn">voltar</a>
        <button type="submit">Cadastrar</button>
      </div>
    </div>
  </form>
  ';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Mentor 95</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="app/dist/css/main.min.css">
</head>
<body>

  <header id="header">
    <div class="alignment">
      <figure class="logotipo">
        <a href="/">
          <img src="app/dist/images/logomentor.png" alt="">
        </a>
      </figure>

      <nav class="menu">
        <ul>
          <li><a href="/login">Entrar</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main id="content">
    <div class="alignment alignment-signin-section">
      <section class="signin-section">

        <?php
          if (!empty($_POST) && isset($_POST)) {
            $response = $user->insert($_POST);

            if (isset($response['success']) && $response['success']) {
              echo '
              <div class="alert alert-success" role="alert">
                Seu cadastro foi realizado com sucesso.<br />
                Em breve enviaremos um e-mail com o acesso para o lançamento da plataforma Mentor95.
              </div>
              ';
            }

            if (isset($response['success']) && $response['success'] == false) {
              $script = '
              <script>
                $(".form-step").removeClass("active");
                $(".user-infos").addClass("active");
              </script>';

              echo '
              <div class="alert alert-danger" role="alert">
                <strong>O(s) seguinte(s) erro(s) ocorreram(ou):</strong>';

                echo '<ul>';
                foreach ($response['messages'] as $value) {
                  echo '<li>&bull; '. $value .'</li>';
                }
                echo '</ul>';

              echo '
              </div>
              ';

              echo $form;
            }
          }

          else {
            echo $form;
          }

        ?>

      </section>
    </div>
  </main>

  <footer id="footer">
    <div class="alignment">
      &copy; mentor95
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script><!-- Inclusão do Plugin jQuery Validation-->
  <script src="http://jqueryvalidation.org/files/dist/jquery.validate.js"></script>
  <script src='app/dist/js/main.min.js'></script>
  <?=$script; ?>
</body>
</html>
