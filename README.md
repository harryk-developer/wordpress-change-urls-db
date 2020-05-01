# Wordpress Change Urls(domain name) in Database without Plugin.

## Example Usage:

include the script on the page and execute the URL from browser and remove the code after URL updates.

    include_once __DIR__.'/vendor/autoload.php';

    use harrykdeveloper\Wordpress\DbMigration;

    (new DbMigration())->setOptions([
        'db_name' => 'wordpress',
        'db_user' => 'root',
        'db_pass' => 'root',
        'db_host' => 'localhost:3308',
        'db_tables_prefix' => 'wp_',
        'old_domain' => 'http://localhost/oldurl',
        'new_domain' => 'http://localhost/newurl',
    ])->migrate();
