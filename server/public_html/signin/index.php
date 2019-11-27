<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Mentor 95</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../app/dist/css/main.min.css">
</head>
<body>

  <header id="header">
    <div class="alignment">
      <figure class="logotipo">
        <img src="../app/dist/images/logomentor.png" alt="">
      </figure>
      <nav class="menu">
        <ul>
          <li><a href="#">Entrar</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main id="content">
    <div class="alignment alignment-signin-section">
      <section class="signin-section">

        <h1>Escolha uma opção para se cadastrar:</h1>

        <form id="formRegister" method="POST" action="signin.php">
          <div class="tag-cloud form-step active" id="tag-cloud-register">
            <ul>
              <li>
                <input type="radio" name="target" id="1" value=""/>
                <label for="1" class="active"><span></span>Sou novo aqui</label>
              </li>
              <li>
                <input type="radio" name="target" id="2" value=""/>
                <label for="2"><span></span>Quero aprender uma nova linguagem</label>
              </li>
              <li>
                <input type="radio" name="target" id="3" value=""/>
                <label for="3"><span></span>Quero aprender a programar</label>
              </li>
              <li>
                <input type="radio" name="target" id="4" value=""/>
                <label for="4"><span></span>Quero ter orientação para qual lado seguir</label>
              </li>
              <li>
                <input type="radio" name="target" id="5" value=""/>
                <label for="5"><span></span>Quero ter orientação em qual tecnologia usar</label>
              </li>
              <li>
                <input type="radio" name="target" id="6" value=""/>
                <label for="6"><span></span>Quero ter orientação em alguma tecnologia</label>
              </li>
            </ul>

            <div class="user-btn">
              <div>
                <input type="checkbox" name="mantory" id="mantory" value=""/>
                <label for="mantory"><span></span>Estou aqui para ajudar</label>
              </div>
              <a href="#" id="form-next-btn" class="disabled">Avançar</a>
            </div>
          </div>

          <div class="user-infos form-step" id="user-infos-register">
            <div class="form-group">
              <label for="cnome">Nome</label>
              <input type="text" id="cnome" name="nome" required minlength="2" class="form-control">
            </div>

            <div class="form-group">
              <label for="csobrenome">Sobrenome</label>
              <input type="text" id="csobrenome" name="sobrenome" required minlength="2" class="form-control">
            </div>

            <div class="form-group">
              <label for="cemail">E-mail</label>
              <input type="email" id="cemail" name="email" required class="form-control">
            </div>

            <div class="form-group">
              <label for="csenha">Senha</label>
              <input type="password" id="csenha" required minlength="2" class="form-control">
            </div>

            <div class="user-btn">
              <a href="#" id="form-prev-btn">voltar</a>
              <button type="submit">Cadastrar</button>
            </div>
          </div>
        </form>

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
  <script src='../app/dist/js/main.min.js'></script>
</body>
</html>
