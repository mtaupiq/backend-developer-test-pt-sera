<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class BillingController extends Controller
{
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @OA\Get(
     *     path="/billdetails",
     *     summary="Billdetails",
     *     description="7. Billdetails",
     *     operationId="billdetails",
     *     tags={"7. Billdetails"},
     *     @OA\Response(
     *         response="200",
     *         description="Returns billdetails >= 100000"
     *     )
     * )
     */
    public function billdetails()
    {
        $url = 'https://gist.githubusercontent.com/Loetfi/fe38a350deeebeb6a92526f6762bd719/raw/9899cf13cc58adac0a65de91642f87c63979960d/filter-data.json';

        $response = $this->client->get($url);
        $body = $response->getBody();
        $data = json_decode($body, true);
        $billdetails = $data['data']['response']['billdetails'];

        $denoms = array();

        foreach ($billdetails as $billdetail) {
            $body = $billdetail['body'];
            foreach ($body as $denom) {
                $denom = str_replace(' ', '', $denom);
                $denom = explode(':', $denom);
                $denom = (int) $denom[1];
                if ($denom >= 100000) {
                    array_push($denoms, $denom);
                }
            }
        }
        
        // dd($denoms);
        return $denoms;
    }
}
