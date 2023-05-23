# Matomo Tag Manager

This module is made to use the Matomo Tag Manager.

## Installation

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/matomo-manager-module:~1.0
```

## Usage
First you need to install an instance of matomo (see here https://github.com/matomo-org/matomo). \
Then you need to go to MatomoManager configuration page (`https://<your_domain>/admin/module/MatomoManager`) 
and configure the module with the data your instance matomo gives you.
This will generate both the head script and the body no-script tags and insert them in the ```main.head-top``` and 
in the ```main.body-top``` hooks. \
If these hooks are not present in your template, you'll need to add them beforehand.\
You must add ```main.footer-bottom``` hook or implement a button in all your pages to allow customers to cancel their consent to matomo tracking.

Example : 
``` html
<a href="javascript:removeConsent()">{intl l="I do not want to be tracked anymore"}</a>
```

## Migration form version 1.0 to 2.0 
This module no longer needs the whole script, just add the GTM id in the Thelia administration panel. 
The ```main.head-top``` hook should be present as it was used in 1.0 but you'll need to check the ```main.body-top``` one.\
If you had the noscript block in your template, you have to remove it as it will now be handled by this module. 

