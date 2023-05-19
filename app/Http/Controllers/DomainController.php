<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iodev\Whois\Factory;

class DomainController extends Controller
{
    public function rules()
    {
        return [
            'name' => ['required', 'regex:^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$']
        ];
    }

    public function index(Request $request): array
    {
        $post = $request->post();
        $domain_name = $post['name'];
        return $this->whois($domain_name);
    }

    public function whois($domain)
    {
        $whois = Factory::get()->createWhois();
        $result['domain'] = $domain;
        $result['available'] = $whois->isDomainAvailable($domain) ? 'сбоводен' : 'не сбоводен';
        $info = $whois->loadDomainInfo($domain);
        if ($info) {
            $result['created'] = date("Y-m-d", $info->creationDate);
            $result['expires'] = date("Y-m-d", $info->expirationDate);
            $result['owner'] = $info->owner;
        }
        return $result;
    }
}
