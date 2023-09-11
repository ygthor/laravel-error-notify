<?php

# resources/views/emails/exception.blade.php
function getIpAndCountry()
{
    $ip_address = \Request::ip();
    $ip_stack_api_key = config('laravel-error-notify.IP_STACK_API_KEY');
    $geoIpFrom = file_get_contents("http://api.ipstack.com/$ip_address?access_key=".$ip_stack_api_key);
    $geoIpFrom = json_decode($geoIpFrom, true);
    
    if( isset($geoIpFrom['country_name'])){
        return $geoIpFrom;
    }else{
        return [
            'ip' => $ip_address,
        ];
    }
    
}

$ip_info = getIpAndCountry();

$ip = $ip_info['ip'] ?? null;
$country_name = $ip_info['country_name'] ?? null;
$region_name = $ip_info['region_name'] ?? null;
$continent_name = $ip_info['continent_name'] ?? null;
$latitude = $ip_info['latitude'] ?? null;
$longitude = $ip_info['longitude'] ?? null;

?>
<h1>🚨⚠️ ERROR DETECTED !!! ⚠️🚨</h1>
<h2>APP NAME: {{ config('app.name') }}</h2>
<hr>

@if (!empty($error))
    <h3>{{ $error->getMessage() }}</h3>
    <h4>{{ $error->getFile() }} </h4>
    <h4>Line: {{ $error->getLine() }} </h4>
@endif

<table border=1>
    @if (auth()->check())
        <tr>
            <td>User id:</td>
            <td> {{ auth()->user()->id }}</td>
        </tr>
        <tr>
            <td>Username:</td>
            <td> {{ auth()->user()->name }}</td>
        </tr>
    @else
        <tr>
            <td>User:</td>
            <td> GUEST</td>
        </tr>
    @endif
    <tr>
        <td>Current Url: </td>
        <td>{{ Request::url() }}</td>
    </tr>
    <tr>
        <td>Previous Url:</td>
        <td>{{ URL::previous() }}</td>
    </tr>
    <tr>
        <td>IP</td>
        <td>{{ $ip }}</td>
    </tr>
    <tr>
        <td>Address</td>
        <td>
            Region Name: {{ $region_name }}<br>
            Country: {{ $country_name }}<br>
            Continent Name: {{ $continent_name }}<br>
        </td>
    </tr>
    <tr>
        <td>Timestamp</td>
        <td>{{ date('Y-m-d H:i:s') }}</td>
    </tr>
    <tr>
        <td valign="top">IP Info</td>
        <td>
            <pre>{!! json_encode($ip_info, JSON_PRETTY_PRINT) !!}</pre>
        </td>
    </tr>
    <tr>
        <td valign="top">Request Data</td>
        <td>
            <pre>{!! json_encode(Request::input(), JSON_PRETTY_PRINT) !!}</pre>
        </td>
    </tr>

</table>

<pre>
{!! $error ?? '-' !!}
</pre>
