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
The following code is need to be added to the bootloader of the application. We need to specify the folder from which
the config is suppose to be loaded.

```
\Pyntax\Pyntax::start(dirname(__FILE__)."/config");
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


## Generate a Form using a FormFactory and a Bean
A Bean can be used to generate a form which can be used to save and update bean in the database.
 
```
/**
 * This has to be added in the bootloader so the POST request can 
 * grabbed and the data can be saved to the database.
 */
\Pyntax\PyntaxDAO::run();

//Load the bean
$attachmentBean = \Pyntax\PyntaxDAO::getBean('attachments');

//Generate the form
echo \Pyntax\PyntaxDAO::generateForm($attachmentBean);

```

### Config for Forms
```
Pyntax\Config\Config::writeConfig('form', array(
    /**
     * @property: capturePostAndSaveBean
     * This allows the bean to be saved into the database when a form is submitted.
     */
    'capturePostAndSaveBean' => true,

    /**
     * @property: callback_before_capturePostAndSaveBean
     * This callback function can be used to inject more data like the users_id of the User who is logged in.
     */
    'callback_before_capturePostAndSaveBean' => function(array $data) {

    },

    /**
     * @property: callback_after_capturePostAndSaveBean
     */
    'callback_after_capturePostAndSaveBean' => function(\Pyntax\DAO\Bean\BeanInterface $bean, $id) {
        /**
         * @ToDo: If any things has to be done after save.
         */
    },

    /**
     * @property: showLabels
     * This is a  flag used to display the labels when a form is rendered
     */
    'showLabels' => true,

    /**
     * @property: convertColumnNamesIntoLabel
     * This allows the labels on the field to be formatted automatically depending on the field name. It removes
     * any "_" and "-" with a space and makes all the first alphabets of the words upper case.
     */
    'convertColumnNamesIntoLabel' => true,

    /**
     * @property: submitButton
     * This defines the template for Submit button that will be rendered in a form.
     * A Custom button can be defined in the beans key in the config array.
     *
     * - <TABLE_NAME> will be replicated with the table name in the Bean
     */
    'submitButton' => array(
        'tagName' => 'button',
        'attributes' => array(
            'type' => 'submit'
        ),
        'value' => 'Save'
    ),

    /**
     * @property: beans
     * This keys stores custom config for forms for particular beans.
     */
    'beans' => array(),

    /**
     * @property: formTemplate
     * This defines a default form tag with the defaults attributes
     */
    'formTemplate' => array(
        'tagName' => 'form',
        'attributes' => array(
            'method' => 'post',
            'class' => 'form-horizontal'
        )
    ),

    /**
     * @property: labelTempalte
     * This defines a default label tag with the default attributes
     */
    'labelTemplate' => array(
        'tagName' => 'label',
    ),

    /**
     * Defining callback functions on element rendering
     * Components in a Form:
     * 1. All the html elements
     * 2. Html element containers.
     *
     * Defining a container
     * 1. <HTML_Element_NAME>_container
     */
    'label_container' => array(
        'tagName' => 'div',
        'attributes' => array(
            'class' => 'col-sm-2 control-label'
        )
    ),
    'element_container' => array(
        'tagName' => 'div',
        'attributes' => array(
            'class' => 'col-sm-10'
        )
    ),
    'form_column' => array(
        'nbr_of_columns' => 2,
        'column_element_template' => array(
            'tagName' => ' div',
            'attributes' => array(
                'class' => 'row'
            )
        ),
        'container_element_template' => array(
            'tagName' => 'div',
            'attributes' => array(
                'class' => 'col-md-6'
            )
        ),

    ),
    'form_element_container_template' => array(
        'templateName' => 'html_element_template',
        'data' => array(
            'tagName' => 'div',
            'attributes' => array(
                'class' => 'form-group'
            )
        ),
    )
));
```

The Form generator will automatically remove the primary fields. The above config array is used to set all the HTML properties of the form.
The form will look as follows:

```
<form id="frm_attachments" method="post" class="form-horizontal">
   <div class="col-md-6">
      <div class="form-group">
         <div class="col-sm-2 control-label"> <label for="PyntaxDAO[attachments][file_name]"> File Name </label> </div>
         <div class="col-sm-10"> <input type="text" id="id_file_name" name="PyntaxDAO[attachments][file_name]" placeholder="file_name" class="form-control" value=""> </div>
      </div>
      <div class="form-group">
         <div class="col-sm-2 control-label"> <label for="PyntaxDAO[attachments][file_path]"> File Path </label> </div>
         <div class="col-sm-10"> <input type="text" id="id_file_path" name="PyntaxDAO[attachments][file_path]" placeholder="file_path" class="form-control" value=""> </div>
      </div>
   </div>
   <div class="col-md-6">
      <div class="form-group">
         <div class="col-sm-2 control-label"> <label for="PyntaxDAO[attachments][date_uploaded]"> Date Uploaded </label> </div>
         <div class="col-sm-10"> <input type="text" id="id_date_uploaded" name="PyntaxDAO[attachments][date_uploaded]" placeholder="date_uploaded" class="form-control" value=""> </div>
      </div>
      <div class="form-group">
         <div class="col-sm-2 control-label"> <label for="PyntaxDAO[attachments][file_type]"> File Type </label> </div>
         <div class="col-sm-10"> <input type="text" id="id_file_type" name="PyntaxDAO[attachments][file_type]" placeholder="file_type" class="form-control" value=""> </div>
      </div>
   </div>
   <input type="hidden" name="PyntaxDAO[BeanName]" value="attachments"><button type="Submit"> Save </button> 
</form>
```

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