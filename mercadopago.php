<?php

$access_token = "TEST-457955750237064-111023-e27e887456713b90b9d9be363dc94a3a-621563634";
$public_key = "TEST-9e51b57a-117a-4263-ba20-e77bcab68fcc";

require __DIR__  . '/vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

MercadoPagoConfig::setAccessToken($access_token);

$client = new PreferenceClient();
$preferenceMensual = $client->create([
    "items"=> array(
        array(
            "title"=>"Suscripcion Mensual TaskIt",
            "quantity"=> 1,
            "currency_id"=>"ARS",
            "unit_price"=>100
        )
    ),
    "back_urls"=>array(
        "success"=>"http://localhost/TaskIt/sql/usuarioABM.php?accion=suscripcion&plan=mensual",
        "failure"=>"http://localhost/TaskIt/index.php",
        "pending"=>"http://localhost/TaskIt/index.php"
    )
]);
$preferenceAnual = $client->create([
    "items"=> array(
        array(
            "title"=>"Suscripcion Anual TaskIt",
            "quantity"=> 1,
            "currency_id"=>"ARS",
            "unit_price"=>600
        )
    ),
    "back_urls"=>array(
        "success"=>"http://localhost/TaskIt/sql/usuarioABM.php?accion=suscripcion&plan=anual",
        "failure"=>"http://localhost/TaskIt/index.php",
        "pending"=>"http://localhost/TaskIt/index.php"
    )
]);

?>

<div class="mp-mensual-btn show disabled"></div>
<div class="mp-anual-btn"></div>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago("TEST-9e51b57a-117a-4263-ba20-e77bcab68fcc");
    mp.checkout({
        preference:{
            id:"<?php echo $preferenceMensual->id; ?>"
        },
        render:{
            container:'.mp-mensual-btn',
            label:'Pagar con Mercado Pago',
        }
    })
    mp.checkout({
        preference:{
            id:"<?php echo $preferenceAnual->id; ?>"
        },
        render:{
            container:'.mp-anual-btn',
            label:'Pagar con Mercado Pago',
        }
    })

    document.querySelector('.plan-btn.mensual').addEventListener('click', () => {
        document.querySelector('.mp-anual-btn').classList.remove('show')
        document.querySelector('.mp-mensual-btn').classList.add('show')
        document.querySelector('.mp-mensual-btn').classList.remove('disabled')
        document.querySelector('.subscription-card.mensual').classList.add('selected')
        document.querySelector('.subscription-card.anual').classList.remove('selected')
    })
    document.querySelector('.plan-btn.anual').addEventListener('click', () => {
        document.querySelector('.mp-mensual-btn').classList.remove('show')
        document.querySelector('.mp-anual-btn').classList.add('show')
        document.querySelector('.mp-mensual-btn').classList.remove('disabled')
        document.querySelector('.subscription-card.anual').classList.add('selected')
        document.querySelector('.subscription-card.mensual').classList.remove('selected')
    })
</script>