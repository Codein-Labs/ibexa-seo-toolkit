# Robots

The `robot.txt` file is a text file that tells web robots which pages on the website to crawl and which ones not to.

The bundle eases the construction of this file.

## Configuration
Ibexa blocks the `robots.txt` file by default, add a rewrite rule to your vhost to use it :
```
<VirtualHost>
    ...
    RewriteRule ^/robots.txt ^/robots.txt
</VirtualHost>
```

```yml
# config/packages/codein_ibexa_seo_toolkit.yaml

codein_ibexa_seo_toolkit:
  system:
    default:
      robots:
        user_agents:
          googlebot:
            allow: ['/']
            crawl_delay: 120
            disallow: ['/search']
          '*':
            allow: ['/']
        sitemap_routes:
          - my_sitemap
        sitemap_urls:
          - "https://www.example.com/sitemap.xml"
```
This configuration allows the googlebot to crawl `/` and disallow it to crawl `/search`.

`'*'`: Allow or disallow crawl of certains pages for all robots.

`sitemap_routes`: Indicate the sitemap location to the robot file with Symfony routes.

`sitemap_urls`: Indicate the sitemap location to the robot file with absolute URLs.

## Result

```text
User-agent: googlebot
Crawl-Delay: 120
Disallow: /search
Allow: /
Sitemap: https://www.example.com/sitemap.xml

User-agent: *
Allow: /
Sitemap: https://www.example.com/sitemap.xml
```
