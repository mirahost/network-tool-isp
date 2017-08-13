<?php

namespace Mirahost\NetworkTools;

class Isp
{

    public function getIsp()
    {
        $result = '';
        $ispName = '';
        $ispStreet = '';
        $ispCity = '';
        $ispState = '';
        $ispCountry = '';
        $ispComment = '';

        $ipAddress = $_SERVER["REMOTE_ADDR"];

        $whoisUrl = 'http://whois.arin.net/rest/ip/';

        try {


            $data = file_get_contents($whoisUrl . $ipAddress);
            $xml = simplexml_load_string($data);
            $json = json_encode($xml);
            $output = json_decode($json, true);

        } catch (\Exception $e) {

            return  'Error Bad Request / Invalid IP Address';
        }


        if(isset($output['comment']['line'])) {

            $comment = $output['comment']['line'];

            if( is_array($comment)) {
                foreach ($comment as $line){

                    if(!is_array($line)) {

                        $ispComment .= $line . "\n";
                    }
                }
            } else {

                $ispComment = $comment . "\n";
            }

        }


        if(!empty($output['orgRef']))
        {

            $orgUrl = $output['orgRef'];

            $data = file_get_contents($orgUrl);
            $xml = simplexml_load_string($data);
            $orgJson = json_encode($xml);
            $orgOutput = json_decode($orgJson, true);


            if(isset($orgOutput['comment']['line'])) {

                $orgComment = $orgOutput['comment']['line'];

                if( is_array($orgComment)) {
                    foreach ($orgComment as $line){
                        $ispComment .= $line . "\n";
                    }
                } else {

                    $ispComment .= $orgComment . "\n";
                }

            }

            if(isset($orgOutput['name'])) {

                $ispName = $orgOutput['name'];
            }
            if(isset($orgOutput['streetAddress']['line'])) {

                $streetAddress = $orgOutput['streetAddress']['line'];

                if(is_array($streetAddress)) {
                    foreach ($streetAddress as $line) {

                        $ispStreet .= $line . ' ';
                    }
                } else {

                    $ispStreet = $streetAddress;
                }
            }
            if(isset($orgOutput['city'])) {

                $ispCity = $orgOutput['city'];
            }
            if(isset($orgOutput['iso3166-2'])) {

                $ispState = $orgOutput['iso3166-2'];
            }
            if(isset($orgOutput['iso3166-1']['code3'])) {

                $ispCountry = $orgOutput['iso3166-1']['code3'];
            }

        }


        if(!empty($ispName)) {

            $result  = "Name: ". $ispName . "\n";
        }

        if(!empty($ispStreet)) {

            $result .= "Street Address: ". $ispStreet . "\n";
        }

        if(!empty($ispCity)) {

            $result .= "City: ". $ispCity . "\n";
        }

        if(!empty($ispState)) {

            $result .= "State: ". $ispState . "\n";
        }

        if(!empty($ispCountry)) {

            $result .= "Country: ". $ispCountry . "\n";
        }

        if(!empty($ispComment)) {

            $result .= "Comment: ". $ispComment . "\n";
        }


        return $result;

    }
}