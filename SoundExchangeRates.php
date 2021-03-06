/*
* SoundExchangeRates.php
* @author Alex Sokolov <admin@gelezako.com>
* @copyright Alex Sokolov http://www.blog.gelezako.com (c)
* @version 0.1
*/


$url = 'https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=11';
$xml = simplexml_load_file($url);
$i=0;

if(!empty($data)){
	//получаем курс евро
	foreach($xml->row[1]->exchangerate->attributes() as $key => $exchangerate){
		if($i==2){

		  sg("Rate.eurobuy",round((float)$exchangerate,1));
		}
	else if($i==3){
		sg("Rate.eurosale",round((float)$exchangerate,1));
		}
		++$i;
	}
}

//получаем курс доллара
$j=0;
if(!empty($data)){
	foreach($xml->row[0]->exchangerate->attributes() as $key => $exchangerate){
		if($j==2){
		sg("Rate.usdbuy",round((float)$exchangerate,1));
		}
		else if($j==3){
		sg("Rate.usdsale",round((float)$exchangerate,1));
		}
		++$j;
	}
}
// определяем падеж слова
function padej($kop,$valuta){
	 if ($valuta=="gr" and $kop=="2" or $kop=="3" or $kop=="4") return "гривны";
	 else if($valuta=="gr") return "гривен";
	 else if($valuta=="rub"and $kop=="1") return "рубль";
	 else if($valuta=="rub"and $kop=="2" or $kop=="3" or $kop=="4") return "рубля";
	 else if($valuta=="rub") return "рублей";
}

$eurob=(string)gg("Rate.eurobuy");
$pieces_eb = explode(".", $eurob);
$euros=(string)gg("Rate.eurosale");
$pieces_es = explode(".", $euros);

$usdb=(string)gg("Rate.usdbuy");
$pieces_ub = explode(".", $usdb);
$usds=(string)gg("Rate.usdsale");
$pieces_us = explode(".", $usds);

$rurd=(string)gg("Rate.dollarrur");
$pieces_rd = explode(".", $rurd);

$rure=(string)gg("Rate.eurorur");
$pieces_re = explode(".", $rure);

$eurob*=10;
$euros*=10;
$usdb*=10;
$usds*=10;
$rurb*=10;
$rurs*=10;

//если валюта по умолчанию не указана, то использовать гривну
if ($params['Currency1']=="") {$params['Currency1']="гривна";$params['number']=1;}
	
//для гривны
if($params['Currency'] == 'евро' and $params['Currency1'] == '' and $params['number'] == '') say("За 10 евро ".$euros." ".padej($pieces_es[1],"gr"),2);
else if($params['Currency'] == 'доллара' and $params['Currency1'] == '' and $params['number'] == '') say("За 10 долларов ".$usds." ".padej($pieces_us[1],"gr"),2);
else if($params['Currency'] == 'рубль' and $params['Currency1'] == '' and $params['number'] == '') say("За 10 рублей ".$rurs." ".padej($pieces_rs[1],"gr"),2);

// вычисляем доллар - гривна(рубль)
else if($params['Currency'] == 'доллар' or $params['Currency'] == 'доллара' or $params['Currency'] == 'долларов'){
 	if($params['Currency1'] == 'гривна' or $params['Currency1'] == 'гривнах'){
      $cur=$params['number']*gg('Rate.usdsale')." ".padej($pieces_us[1],"gr");
      say($cur,2);
	}
  	else if($params['Currency1'] == 'рубль' or $params['Currency1'] == 'рублях'){
      $cur=$params['number']*gg('Rate.dollarrur')." ".padej($pieces_rd[1],"rub");
      say($cur,2);
	}
}

// вычисляем евро - гривна(рубль)
	else if($params['Currency'] == 'евро'){
 	if($params['Currency1'] == 'гривна' or $params['Currency1'] == 'гривнах'){
      $cur=$params['number']*gg('Rate.eurosale')." ".padej($pieces_es[1],"gr");
      say($cur,2);
	}
 	 else if($params['Currency1'] == 'рубль' or $params['Currency1'] == 'рублях'){
      $cur=$params['number']*gg('Rate.eurorur')." ".padej($pieces_re[1],"rub");
      say($cur,2);
	}
}

// вычисляем рубль - гривна
else if($params['Currency'] == 'рубль' or $params['Currency'] == 'рублей'){
 	if($params['Currency1'] == 'гривна' or $params['Currency1'] == 'гривнах'){
     $cur=$params['number']*gg('Rate.rursale')." ".padej($pieces_rs[1],"gr");
     say($cur,2);
	}
}

// вычисляем гривна(рубль) - евро
	else if($params['Currency1'] == 'евро'){
 	if($params['Currency'] == 'гривна'){
      $cur=round((float)$params['number']/gg('Rate.eurosale'),2)." ".$params['Currency1'];
      say($cur,2);
	}
 	 else if($params['Currency'] == 'рубль'){
      $cur=round((float)$params['number']/gg('Rate.eurorur'),2)." ".$params['Currency1'];
      say($cur,2);
	}
}

// вычисляем гривна(рубль) - доллар
	else if($params['Currency1'] == 'доллар'){
 	if($params['Currency'] == 'гривна'){
      $cur=round((float)$params['number']/gg('Rate.usdsale'),2)." ".$params['Currency1'];
      say($cur,2);
	}
 	  if($params['Currency'] == 'рубль' or $params['Currency1'] == 'рублей'){
      $cur=round((float)$params['number']/gg('Rate.dollarrur'),2)." ".$params['Currency1'];
      say($cur,2);
	}
}


else if($params['Currency'] == 'все валюты' or $params['Currency'] == 'всех валют')
$currency.="Курс валют: за 10 евро. Покупка ".$eurob." ".padej($pieces_eb[1],"gr").", продажа ".$euros." ".padej($pieces_es[1],"gr")." . За 10 долларов: покупка ".$usdb." ".padej($pieces_ub[1],"gr")." . Продажа ".$usds." ".padej($pieces_us[1],"gr");
say($currency,2);
