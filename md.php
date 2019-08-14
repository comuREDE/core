<?php 
require 'init.php';
/*este eh commentario*/≈

if($_POST){
  extract($_POST);

/*  $data1="2019-02-19";
  $data2="2019-03-19";
  $email = "frimes@gmail.com";
  $cep="24130400";
  $tipo="E";
*/  
  $data1 = DateTime::createFromFormat('Y-m-d',$data1);
  $data_str_1 = $data1->format('Y/m/d');

  $data2 = DateTime::createFromFormat('Y-m-d',$data2);
  $data_str_2 = $data2->format('Y/m/d');
  
  if(montaEnviaEmail($email,$data_str_1,$data_str_2,$cep,$tipo)){
    echo "<h1>Email enviado com sucesso</h1>";
  } else {
    echo "<h1>Erro ao enviar email</h1>";
  }

}




/*  if(!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $data1, $matches)){
    echo "<h3>erro em data1</h3>";
  }
  
  if(!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $data2, $matches)){
    echo "<h3>erro em data2</h3>";
  }

  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo "<h3>email invalido</h3>";
  } 
*/  






function getCepByEmail(string $email){
  $res = (new Model())->setTable('cadastros')->getOneBy('email',$email);
  echo $res['cep'];
}


function montaEnviaEmail(string $email,string $data1,string $data2, string $cep,string $tipo='A'){

  $tipo_diferencial = $tipo=='A'?" Agua":" Luz";

  $linha = $tipo=='A'?"No período selecionado, nestes dias caiu água:<br><br>":"No período selecionado, nestes dias faltou luz:<br><br>";
  $header = "Relatório comuREDE ($tipo_diferencial) <br>";
  $texto = montaRelatorioEmail($data1,$data2,$cep,$tipo);
  $footer = "<br>Tamo junto!<br>www.comurede.net";
  $msg = $header . $texto . $footer;
  $assunto = "Relatório comuREDE ($tipo_diferencial)";
  $nome = "Usuário comuREDE";
  return enviaEmail(GMAIL,"comuREDE",SENHA,$email,$nome,$assunto,$msg);

}


function montaRelatorioEmail($data1,$data2,$cep,$tipo='A'){

  $q="SELECT DISTINCT data_hora FROM relatorios WHERE 
  CAST(data_hora AS DATE) BETWEEN '$data1' AND '$data2' AND tipo='$tipo';";
  #echo $q;
  $res = (new BD())->query($q);
  #print_r($res);
  $dias = array_column($res, "data_hora");
  $dias_sem = ['DOM','SEG','TER','QUA','QUI','SEX','SAB',];

  $msg = "";
  foreach ($dias as $dia) {
    $dia_num = date('w',strtotime($dia));
    $msg .=  $dias_sem[$dia_num] . " - " . $dia . "<br>"; 
  }
  return $msg;
}




function enviaEmail($emailDeOrigem,$nomeDeOrigem,$senha,$emailDeDestino,$nomeDeDestino,$assunto,$msg){
  $mail = new PHPMailer();
  // Define os dados do servidor e tipo de conexão
  //$mail->SMTPDebug  = 2;
  //$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
  //$mail->Username = 'seumail@dominio.net'; // Usuário do servidor SMTP
  //$mail->Password = 'senha'; // Senha do servidor SMTP

  // Config Gmail
  $mail->IsSMTP(); // Define que a mensagem será SMTP
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
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
  // Limpa os destinatários e os anexos
  $mail->ClearAllRecipients();
  #$mail->ClearAttachments();
  if (!$emailEnviado) {
      $m= "Informações do erro: <pre>" . print_r($mail->ErrorInfo) ."</pre>";
      echo "Não foi possível enviar o e-mail",$m;
      return false;
  }
  return true; #booleano
}
