<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- <meta http-equiv="refresh" content="3"> -->
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.1/css/all.css' integrity='sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz' crossorigin='anonymous'>
	<link href="fonts/fonts.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
	<title>comuREDE</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<!-- 
		Link Guide https://developers.google.com/chart/interactive/docs/gallery/areachart 
	-->
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script>
		const endpoint = 'servicos.php?param=estado_luz_grafico';
		const infoDataLuz = [];

		axios
			.get(endpoint)
			.then((response) => {
				let data = response.data;
				console.log('data Luz', data);
				data.forEach((info) => {
					infoDataLuz.push(info)
				})				
			})
			.catch((error) => {
				console.log('error: ', error);
			});

		google.charts.load('current', {
			'packages': ['corechart']
		});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Luz', 'Caindo'],
				[validation(infoDataLuz[0].dia), infoDataLuz[0].caiu], [validation(infoDataLuz[1].dia), infoDataLuz[1].caiu], 
				[validation(infoDataLuz[2].dia), infoDataLuz[2].caiu], [validation(infoDataLuz[3].dia), infoDataLuz[3].caiu], 
				[validation(infoDataLuz[4].dia), infoDataLuz[4].caiu], [validation(infoDataLuz[5].dia), infoDataLuz[5].caiu], 
				[validation(infoDataLuz[6].dia), infoDataLuz[6].caiu], [validation(infoDataLuz[7].dia), infoDataLuz[7].caiu]
			]);

			var options = {
				title: 'últimos 7 dias',
				titleTextStyle: {
					color: '#9e3eba'
				},
				hAxis: {
					title: 'Dias',
					titleTextStyle: {
						color: '#9e3eba'
					},
					baseline: '8',
					baselineColor: '#00A79E',
					gridlines: {
						color: 'none',
					}
				},
				vAxis: {
					baselineColor: '#00A79E',
					gridlines: {
						color: 'none',
					}
				},
				colors: ['#FF8760', '#00A79E'],
				backgroundColor: '#4e1d69',
				chartArea: {
					backgroundColor: '#4e1d69',
					left: 20,
					width: '90%',
					margin: 'auto'
				},
				series: {
					0: {
						lineWidth: 4,
					}
				}
			};

			var chart = new google.visualization.AreaChart(document.getElementById('chart_div--ligth'));
			chart.draw(data, options);
		}

	</script>
	<script>
		const graficoAgua = './servicos.php?param=estado_agua_grafico';
		const infoDataAgua = [];

		axios
			.get(graficoAgua)
			.then((response) => {
				let data = response.data;
				console.log('data Agua: ', data);
				
				data.forEach((info) => {
					infoDataAgua.push(info)
				})
			})
			.catch((error) => {
				console.log('error: ', error);
			})

		google.charts.load('current', {
			'packages': ['corechart']
		});
		google.charts.setOnLoadCallback(drawChart);

		const validation = (value) => {
			return value.substring(2,10)
		}

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Água', 'Caindo'],
				[validation(infoDataAgua[0].dia), infoDataAgua[0].caiu], [validation(infoDataAgua[1].dia), infoDataAgua[1].caiu], 
				[validation(infoDataAgua[2].dia), infoDataAgua[2].caiu], [validation(infoDataAgua[3].dia), infoDataAgua[3].caiu], 
				[validation(infoDataAgua[4].dia), infoDataAgua[4].caiu], [validation(infoDataAgua[5].dia), infoDataAgua[5].caiu], 
				[validation(infoDataAgua[6].dia), infoDataAgua[6].caiu], [validation(infoDataAgua[7].dia), infoDataAgua[7].caiu]
			]);

			var options = {
				title: 'últimos 7 dias',
				titleTextStyle: {
					color: '#9e3eba'
				},
				hAxis: {
					title: 'Dias',
					titleTextStyle: {
						color: '#9e3eba'
					},
					baseline: '8',
					baselineColor: '#00A79E',
					gridlines: {
						color: 'none',
					}
				},
				vAxis: {
					// minValue: 1,
					baselineColor: '#00A79E',
					gridlines: {
						color: 'none',
					}
				},
				colors: ['#FF8760', '#00A79E'],
				backgroundColor: '#4e1d69',
				chartArea: {
					backgroundColor: '#4e1d69',
					left: 20,
					width: '90%',
					margin: 'auto'
				},
				series: {
					0: {
						lineWidth: 4,
					}
				}
			};

			var chart = new google.visualization.AreaChart(document.getElementById('chart_div--water'));
			chart.draw(data, options);
		}
  </script>
</head>

<body class="bodyServices">
	<header class="header">
		<h1 class="header__title header__title--services">Serviços</h1>
	</header>
	<main class="container container--services">
		<section class="container__section">
			<h2 class="container__title container__title--service">Tá caindo água?</h2>
			<img class="container__imgIndicator" id="torneiraAgua" alt="Torneira">
      <div id="chart_div--water"></div>
		</section>
		<section class="container__section">
			<img class="container__imgIndicator" id='lampadaLuz' alt="Lampada">
			<h2 class="container__title container__title--service container__title--right">Tá com luz?</h2>
			<div id="chart_div--ligth"></div>
    </section>
    <form class="container__formRegister container__formSubscribe" method="POST" action="md.php">
        <input type="hidden" name="cep" value="24130400">
        <h2 class="formRegister__title">
						COBRE pelos seus direitos com nossos relatórios. Selecione um período, entre com seu e-mail e saiba quando o serviço não foi entregue.
				</h2>
        <label class="formRegister__item" for="data1">
            Data Inicial
            <input class="formRegister__itemInput" type="date" name="data1">
        </label>
        <label class="formRegister__item" for="data2">
            Data Final
            <input class="formRegister__itemInput" type="date" name="data2">
        </label>
        <label for="tipo" class="formRegister__item">Água
					<input type="radio" id="tipo" name="tipo" value="A">
				</label>
        <label for="tipo" class="formRegister__item">Luz
					<input type="radio" id="tipo" name="tipo" value="E">
				</label>  
        <label class="formRegister__item" for="email">
            E-mail
            <input class="formRegister__itemInput" type="email" name="email">
        </label>
        <button class="formRegister__itemButton btn__default">Enviar</button>
    </form>
	</main>
	<footer class="footer footer--service ">
		<a class="footer__linkHome " href="index.html ">
			<img class="footer__logoService " src="images/logo-comuREDE_02.png " alt=" ">
		</a>
	</footer>
	<script>
      let aguaAgora = null,
        luzAgora = null
      const torneira = document.getElementById('torneiraAgua'),
				lampada = document.getElementById('lampadaLuz');

			if(aguaAgora === null) {
				torneira.src='./images/loader.svg';
				torneira.style.width='15%';
				torneira.style.margin='10% auto';
			}
			if(luzAgora === null) {
				lampada.src='./images/loader.svg';
				lampada.style.width='15%';;
				lampada.style.margin='10% auto';
			}
			
			
			function requisiçãoÁgua() {
				axios
					.get('./servicos.php?param=estado_agua_agora')
					.then((response) => {
						aguaAgora = response.data
	
						if(aguaAgora === "L") {
							torneira.src='./images/Icone-torneira.svg';
							torneira.style.width='50%';
						} else if(aguaAgora === "D") {
							torneira.src='./images/Icone-torneira-2.svg';
							torneira.style.width='50%';
						} else {
							console.log('Deu erro!');
						}
					})
					.catch((error) => {
						console.log('error: ', error);
					})
			};

			setInterval(requisiçãoÁgua, 3000);
			
			function requisiçãoLuz() {
				axios
					.get('./servicos.php?param=estado_luz_agora')
					.then((response) => {
						luzAgora = response.data
	
						if(luzAgora === "L") {
							lampada.src='./images/Icone-Lampada.svg';
							lampada.style.width='50%';
						} else if(luzAgora === "D") {
							lampada.src='./images/Icone-Lampada-2.svg'
							lampada.style.width='35%';
						} else {
							lampada.src='./images/loader.svg'
						}
					})
					.catch((error) => {
						console.log('error: ', error);
					});
			}

			setInterval(requisiçãoLuz, 3000);

        
    </script>
</body>

</html>
