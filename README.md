# sf-6-music
Basic Sf 6 project to manage my music and playlists

Before executing the test the first time:
create folder data in var
php bin/console --env=test doctrine:database:create
bin/console --env=test doctrine:schema:create


Coding Standard Tools

PHP CS Fixer 
The configurastion file contains risky rules, so it must be used with the risky flag enabled
PHP Unit and PHP Doc rules are not included yet.

tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --allow-risky=yes 

PHP Stan
The code should be up to inspection level 6. it automatically uses the phpstan.dist.neon file


vendor/bin/phpstan analyse

vendor/bin/phpstan clear-result-cache 
