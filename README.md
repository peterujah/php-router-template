# php-router-templete-helper

# USAGES

Initialize TemplateHelper with the necessary parameters and register your custom classes.

```php 
$template = new \Peterujah\NanoBlock\TemplateHelper(__DIR__, true);
$template->addUser(new User(User::LIVE))->addFunc(new Functions())->addConfig(new Config());
```

Render template by passing the directory deep method as the first parameter while optional options array will be the second parameter.
```php
$template->Render("home")->with($template->deep(1));
```

A shorthand to the above method should be using `withDept` method and only passing the directory dept integer as the first parameter while optional options array will be the second parameter.
```php
$template->Render("home")->withDept(1);
```

Using the class with [Bramus Router](https://github.com/bramus/router) or any other php router as you wish. Initialize your router instance
```php 
$router = new \Bramus\Router\Router();
```

Render hompage template using `with` and `deep` method.
```php
$router->get('/', function() use ($template) {
    $template->Build("home")->with($template->deep(1));
});
```

Render hompage template using `withDept` method.
```php
$router->get('/', function() use ($template) {
    $template->Build("home")->withDept(1);
});
```

Render update product template with product id as the second url parameter.
```php
$router->get('/update/([a-zA-Z0-9]+)', function($id) use ($template) {
    $template->Build("product")->withDept(2);
    /*
      Using with method below
      $template->Build("product")->with($template->deep(2));
    */
});
```

Render update product template with product id as second url parameter and passing additional options to the template.
```php
$router->get('/update/([a-zA-Z0-9]+)', function($id) use ($template) {
    $template->Build("product")->withDept(2, [
      "foo" => "Our Foo"
      "bar" => "Our Bar id {$id}"
    ]);
});
```

Accessing all global variables within a template file `product.php`.

```php
<?php 
/*Secure template from unwanted access*/
$ALLOW_ACCESS or die("Access Denied");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Router template</title>
<!-- Unbreakable css path -->
<link href="<?php echo $root;?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<h1>News - <?php echo $config::NAME;?></h1>
<?php 
  /* Call Functions methods */
  $func->doStuff();
  
  /* Call User methods */
  $user->doAnything()
  
  /* Gets user information */
  $person->name;
  
  /* Access options from template */
  echo $self["foo"];
  
   /* Project root directory */
  echo $root;
?>
Unbreakable image 
<img src="<?php echo $root;?>assets/image/foo.png"/>

Unbreakable link 
<a href="<?php echo $root;?>newpage">A New Page</a>
</body>
</html>
```
