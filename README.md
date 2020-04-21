# checkpoint-php


### Installation
```
composer require checkpointemail/checkpoint-php
```

### Initialization
```
use checkpointemail/checkpointPHP;

$CheckpointEmail = new CheckpointEmail('YOUR-API-KEY');
```

### API Call


#### Email Verifier
  
This API Call checks the deliverability of a given email address.
```
$CheckpointEmail->verify('client@company.com');
```
