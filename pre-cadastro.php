<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . '/vendor/autoload.php';

    $sucesso = false;
    $erro = '';

    $email_destino = 'contato@bipeipostei.com.br';

    $smtp_usuario = 'contato@bipeipostei.com.br';
    $smtp_senha = '212223@Bipei!';

    $nome = '';
    $empresa = '';
    $email = '';
    $whatsapp = '';
    $pedidos = '';
    $observacoes = '';
    $marketplaces = [];

    /*
    |--------------------------------------------------------------------------
    | Marketplaces permitidos
    |--------------------------------------------------------------------------
    | Fica fora do POST porque também é utilizado pelo HTML do formulário.
    */

    $marketplaces_permitidos = [
        'Mercado Livre',
        'Mercado Livre Flex',
        'Shopee',
        'Shopee Entrega Direta',
        'Shein',
        'Magalu',
        'Americanas',
        'TikTok Shop',
        'Amazon',
        'Temu',
        'Site próprio',
        'Outro'
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['website'])) {
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $empresa = trim($_POST['empresa'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $pedidos = trim($_POST['pedidos'] ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');

        $marketplaces = $_POST['marketplaces'] ?? [];

        if (!is_array($marketplaces)) {
            $marketplaces = [];
        }

        /*
        |--------------------------------------------------------------------------
        | Filtra somente marketplaces permitidos
        |--------------------------------------------------------------------------
        */

        $marketplaces = array_values(
            array_intersect(
                $marketplaces,
                $marketplaces_permitidos
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Validações
        |--------------------------------------------------------------------------
        */

        if ($nome === '') {

            $erro = 'Informe seu nome.';

        } elseif ($empresa === '') {

            $erro = 'Informe o nome da empresa.';

        } elseif ($email === '') {

            $erro = 'Informe seu e-mail.';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $erro = 'Informe um e-mail válido.';

        } elseif ($whatsapp === '') {

            $erro = 'Informe seu WhatsApp.';

        } elseif ($pedidos === '') {

            $erro = 'Informe a quantidade aproximada de pedidos.';

        } elseif (empty($marketplaces)) {

            $erro = 'Selecione pelo menos um marketplace.';
        }

        /*
        |--------------------------------------------------------------------------
        | Envio do e-mail
        |--------------------------------------------------------------------------
        */

        if ($erro === '') {

            try {

                $mail = new PHPMailer(true);

                $mail->isSMTP();

                $mail->Host = 'smtp.hostinger.com';

                $mail->SMTPAuth = true;

                $mail->Username = $smtp_usuario;

                $mail->Password = $smtp_senha;

                $mail->SMTPSecure =
                    PHPMailer::ENCRYPTION_SMTPS;

                $mail->Port = 465;

                $mail->CharSet = 'UTF-8';

                /*
                |--------------------------------------------------------------------------
                | Remetente
                |--------------------------------------------------------------------------
                */

                $mail->setFrom(
                    $smtp_usuario,
                    'BipeiPostei'
                );

                /*
                |--------------------------------------------------------------------------
                | Destinatário
                |--------------------------------------------------------------------------
                */

                $mail->addAddress(
                    $email_destino,
                    'BipeiPostei'
                );

                /*
                |--------------------------------------------------------------------------
                | Responder para o interessado
                |--------------------------------------------------------------------------
                */

                $mail->addReplyTo(
                    $email,
                    $nome
                );

                /*
                |--------------------------------------------------------------------------
                | Dados
                |--------------------------------------------------------------------------
                */

                $lista_marketplaces = implode(
                    ', ',
                    $marketplaces
                );

                $data_cadastro = date(
                    'd/m/Y H:i:s'
                );

                /*
                |--------------------------------------------------------------------------
                | E-mail HTML
                |--------------------------------------------------------------------------
                */

                $mail->isHTML(true);

                $mail->Subject =
                    'Novo pré-cadastro - BipeiPostei';

                $mail->Body = '

                <div style="
                    font-family: Arial, sans-serif;
                    max-width: 700px;
                    margin: 0 auto;
                    color: #333;
                ">

                    <div style="
                        background: #f5f7fa;
                        padding: 25px;
                        border-radius: 12px;
                    ">

                        <h2 style="margin-top: 0;">
                            Novo pré-cadastro
                        </h2>

                        <p>
                            Um novo interessado enviou o formulário
                            do BipeiPostei.
                        </p>

                    </div>

                    <br>

                    <table
                        width="100%"
                        cellpadding="10"
                        cellspacing="0"
                        style="border-collapse: collapse;"
                    >

                        <tr>

                            <td style="
                                border-bottom: 1px solid #ddd;
                                font-weight: bold;
                            ">
                                Data
                            </td>

                            <td style="
                                border-bottom: 1px solid #ddd;
                            ">
                                ' .
                                htmlspecialchars(
                                    $data_cadastro,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) .
                            '
                            </td>

                        </tr>

                        <tr>

                            <td style="
                                border-bottom: 1px solid #ddd;
                                font-weight: bold;
                            ">
                                Nome
                            </td>

                            <td style="
                                border-bottom: 1px solid #ddd;
                            ">
                                ' .
                                htmlspecialchars(
                                    $nome,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) .
                            '
                            </td>

                        </tr>

                        <tr>

                            <td style="
                                border-bottom: 1px solid #ddd;
                                font-weight: bold;
                            ">
                                Empresa
                            </td>

                            <td style="
                                border-bottom: 1px solid #ddd;
                            ">
                                ' .
                                htmlspecialchars(
                                    $empresa,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) .
                            '
                            </td>

                        </tr>

                        <tr>

                            <td style="
                                border-bottom: 1px solid #ddd;
                                font-weight: bold;
                            ">
                                E-mail
                            </td>

                            <td style="
                                border-bottom: 1px solid #ddd;
                            ">
                                ' .
                                htmlspecialchars(
                                    $email,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) .
                            '
                            </td>

                        </tr>

                        <tr>

                            <td style="
                                border-bottom: 1px solid #ddd;
                                font-weight: bold;
                            ">
                                WhatsApp
                            </td>

                            <td style="
                                border-bottom: 1px solid #ddd;
                            ">
                                ' .
                                htmlspecialchars(
                                    $whatsapp,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) .
                            '
                            </td>

                        </tr>

                        <tr>

                            <td style="
                                border-bottom: 1px solid #ddd;
                                font-weight: bold;
                            ">
                                Pedidos por dia
                            </td>

                            <td style="
                                border-bottom: 1px solid #ddd;
                            ">
                                ' .
                                htmlspecialchars(
                                    $pedidos,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) .
                            '
                            </td>

                        </tr>

                        <tr>

                            <td style="
                                border-bottom: 1px solid #ddd;
                                font-weight: bold;
                            ">
                                Marketplaces
                            </td>

                            <td style="
                                border-bottom: 1px solid #ddd;
                            ">
                                ' .
                                htmlspecialchars(
                                    $lista_marketplaces,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) .
                            '
                            </td>

                        </tr>

                        <tr>

                            <td style="
                                vertical-align: top;
                                font-weight: bold;
                            ">
                                Observações
                            </td>

                            <td>
                                ' .
                                nl2br(
                                    htmlspecialchars(
                                        $observacoes !== ''
                                            ? $observacoes
                                            : 'Nenhuma observação informada.',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                ) .
                                '
                            </td>

                        </tr>

                    </table>

                    <br>

                    <p style="
                        color: #888;
                        font-size: 12px;
                    ">
                        Enviado através do site BipeiPostei.
                    </p>

                </div>';

                /*
                |--------------------------------------------------------------------------
                | Versão texto
                |--------------------------------------------------------------------------
                */

                $mail->AltBody =
                    "NOVO PRÉ-CADASTRO - BIPEIPOSTEI\n\n" .
                    "Data: $data_cadastro\n" .
                    "Nome: $nome\n" .
                    "Empresa: $empresa\n" .
                    "E-mail: $email\n" .
                    "WhatsApp: $whatsapp\n" .
                    "Pedidos por dia: $pedidos\n" .
                    "Marketplaces: $lista_marketplaces\n\n" .
                    "Observações:\n" .
                    ($observacoes ?: 'Nenhuma.');

                /*
                |--------------------------------------------------------------------------
                | Envia
                |--------------------------------------------------------------------------
                */

                $mail->send();

                $sucesso = true;

                $nome = '';
                $empresa = '';
                $email = '';
                $whatsapp = '';
                $pedidos = '';
                $observacoes = '';
                $marketplaces = [];

            } catch (Exception $e) {

                $erro =
                    'Não foi possível enviar seu pré-cadastro. ' .
                    'Tente novamente em alguns instantes.';

                error_log(
                    'Erro SMTP BipeiPostei: ' .
                    $mail->ErrorInfo
                );
            }
        }
    }
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pré-cadastro | BipeiPostei</title>

    <meta
        name="description"
        content="Faça seu pré-cadastro no BipeiPostei e conheça uma forma mais prática de controlar seus códigos de rastreio."
    >

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="css/principal.css">
    <link rel="stylesheet" href="css/pre-cadastro.css">
</head>

<body>

<!-- ======================================================
     NAVBAR
====================================================== -->

    <nav class="navbar navbar-expand-md navbar-dark bg-purple fixed-top p-2 mb-3">
      <div class="container">
          <a href="/" class="d-block m-auto"><img src="images/logo.svg"></a>
      </div>
    </nav>


<!-- ======================================================
     FORMULÁRIO
====================================================== -->

<div class="cadastro-wrapper mt-5">

    <div class="cadastro-card">

        <div class="cadastro-header">

            <h1>Experimente o BipeiPostei</h1>

            <p>
                Faça seu pré-cadastro e entre em contato conosco
                para conhecer nossa solução.
            </p>

        </div>


        <div class="cadastro-body">


            <!-- ==================================================
                 MENSAGEM DE SUCESSO
            ================================================== -->

            <?php if ($sucesso): ?>

                <div
                    class="alert alert-success text-center"
                    role="alert"
                >

                    <h4 class="alert-heading">
                        Pré-cadastro enviado! 🎉
                    </h4>

                    <p class="mb-0">
                        Recebemos seus dados com sucesso.
                        Em breve entraremos em contato.
                    </p>

                </div>


            <!-- ==================================================
                 FORMULÁRIO
            ================================================== -->

            <?php else: ?>


                <?php if ($erro !== ''): ?>

                    <div
                        class="alert alert-danger"
                        role="alert"
                    >
                        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                    </div>

                <?php endif; ?>


                <form
                    method="POST"
                    action=""
                    autocomplete="on"
                >

                    <!-- ==========================================
                         HONEYPOT
                    =========================================== -->

                    <div class="campo-oculto">

                        <label for="website">
                            Website
                        </label>

                        <input
                            type="text"
                            name="website"
                            id="website"
                            tabindex="-1"
                            autocomplete="off"
                        >

                    </div>


                    <!-- ==========================================
                         NOME + EMPRESA
                    =========================================== -->

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label
                                for="nome"
                                class="form-label"
                            >
                                Seu nome *
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nome"
                                name="nome"
                                value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Digite seu nome"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label
                                for="empresa"
                                class="form-label"
                            >
                                Nome da empresa *
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="empresa"
                                name="empresa"
                                value="<?= htmlspecialchars($empresa, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Nome da sua empresa"
                                required
                            >

                        </div>

                    </div>


                    <!-- ==========================================
                         E-MAIL + WHATSAPP
                    =========================================== -->

                    <div class="row g-3 mt-1">

                        <div class="col-md-6">

                            <label
                                for="email"
                                class="form-label"
                            >
                                E-mail *
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="seu@email.com"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label
                                for="whatsapp"
                                class="form-label"
                            >
                                WhatsApp *
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="whatsapp"
                                name="whatsapp"
                                value="<?= htmlspecialchars($whatsapp, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="(00) 00000-0000"
                                required
                            >

                        </div>

                    </div>


                    <!-- ==========================================
                         PEDIDOS
                    =========================================== -->

                    <div class="mt-4">

                        <label
                            for="pedidos"
                            class="form-label"
                        >
                            Quantidade aproximada de pedidos por dia *
                        </label>

                        <select
                            class="form-select"
                            id="pedidos"
                            name="pedidos"
                            required
                        >

                            <option value="">
                                Selecione uma faixa
                            </option>

                            <option
                                value="Até 20"
                                <?= $pedidos === 'Até 20' ? 'selected' : '' ?>
                            >
                                Até 20 pedidos
                            </option>

                            <option
                                value="21 a 50"
                                <?= $pedidos === '21 a 50' ? 'selected' : '' ?>
                            >
                                21 a 50 pedidos
                            </option>

                            <option
                                value="51 a 100"
                                <?= $pedidos === '51 a 100' ? 'selected' : '' ?>
                            >
                                51 a 100 pedidos
                            </option>

                            <option
                                value="101 a 300"
                                <?= $pedidos === '101 a 300' ? 'selected' : '' ?>
                            >
                                101 a 300 pedidos
                            </option>

                            <option
                                value="301 a 500"
                                <?= $pedidos === '301 a 500' ? 'selected' : '' ?>
                            >
                                301 a 500 pedidos
                            </option>

                            <option
                                value="Mais de 500"
                                <?= $pedidos === 'Mais de 500' ? 'selected' : '' ?>
                            >
                                Mais de 500 pedidos
                            </option>

                        </select>

                    </div>


                    <!-- ==========================================
                         MARKETPLACES
                    =========================================== -->

                    <div class="mt-4">

                        <label class="form-label">
                            Quais marketplaces você utiliza? *
                        </label>

                        <div class="marketplace-box">

                            <div class="row">

                                <?php foreach ($marketplaces_permitidos as $index => $marketplace): ?>

                                    <div class="col-md-6">

                                        <div class="form-check">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="marketplaces[]"
                                                value="<?= htmlspecialchars($marketplace, ENT_QUOTES, 'UTF-8') ?>"
                                                id="marketplace<?= $index ?>"
                                                <?= in_array($marketplace, $marketplaces) ? 'checked' : '' ?>
                                            >

                                            <label
                                                class="form-check-label"
                                                for="marketplace<?= $index ?>"
                                            >
                                                <?= htmlspecialchars($marketplace, ENT_QUOTES, 'UTF-8') ?>
                                            </label>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    </div>


                    <!-- ==========================================
                         OBSERVAÇÕES
                    =========================================== -->

                    <div class="mt-4">

                        <label
                            for="observacoes"
                            class="form-label"
                        >
                            Como podemos ajudar?
                        </label>

                        <textarea
                            class="form-control"
                            id="observacoes"
                            name="observacoes"
                            rows="4"
                            maxlength="1000"
                            placeholder="Conte um pouco sobre sua operação ou deixe uma mensagem..."
                        ><?= htmlspecialchars($observacoes, ENT_QUOTES, 'UTF-8') ?></textarea>

                    </div>


                    <!-- ==========================================
                         BOTÃO
                    =========================================== -->

                    <div class="mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary btn-cadastro"
                        >
                            🚀 Quero conhecer o BipeiPostei
                        </button>

                    </div>


                    <div class="text-center mt-3">

                        <small class="text-muted">
                            Seus dados serão utilizados apenas para
                            entrarmos em contato com você.
                        </small>

                    </div>


                </form>

            <?php endif; ?>

        </div>

    </div>

</div>

<!-- =====================================================
 FOOTER
===================================================== -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="footer-brand">
          <img src="https://bipeipostei.com.br/images/logo-bipeipostei.svg">
        </div>
        <p class="mt-1"> Controle seus códigos. Agilize sua expedição. </p>
      </div>
    </div>
    <div class="copyright text-center"> © 2020 / <?= date('Y') ?> BipeiPostei. Todos os direitos reservados. </div>
  </div>
</footer>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- ======================================================
     MÁSCARA WHATSAPP
====================================================== -->

<script>

document.getElementById('whatsapp').addEventListener('input', function (e) {

    let valor = e.target.value.replace(/\D/g, '');

    if (valor.length > 11) {
        valor = valor.substring(0, 11);
    }

    if (valor.length <= 10) {

        valor = valor.replace(
            /^(\d{2})(\d)/,
            '($1) $2'
        );

        valor = valor.replace(
            /(\d{4})(\d)/,
            '$1-$2'
        );

    } else {

        valor = valor.replace(
            /^(\d{2})(\d)/,
            '($1) $2'
        );

        valor = valor.replace(
            /(\d{5})(\d)/,
            '$1-$2'
        );

    }

    e.target.value = valor;

});

</script>

</body>

</html>
