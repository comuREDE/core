<?php
require 'init.php';

$email = $_POST['email'];
$cep = ($_POST['cep']) ? $_POST['cep'] : '31170220';
$tipo = $_POST['tipo'];

$ano = date('Y');

switch($_POST["mes"] == "Agosto"){
    case "Agosto":
        $data1 = $ano."-08-01";
        $data2 = $ano."-08-31";
        break;

    default:
        echo 'Default';
        break;
}

$q = "SELECT id, DATE(dia_hora) AS data, TIME(dia_hora) AS hora, estado, cep, sensor FROM sensores_agua WHERE DATE(dia_hora)
          BETWEEN '".$data1."' AND '".$data2."'";

$res = (new BD())->query($q);


//$q = "SELECT DATE(dia_hora), estado FROM sensores_agua WHERE DATE(dia_hora) BETWEEN '" . $data1 . "' AND '" . $data2 . "'";
//echo $q;
//$res = (new BD())->query($q);
//var_dump($res);

function filtroRelatAgua()
{
    $ano = date('Y');

    switch($_POST["mes"] == "Agosto"){
        case "Agosto":
            $data1 = $ano."-08-01";
            $data2 = $ano."-08-31";
            break;

        default:
            echo 'Default';
            break;
    }

    $q = "SELECT id, DATE(dia_hora) AS data, TIME(dia_hora) AS hora, estado, cep, sensor FROM sensores_agua WHERE DATE(dia_hora)
          BETWEEN '".$data1."' AND '".$data2."'";

  $res = (new BD())->query($q);

  $loop = false;
  $regs = [];
  //$flags = [];
  $count = count($res);
  if ($count > 2) {
    for ($i = 0; $i < $count - 2; $i++) {
      $id_davez = $res[$i]['id'];
      $atual_estado = $res[$i]['estado'];
      $proximo_estado = $res[$i + 1]['estado'];
      $proximo_prox_estado = $res[$i + 2]['estado'];
      if ($atual_estado === 'D') {
        $loop = false;
      }

      $atual_cep = $res[$i]['cep'];
      $proximo_cep = $res[$i + 1]['cep'];
      $proximo_prox_cep = $res[$i + 2]['cep'];

      $atual_sensor = $res[$i]['sensor'];
      $proximo_sensor = $res[$i + 1]['sensor'];
      $proximo_prox_sensor = $res[$i + 2]['sensor'];

      $cond1 = ($atual_estado === 'L' && $proximo_estado === 'L' && $proximo_prox_estado === 'L');
      $cond2 = ($atual_cep == $proximo_cep) && ($atual_cep == $proximo_prox_cep);
      $cond3 = ($atual_sensor == $proximo_sensor) && ($atual_sensor == $proximo_prox_sensor);

      if ($cond1 && $cond2 && $cond3) {
        if (!$loop) {
          //$regs armazena todos os registros que são seguidos de 3 "L"
          //var_dump($regs[] = $res[$i]);
          $regs[] = $res[$i];
        }
        $loop = true;
      } else {
        #echo "<br>NAAAOOO entrou ". implode(" - ",$res[$i]);
      }
      //$flags[] = $id_davez;
    }

    // #aqui neste caso vamos dar um tratamento para os 2 ultimos
    // $penultimo = $count - 1;
    // $ultimo = $count - 2;
    // $flags[] = $res[$ultimo]['id'];
    // $flags[] = $res[$penultimo]['id'];

    // atualizaFlags($flags, 'T', 'sensores_agua');

      return $regs;

  } else {
    echo "Total Menor que 3 registros";
  }
}

//chama a funcao do filtro de agua e guarda os valores do array de retorno $regs[] em $relatorio
$relatorio = filtroRelatAgua();
$totalItens = count($relatorio);
$data_str_1 = $relatorio[0]["data"];
$data_str_2 = $relatorio[$totalItens-1]["data"];
print_r($data_str_1);
print_r($data_str_2);

if (montaEnviaEmail($email, $data_str_1, $data_str_2, $cep, $tipo)) {
    //echo "<h1>Email enviado com sucesso</h1>";
} else {
    echo "<h1>Erro ao enviar email</h1>";
}


if ($_POST) {
    extract($_POST);
}

function getCepByEmail(string $email)
{
  $res = (new Model())->setTable('cadastros')->getOneBy('email', $email);
  echo $res['cep'];
}


function montaEnviaEmail(string $email, string $data1, string $data2, string $cep, string $tipo = 'A')
{

  $tipo_diferencial = $tipo == 'A' ? "Água" : "Luz";

  $linha = $tipo == 'A' ? "No período selecionado, nestes dias caiu água:<br><br>" : "No período selecionado, nestes dias faltou luz:<br><br>";
  $header = "Relatório comuREDE ($tipo_diferencial) <br>";
  $texto = montaRelatorioMensal($data1, $data2, $cep, $tipo);
  $footer = "<br>Tamo junto!<br>www.comurede.net";
  $msg = $header . $linha . $texto . $footer;
  $assunto = "Relatório comuREDE ($tipo_diferencial)";
  $nome = "Usuário comuREDE";
  return enviaEmail(GMAIL, "comuREDE", EMAIL_S, $email, $nome, $assunto, $msg);
}


function montaRelatorioMensal($data1, $data2, $cep, $tipo = 'A')
{

  $q = "SELECT DATE(dia_hora), estado FROM 'sensores_agua' WHERE DATE(dia_hora) BETWEEN '$data1' AND '$data2'";
  #echo $q;
  $res = (new BD())->query($q);
  $dias = array_column($res, "dia_hora");



  $dias_sem = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB',];

  $msg = "";
  foreach ($dias as $dia) {
    $dia_num = date('w', strtotime($dia));
    $msg .= $dias_sem[$dia_num] . " - " . date('d/m/Y', strtotime($dia)) . "<br>";
  }

  return $msg;
}


function enviaEmail($emailDeOrigem, $nomeDeOrigem, $senha, $emailDeDestino, $nomeDeDestino, $assunto, $msg)
{
  $mail = new PHPMailer();
  // Define os dados do servidor e tipo de conexão
  //$mail->SMTPDebug  = 2;
  //$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
  //$mail->Username = 'seumail@dominio.net'; // Usuário do servidor SMTP
  //$mail->Password = 'senha'; // Senha do servidor SMTP

  // Config Gmail
  $mail->IsSMTP(); // Define que a mensagem será SMTP
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->SMTPDebug = 0;
  $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
  $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
  $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
  $mail->Username   = $emailDeOrigem;     // GMAIL username
  $mail->Password   = $senha;               // GMAIL password

  // Define o remetente
  $mail->SetFrom($emailDeOrigem, $nomeDeOrigem);
  $mail->AddReplyTo($emailDeOrigem, $nomeDeOrigem);

  // Define os destinatário(s)
  $mail->AddAddress($emailDeDestino, $nomeDeOrigem);
  //$mail->AddAddress('ciclano@site.net');
  //$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
  //$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta

  // Define os dados técnicos da Mensagem
  $mail->ContentType = 'text/plain';
  #$mail->IsHTML(true);
  $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

  // Define a mensagem (Texto e Assunto)
  $mail->Subject  = $assunto; // Assunto da mensagem
  $mail->Body = $msg;
  $mail->AltBody = $msg; #texto PURO

  // Define os anexos (opcional)
  //$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
  $emailEnviado = $mail->Send();
  //var_dump($emailEnviado);
  if ($emailEnviado) {
    header("Location: email_enviado.php");
  }
  // Limpa os destinatários e os anexos
  $mail->ClearAllRecipients();
  #$mail->ClearAttachments();
  if (!$emailEnviado) {
    $m = "Informações do erro: <pre>" . print_r($mail->ErrorInfo) . "</pre>";
    echo "Não foi possível enviar o e-mail", $m;
    return false;
  }
  return true; #booleano
}
