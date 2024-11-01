<?php

$showErrorCaptcha = false;
$showAPIMessage = false;
if (isset($_POST['captcha']))
{
    $captcha = trim(strtolower($_POST['captcha']));
    if (empty($captcha) || empty($_SESSION['captcha']) || $captcha != $_SESSION['captcha'])
    {
        $showErrorCaptcha = true;
    }
    else
    {
        $request = new Wp_Http;
        $url = "https://app.webcourier.com.br/api/wordpress/save";
        $headers = array('Accept-Language' => '*');
        $body = array(
            'dominio' => network_site_url('/'),
            'nome_destinatario' => $_POST['nome'],
            'email' => $_POST['email'],
        );
        $response = $request->request($url, array('method' => 'POST', 'body' => $body, 'headers' => $headers));
        $result = json_decode($response['body']);
        if ($response['response']['message'] == 'OK') {
            $showAPIMessage = true;
            $status = $result->message;
        }
    }
    unset($_POST['captcha']);
}

$root = plugins_url() . "/webcourier/cool-php-captcha-master/captcha.php?";
$url = $root;
?>
<div>
    <div id="lbmessage"></div>
    <div id="lbaguarde"></div>
    <form action="" method="post">
        <?php if($showAPIMessage): ?> 
            <div>
                <h5><?php echo $status ?></h5>
            </div>
        <?php endif; ?>
        <h5>Cadastre-se em nossa newsletter</h5>
        <div style="display:block">
        <label for="nome">Nome:</label>
        <input name="nome" type="text" id="nome" value=""/>
        </div>
        <div style="display:block; margin-top: 10px">
        <label for="email">E-mail:</label>
        <input name="email" type="text" id="email" value=""/>
        </div>
        <?php if ($showErrorCaptcha): ?>
            <div id="result" style="color: red;">
                <h5>Captcha inválido</h5>
            </div>
        <?php endif; ?>
        <img src="<?= $url ?>" id="captcha" /><br/>
        <u><p href="" style="cursor: pointer;"onclick="            
                document.getElementById('captcha').src = '<?= $url ?>' + Math.random();
              "id="change-image">Não visível ? Mude o texto.</p></u>
        <input type="text" name="captcha" placeholder="Digite o captcha" id="captcha-form" autocomplete="off">
        <button name="btenviar" type="submit" id="btnWcSubmit">Cadastrar</button><br/><br/>
    </form>
</div>