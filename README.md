# checkpoint-php


### Installation
```
composer require angeljunior/checkpoint.email
```

### Initialization
```
use checkpointemail/Checkpoint-php;

$CheckpointEmail = new CheckpointEmail('YOUR-API-KEY');
```

### API Call


#### Email Verifier
  
This API Call checks the deliverability of a given email address.
```
$CheckpointEmail->verify('client@company.com');
```
