# Under Construction
Pyntax Framework is a ORM under construction. It will have the following functionality.
- Creating, Saving and Searching data in the database using beans.
- Generating a Form based on the bean and columns defined in the database.
- Generating a HTML based on the data returned after the search.

## Configure Database

Edit confing/config.php to add database details.

```
$pyntax_config['database'] = array(
    'server' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'simplemanager_db_v3',
);
```