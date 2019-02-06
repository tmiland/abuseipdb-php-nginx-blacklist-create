# abuseipdb-php-nginx-blacklist-create
Creates a blacklist file for use in your Nginx configuration. Please see www.abuseipdb.com for API request limits. The "Free" account allows five requests/day. **All of the commands listed below require sudo or root privileges.**

## Installation

1. Sign up for an AbuseIPDB account to get your API key: www.abuseipdb.com. The API key used for this script is the "APIv2" key.

2. Clone this repository into your Nginx directory. *You must be the root user.* On Ubuntu, for example, clone into /etc/nginx/.

3. cd into abuseipdb-php-nginx-blacklist-create/ directory.

4. Copy config.dist.php to config.php and insert your key into the define('ABUSE_IP_DB_KEY', '<KEY HERE>'); block. Set the define('ABUSE_CONFIDENCE_SCORE', 80); to your liking.

5. Run the script using sudo or change to the root user and run the script, "php abuseipdb-blacklist-create.php"

6. At this point you should have a file named "nginx-abuseipdb-blacklist.conf" in the directory. It should contain a list of IP addresses on each line. For example, "deny ##.##.##.##;"

7. Add the following line near the top of the http section in your nginx.conf file:
```
    http {
            .....

            ## Block spammers and other unwanted visitors  ##
            include abuseipdb-php-nginx-blacklist-create/nginx-abuseipdb-blacklist.conf;

            ......
```
8. Test the Nginx configuration, "sudo nginx -t"

9. If all is well, reload Nginx. On Ubuntu, "sudo service nginx reload"

## Updating the Blacklist

1. Run the script using sudo or change to the root user and run the script, "php abuseipdb-blacklist-create.php"

2. Test the Nginx configuration, "sudo nginx -t"

3. If all is well, reload Nginx. On Ubuntu, "sudo service nginx reload"
