<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="app.css">
</head>
<body >
	<div id="app">
		<form method="POST" id="form">
			<section class="formulario">
				<div class="row">
					<div class="col-sm-12 columns">
						<small>Nome Completo*</small>
						<input class="form-control" type="text" name="nome" v-model="nome" />
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2 columns">
						<small>DDD*</small>
						<select class="form-control" name="ddd">
							<option v-bind:value="ddd" v-for="ddd in ddds">
								{{ ddd }}
							</option>
						</select>
					</div>
					<div class="col-sm-5 columns">
						<small>Telefone, fixo ou celular*</small>
						<input class="form-control" type="phone" name="telefone" />
					</div>
					<div class="col-sm-5 columns">
						<small>E-mail*</small>
						<input class="form-control" type="email" name="email" />
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 columns">
						<small>CEP*</small>
						<input class="form-control" type="text" name="cep" v-model="cep" placeholder="00000-000" />
					</div>
					<div class="col-sm-6 columns">
						<small>Bairro*</small>
						<input class="form-control" type="text" name="bairro" v-model="bairro" />
					</div>
				</div>
				<div class="row">
					<div class="col-sm-7 columns">
						<small>Rua*</small>
						<input class="form-control" type="text" name="rua" v-model="rua" />
					</div>
					<div class="col-sm-5 columns">
						<small>Cidade*</small>
						<input class="form-control" type="text" name="cidade" v-model="cidade"  />
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2 columns">
						<small>Estado*</small>
						<input class="form-control" type="text" name="estado" v-model="estado" />
					</div>
					<div class="col-sm-4 columns">
						<small>CPF*</small>
						<input class="form-control" type="text" name="cpf" />
					</div>
					<div class="col-sm-6 columns">
						<small>RG*</small>
						<input class="form-control" type="text" name="rg" />
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3 columns">
						<small>Categoria pretendida*</small>
						<select class="form-control" name="categoria" v-model="categoria">
							<option value="A">Categoria A</option>
							<option value="B">Categoria B</option>
							<option value="AB">Categoria AB</option>
						</select>
					</div>
					<div class="col-sm-4 columns">
						<small>Tipo de pagamento*</small>
						<select class="form-control" name="tipo_de_pagamento">
							<option value="boleto">Boleto Bancário R$ {{ preco_boleto }} </option>
							<option value="cartao_credito">Cartão de Crédito R$ {{ preco_cartao }} </option>
						</select>
					</div>
					<div class="col-sm-5 columns">
						<small>Nome da mãe*</small>
						<input class="form-control" type="text" name="rg" />
					</div>
				</div>
				<div class="row btnpagamento text-right">
					<div class="col-sm-6 col-sm-offset-6 columns">
						<button type="button" class="btn btn-primary btn-block button-pagamento" id="comprar">PROSSEGUIR PARA PAGAMENTO</button>
					</div>
				</div>
			</section>
		</form>
	</div>
</body>
</html>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/vue"></script>
	<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
<script type="text/javascript">
	var app = new Vue({
		el: '#app',
		data: {
			nome: '',
			cep: '',
			rua: '',
			bairro: '',
			cidade: '',
			estado: '',
			ddds: null,
			categoria: 'A',
			preco_boleto: '954.00',
			preco_cartao: '754.00',
		},
		created: function () {
			self = this;
		    jQuery.getJSON('http://ddd.pricez.com.br/ddds', function(response){
		    	self.ddds = response.payload;
			});
		},
		watch: {
			nome: function(n){
				var string = n;
				var mapaAcentosHex 	= {
					a : /[\xE0-\xE6]/g,
					A : /[\xC0-\xC6]/g,
					e : /[\xE8-\xEB]/g,
					E : /[\xC8-\xCB]/g,
					i : /[\xEC-\xEF]/g,
					I : /[\xCC-\xCF]/g,
					o : /[\xF2-\xF6]/g,
					O : /[\xD2-\xD6]/g,
					u : /[\xF9-\xFC]/g,
					U : /[\xD9-\xDC]/g,
					c : /\xE7/g,
					C : /\xC7/g,
					n : /\xF1/g,
					N : /\xD1/g,
				};

				for ( var letra in mapaAcentosHex ) {
					var expressaoRegular = mapaAcentosHex[letra];
					string = string.replace( expressaoRegular, letra );
				}

				this.nome = string;
			},
			cep: function (value) {
				if ( /^[0-9]{5}-[0-9]{3}$/.test(value) ) {
					self = this;
					jQuery.getJSON('https://viacep.com.br/ws/' + value + '/json/', function(endereco){
						self.rua = endereco.logradouro;
						self.bairro = endereco.bairro;
						self.cidade = endereco.localidade;
						self.estado = endereco.uf;
					});
				}
			},
			categoria: function (value_cat) {
				switch(value_cat){
					case 'A':
						this.preco_cartao = "954.00";
						this.preco_boleto = "754.00";
					break;	
					case 'B':
						this.preco_cartao = "1404.00";
						this.preco_boleto = "1204.00";
					break;
					case 'AB':
						this.preco_cartao = "1804.00";
						this.preco_boleto = "1604.00";
					break;		
				}
			}
		}
	})
</script>
	<script>
		(function($){
			$('#comprar').click(function(){
				var form = $('#form').serialize();
				$('#comprar').attr('disabled', 'disabled')
				$.post('pagseguro.php', form, function(token){
					var isOpenLightbox = PagSeguroLightbox({
						code: token
					}, {
					success : function(transactionCode) {
						alert("success - " + transactionCode);
					},
					abort : function() {
						$("#comprar").removeAttr('disabled');

					}
					});
					if (!isOpenLightbox){
						location.href="https://pagseguro.uol.com.br/v2/checkout/payment.html?code="+code;
					}
				})
			});

		})(jQuery)
	</script>