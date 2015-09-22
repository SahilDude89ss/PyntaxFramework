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
 * This has to be added in the bootloader so the POST request can 
 * grabbed and the data can be saved to the database.
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

The form will look as follows:

```
<div class="box box-primary">
   <form id="frm_users" method="post" class="form-horizontal">
      <div class="col-md-6">
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][title]"> Title </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_title" name="PyntaxDAO[users][title]" placeholder="title" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][first_name]"> First Name </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_first_name" name="PyntaxDAO[users][first_name]" placeholder="first_name" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][last_name]"> Last Name </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_last_name" name="PyntaxDAO[users][last_name]" placeholder="last_name" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][abn]"> Abn </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_abn" name="PyntaxDAO[users][abn]" placeholder="abn" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][company_name]"> Company Name </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_company_name" name="PyntaxDAO[users][company_name]" placeholder="company_name" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][address]"> Address </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_address" name="PyntaxDAO[users][address]" placeholder="address" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][suburb]"> Suburb </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_suburb" name="PyntaxDAO[users][suburb]" placeholder="suburb" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][state]"> State </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_state" name="PyntaxDAO[users][state]" placeholder="state" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][country]"> Country </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_country" name="PyntaxDAO[users][country]" placeholder="country" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][postcode]"> Postcode </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_postcode" name="PyntaxDAO[users][postcode]" placeholder="postcode" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][phone_work]"> Phone Work </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_phone_work" name="PyntaxDAO[users][phone_work]" placeholder="phone_work" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][phone_after_hours]"> Phone After Hours </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_phone_after_hours" name="PyntaxDAO[users][phone_after_hours]" placeholder="phone_after_hours" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][fax_number]"> Fax Number </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_fax_number" name="PyntaxDAO[users][fax_number]" placeholder="fax_number" class="form-control" value=""> </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][mobile_number]"> Mobile Number </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_mobile_number" name="PyntaxDAO[users][mobile_number]" placeholder="mobile_number" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][email]"> Email </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_email" name="PyntaxDAO[users][email]" placeholder="email" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][website]"> Website </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_website" name="PyntaxDAO[users][website]" placeholder="website" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][bank_account_name]"> Bank Account Name </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_bank_account_name" name="PyntaxDAO[users][bank_account_name]" placeholder="bank_account_name" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][bank_name]"> Bank Name </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_bank_name" name="PyntaxDAO[users][bank_name]" placeholder="bank_name" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][bank_bsb]"> Bank Bsb </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_bank_bsb" name="PyntaxDAO[users][bank_bsb]" placeholder="bank_bsb" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][bank_account_number]"> Bank Account Number </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_bank_account_number" name="PyntaxDAO[users][bank_account_number]" placeholder="bank_account_number" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][username]"> Username </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_username" name="PyntaxDAO[users][username]" placeholder="username" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][password]"> Password </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_password" name="PyntaxDAO[users][password]" placeholder="password" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][password_reset_token]"> Password Reset Token </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_password_reset_token" name="PyntaxDAO[users][password_reset_token]" placeholder="password_reset_token" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][password_last_reset]"> Password Last Reset </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_password_last_reset" name="PyntaxDAO[users][password_last_reset]" placeholder="password_last_reset" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][account_created]"> Account Created </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_account_created" name="PyntaxDAO[users][account_created]" placeholder="account_created" class="form-control" value=""> </div>
         </div>
         <div class="form-group">
            <div class="col-sm-2 control-label"> <label for="PyntaxDAO[users][account_expiry]"> Account Expiry </label> </div>
            <div class="col-sm-10"> <input type="text" id="id_account_expiry" name="PyntaxDAO[users][account_expiry]" placeholder="account_expiry" class="form-control" value=""> </div>
         </div>
      </div>
      <input type="hidden" name="PyntaxDAO[BeanName]" value="users"><button type="Submit"> Save </button> 
   </form>
</div>
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