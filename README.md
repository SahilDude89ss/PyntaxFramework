#PyntaxFramework 
Everything you need for development

## Installation
### Server Requirements
The Pyntax framework requires php version > 5.4.31.

### Installing PyntaxFramework
Pyntax utilizes Composer to manage is dependencies. So, before using PyntaxFramework, make sure you have Composer
installed on your machine.

```
composer require pyntax/pyntax
composer install
```

## Configuration

### Basic Configuration
All of the configuration files for the Pyntax framework are stored in the config folder. 


## Configure Database

Edit confing/config.php to add database details.

```
Pyntax\Config\Config::writeConfig('database', array(
    'server' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'simplemanager_db_v3'
));
```

### Usage 
## Bootloader
The following code is need to be added to the bootloader of the application.

```
\Pyntax\PyntaxDAO::run();
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

## Find a Bean
Once an empty bean is retrieved it can be used to search for data in the database.

```
$bean = \Pyntax\PyntaxDAO::getBean('<TABLE_NAME>');
$bean->find(1);
```

This will load the data for record with primary key id 1 into the same bean.

### Finding a Bean with AND and OR

A Bean can be used to search for data using an Array. The following is an example of using AND and OR at the same time.

```
$clientBean->find(array('AND' => array(
    'first_name' => 'Sahil',
    'OR' => array(
        'last_name' => 'Sharma',
        'email' => 'SahilSHARM'
    )
)));
```

The above code will generate the following query:

```
SELECT
    *
FROM
    clients
WHERE
    first_name = 'Sahil' AND last_name = 'Sharma' OR email = 'SahilSHARM'";
```

If the search results returns more than one bean, it will return an array with the associated bean or if the search
returns only return one record it will return the object and also can be accessed from the base bean.


## Generate a Form  using a FormFactory and a Bean
A Bean can be used to generate a form which can be used to save and update bean in the database.
 
```
/**
 * This has to be added in the bootloader so the POST request can grabbed and the data can be saved to the database.
 */
\Pyntax\PyntaxDAO::run();

//Load the bean
$attachmentBean = \Pyntax\PyntaxDAO::getBean('attachments');

//Generate the form
$formFactory = new \Pyntax\Html\Form\FormFactory();
echo $formFactory->generateForm($attachmentBean);

```

At the moment the form is bare bone Form with no CSS but that will come with future releases. The Form generator will 
automatically remove the primary fields.

## Generate a Table using TableFactory and a Bean
A bean and a find query can be used to generate a table. PyntaxDAO, for now automatically finds the fields which are eligible for displaying. It removes
all id fields and only keep string fields.


```
$clientBean = \Pyntax\PyntaxDAO::getBean('clients');

$tableFactory = new Pyntax\Table\TableFactory;
$tableFactory->generateTable($clientBean, array('AND' => array(
    'first_name' => 'Sahil',
    'OR' => array(
        'last_name' => 'Sharma'
    )
)));
```