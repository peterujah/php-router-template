# php-router-templete

A simple php class to help render template in routers and fix additional slash issue.

### IMPORTANT

You do not have to install, download nor use this class, i wrote the class for my personal needs, the first time i starting using router in my project instead of old ways. The only important the about this class is `deep` method which fixed the issue with extra shlash as posted on stackoverflow question here [style disappear when i add slash / after route](https://stackoverflow.com/questions/64298425/style-disappear-when-i-add-slash-after-route)

And making it easy for me to access any defined global variables within the template since am not using any php framework.
So please just ignore the project, even though i have claerly documented the class and showed usage sample, it because i like wasing my time on beautiful usles codes and likes to wrap all my ever writing functions is a class because i might need it again. What about composer installation? Yah i know, it free so i used.


Installation is super-easy via Composer:
```md
composer require peterujah/php-router-templete
```

# USAGES

Initialize RouterTemplate with the necessary parameters and register your custom classes.

```php 
$template = new \Peterujah\NanoBlock\RouterTemplate(__DIR__, false);
$template->addUser(new User(User::LIVE))->addFunc(new Functions())->addConfig(new Config());
```

Render template by passing the directory deep method as the first parameter while optional options array will be the second parameter.
```php
$template->Render("home")->with($template->deep(1));
```

A shorthand to the above method should be using `withDept` method and only passing the directory dept integer as the first parameter while optional options array will be the second parameter.
```php
$template->Render("home")->view(1);
```

Using the class with [Bramus Router](https://github.com/bramus/router) or any other php router as you wish. Initialize your router instance
```php 
$router = new \Bramus\Router\Router();
```

Render hompage template using `with` and `deep` method.
```php
$router->get('/', function() use ($template) {
    $template->Build("home")->with($template->deep(0));
});
```

Render hompage template using `view` method.
```php
$router->get('/', function() use ($template) {
    $template->Build("home")->view(0);
});
```

Render update product template with product id as the second url parameter.
```php
$router->get('/update/([a-zA-Z0-9]+)', function($id) use ($template) {
    $template->Build("product")->view(1);
    /*
      Using with method below
      $template->Build("product")->with($template->deep(1));
    */
});
```

Render update product template with product id as second url parameter and passing additional options to the template.
```php
$router->get('/update/([a-zA-Z0-9]+)', function($id) use ($template) {
    $template->Build("product")->view(1, [
      "foo" => "Our Foo"
      "bar" => "Our Bar id {$id}"
    ]);
});
```

Accessing all global variables within a template file `/router/product.php`.

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
