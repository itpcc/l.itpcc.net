# l.itpcc.net
Simple short link service for itpcc.net using Google Sheet

## Why?

I need some way to keep short link easy (for me at least) to generate, share, and use! I, frankly, HATE me-qr or some bullshit QR generator services that keep showing advertisements before redirecting to THAT GOD DAMN DOCUMENT!

That's why.

## How?

0. You need ~~$5~~ ~~$6~~ server  (I use my existing PHP web server) with PHP support with `fopen` allowed (Since the script need to cache Google Sheet CSV info for **SPEED ~~AND POWER~~**.
1. Setup Google sheet to contain 2 column. One for the code, one for target URL. See [short-link.csv](./short-link.csv) for an example.
2. Setup web server to allow the following
  - Allow Read/Write file `./short-link.csv`.
  - Redirect the path to `index.php`. For Apache, just place [`.htacccess`](./htaccess) file. [For Nginx](https://www.cyberciti.biz/faq/how-to-configure-nginx-for-wordpress-permalinks/), set `location / { try_files $uri $uri/ /index.php?/$args; }`.
3. Place [`index.php`](./index.php) and change the following variable:
  - `$sheetUrl` to Google Sheet's published web URL.
  ![image](https://github.com/itpcc/l.itpcc.net/assets/3356814/ccfc23d6-9e02-4bf5-9777-bb7fc02503f3)
  ![image](https://github.com/itpcc/l.itpcc.net/assets/3356814/a45ec99a-3d61-4666-81f1-8c177b822604)
  - `$location` to default URL location.
  - `$serviceRoot` to your service' root URL. *Keep trailing slash*
4. Try!

If your setup correctly, once you go to `https://<YOUR DOMAIN>/google`, it should redirect to `https://google.com`. Or `https://<YOUR DOMAIN>/google?qrcode=1`, it should show a QR code of `https://google.com`.

## Note

This script use [Google Chart QR code service](https://developers.google.com/chart/infographics/docs/qr_codes) which is currently deprecated. Should the service suddenly stopped, please replace `https://chart.googleapis.com/chart` to somewhere else like [quickchart.io](https://quickchart.io/documentation/qr-codes/) or others.

## Disclaimer

This repo is not associate with Google.

