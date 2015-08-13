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
    'database' => 'Database Name',
);
```

## Create a Bean

In order to save or retrieve data from the database, we need to create a BEAN.

```
$bean = \Pyntax\PyntaxDAO::getBean('<TABLE_NAME>');
```

### Save a Bean
Once the bean is retrieved, the new value can be set by using the following method. Once all the required data has
been set, save() function is called to save the data in the database. When saving a new Bean save function will return
id of the new Record, when updating it will return a boolean value.

```
$bean-><Column Name> = 4;
$id = $bean->save();
```