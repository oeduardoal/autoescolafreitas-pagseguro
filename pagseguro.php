<?php
	
	$precos = array('A' => '954.00', 'B' => '1404.00', 'AB' => '1804.00');
	$categoria = $_POST['categoria'];
	$preco_cat = $precos[$categoria];

	// Cartão ou Boleto
	$tipo_de_pagamento = $_POST['tipo_de_pagamento'];

	// Informações da conta do vendedor
	$data['token'] ='D75803066F4F4FAABECC69D3938E5329';
	// $data['token'] ='74CAAE9C650B45B8B3735F324AD8E41E';
	$data['email'] = "eduardoalmeida258@gmail.com";

	$data['currency'] = 'BRL';	
	$data['itemId1'] = $_POST['categoria'];
	$data['itemQuantity1'] = '1';
	$data['itemDescription1'] = "Categoria " . $categoria;
	$data['itemAmount1'] = $preco_cat;

	// Se o tipo de pagamento for boleto, dar desconto de 200 reais
	if ($tipo_de_pagamento == 'boleto') {
		$data['extraAmount'] = '-200.00';
		$data['acceptPaymentMethodGroup'] = 'BOLETO';
	}else{
		$data['acceptPaymentMethodGroup'] = 'CREDIT_CARD';
	}
			
	$data['senderName'] = $_POST['nome'];
	$data['senderPhone'] = $_POST['telefone'];
	$data['senderAreaCode'] = $_POST['ddd'];
	$data['senderCPF'] = $_POST['cpf'];

	// Cliente Teste
	$data['senderEmail'] = $_POST['email'];

	// Tipo de frete - (3) Não especificado
	$data['shippingType'] = '3';

	// $data['shippingAddressStreet'] = $_POST['rua'];
	// $data['shippingAddressDistrict'] = $_POST['bairro'];
	$data['shippingAddressPostalCode'] = $_POST['cep'];
	// $data['shippingAddressCity'] = $_POST['cidade'];
	// $data['shippingAddressState'] = $_POST['estado'];
	// $data['shippingAddressCountry'] = 'BRA';

	$url = 'https://ws.pagseguro.uol.com.br/v2/checkout';

	$data = http_build_query($data);

	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	$xml = curl_exec($curl);

	curl_close($curl);

	//Transforma o xml para string
	$xml = simplexml_load_string($xml);

	if(count($xml->error) > 0){
			$return = 'Dados Inválidos '.$xml->error->message;
			echo $return;
		exit;
	}
	echo $xml->code;

?>