# Webmoney

WebMoney Transfer payment system API library.

Project was forked from latest dkameleon/WMXI library and provide high-level logic, testing capabilities and some tools for easy to use and quick start with Webmoney Transfer API.

## Configuration check

Check your configuration by running:

    $ php ./tools/check.php
    
or if you not have `cli` installed run it through your browser by point it to:

    http://yourdomain.com/path/to/webmoney/tools/check.php

Note:

    After check is being complete be sure to remove `check.php`.

## Usage

Example of usage high-level logic class Webmoney:

```php
require_once (__DIR__ . '/src/webmoney/Webmoney.php');
$Webmoney = new Webmoney();
$Webmoney->Light(/* params */);
$result = $Webmoney->transferFunds(/* params */);
```

Example of usage X2 XML interface:

```php
require_once (__DIR__ . '/src/webmoney/WMXI.php');
$Wmxi = new WMXI();
$Wmxi->Light(/* params */);
$result = $Wmxi->X2(/* params */);
```

Purse with internal validation:

```php
require_once (__DIR__ . '/src/webmoney/Purse.php');
$Purse = new Purse('PURSE ID');
if (!$Purse->isValid()) {
    echo 'Purse ID = ' . $Purse->getId() . ' is invalid';
}
$server_responce_after_validation = $Purse->getResultX8();
```

## Folder structure

```
cert/         - public Webmoney certificates
examples/     - old WMXI examples
keys/         - your private keys and certificates
src/
    webmoney/ - Webmoney library
tests/        - PHPUnit tests
tools/        - tools for configuration check and converting certificates
```

## Certificates

Folder ./cert/ contains following certificates:

```
WebMoneyCA.crt     - WebMoney root certificate.
WebMoneyCA.pem.crt - WebMoney root certificate in PEM format (for use with the cURL library).
WMUsedRootCAs.cer  - Set of WebMoney, Network Solutions and Verisign root certificates
                     (recommended for services and developers using the XML interfaces).
```

To read more follow this link https://wiki.wmtransfer.com/projects/webmoney/wiki/WebMoney_root_certificate

## Testing

Testing available only if PHPUnit is installed. To run tests follow this list:

    1. Add your certificates and keys to ./keys/ folder;

    2. Make a copy of ./tests/authn.dist.php to ./tests/authn.php;

    3. Go to console and run:
    
        $ phpunit
    
       or to run specific test for Purse:
       
        $ phpunit ./tests/webmoney/PurseTest.php

## WMXI library support

* http://my-tools.net/wmxi/
* http://talk.dkameleon.net/
