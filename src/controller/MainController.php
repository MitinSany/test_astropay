<?php

namespace app\controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class MainController
{
    protected $container;

    // constructor receives container instance
    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
    }

    public function actionIndex(Request $request, Response $response, array $args)
    {
        return $this->container->view->render($response, 'main.twig', [
            'amount' => rand(0, 200),
            'userid' => rand(1000000, 10000000)
        ]);
    }

    public function actionPayDirect(Request $request, Response $response, array $args)
    {
        $api = new \astropay\AstroPayDirect();
        $config = ($this->container->get('settings'))->get('astropay');
        $api->x_login = $config['x_login'];
        $api->x_trans_key = $config['x_trans_key'];
        $api->secret_key = $config['x_secret_key'];

        $invoice = 'inv-' . dechex(crc32(time()));
        $amount = $request->getParsedBody()['inputAmount'];
        $iduser = $request->getParsedBody()['inputIduser'];
        $bank = 'TE';
        $country = 'UA';
        $currency = 'USD';
        $description = 'test transaction';
        $cpf = '123';
        $sub_code = '1';
        $return_url = $config['return_url'];
        $confirmation_url = $config['confirm_url'];

        $queryResponse = json_decode($api->create($invoice, $amount, $iduser, $bank, $country, $currency, $description
            , $cpf, $sub_code, $return_url, $confirmation_url, $response_type = 'json'), true);

        return $response->withStatus(302)->withHeader('Location', $queryResponse['link']);
    }

    public function actionPayStreamline(Request $request, Response $response, array $args)
    {
        $api = new \astropay\AstroPayStreamline();
        $config = ($this->container->get('settings'))->get('astropay');
        $api->x_login = $config['x_login'];
        $api->x_trans_key = $config['x_trans_key'];
        $api->secret_key = $config['x_secret_key'];

        $invoice = 'inv-' . dechex(crc32(time()));
        $amount = $request->getParsedBody()['inputAmount'];
        $iduser = $request->getParsedBody()['inputIduser'];
        $bank = 'TE';
        $country = 'UA';
        $currency = 'USD';
        $description = 'test transaction';
        $cpf = 34323;
        $sub_code = 1;
        $return_url = $config['return_url'];
        $confirmation_url = $config['confirm_url'];
        $name = 'Alex';
        $email = 'user@example.com';

        $queryResponse = $api->newinvoice($invoice, $amount, $bank, $country, $iduser, $cpf, $name, $email, $currency
            , $return_url, $confirmation_url, $description);

        $decoded_response = json_decode($queryResponse);
        if ($decoded_response->status == 0) {
            $url = $decoded_response->link;
            return $response->withStatus(302)->withHeader('Location', $url);
        } else {
            //manage error here
            $error = $decoded_response->desc;
            echo $error;
        }
    }

    public function actionTest(Request $request, Response $response, array $args)
    {
        $config = ($this->container->get('settings'))->get('astropay');
        $login = $config['x_login'];
        $trans_key = $config['x_trans_key'];
        $secret = $config['x_secret_key'];

        $api = new \Astropay\CashoutCard(\Astropay\constants::ENV_SANDBOX);
        $api->setCredentials($login, $trans_key, $secret);
        $api->setAmount(100);
        $api->setCurrency('USD');
        $api->setEmail('test@astropaycard.com');
        $api->setName('Test recipient');
        $api->setDocument('8976fsdf1234');

        if($api->sendCard()){
            echo urldecode($api->getMessage());
            echo '<br/>'.$api->getAuthCode();
        } else {
            echo urldecode($api->getMessage());
        }
    }
}