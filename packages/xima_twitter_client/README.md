# XIMA TYPO3 Twitter client

This extension uses the Twitter v2 API to download and display tweets.

## Installation

Install via composer

```bash
composer require xima/xima-twitter-client
```

To use the Twitter API, you need a developer account, register your application and obtain

* consumer key
* consumer secret
* api key
* api secret

Enter the credentials as extension configuration via TYPO3 backend or `LocalConfiguration.php`:

````php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xima_twitter_client']['access_key'] = '',
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xima_twitter_client']['access_secret'] = '',
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xima_twitter_client']['api_key'] = '',
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xima_twitter_client']['api_secret'] = '',
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xima_twitter_client']['image_storage'] = '1:Images/Twitter',
````

The configuration `image_storage` is the combined identifier for the downloaded images.

## Configuration

1. Create a new SysFolder that includes the module "twitter"
2. Add a new "Account" record inside this folder
3. Enter a Twitter account name you want to fetch tweets from

## Usage

To start the download, run this command:

```bash
vendor/bin/typo3cms twitter:fetchTweets
```
