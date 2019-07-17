<h1>
Use of Lambda/Anonymous functions, Closures and Shared Instances in conjunction with Container
</h1>

<p>
<em>PHP</em> supports anonymous functions which are also known as closures. They are very much handy when used as callback parameters or rather the values of callback parameters. In PHP 5.3, anonymous functions are implemented by the Closure class and usually, they do not have any specified name. If you sometimes prefer your anonymous function not to be bound automatically by the current class then you may declared them statically as of PHP 5.4, which also prevents them not to be bounded at run-time by the class objects, <i><a href="https://www.php.net/manual/en/functions.anonymous.php">Ref</a></i>.
</p>

<p> 
Interestingly I have planed to write this article as I was vastly inspired by the post of Fabien Potencier on Lambda Functions, and Closures : "A lambda function is an anonymous PHP function that can be stored in a variable and passed as an argument to other functions or methods. A closure is a lambda function that is aware of its surrounding context". A details on this quoted topic can be found by the <a href="http://fabien.potencier.orgon-php-5-3-lambda-functions-and-closures.html"><i>Link</i></a>. 
</p>

<p> 
The PHP example depicted here focuses on to the operation of Lambda/anonymous functions, closures, shared instances and two of the PHP magic methods <strong>__set()</strong> and <strong>__get()</strong> by introducing them into a container. Something I need to mention here is that, I have already written an article on Dependency Injection Container wherein some of the topics of this article are already introduced. Therefore I am not going to discuss all of them in details here. But if someone is further interested then please, visit the <a href="https://medium.com/@annuhuss/dependency-injection-container-a-simple-introduction-for-managing-objects-from-their-creation-to-cebbcb772694"><i>Link</i></a> to go through how to implement Dependency Injection, shared instance and some PHP magic methods into a Dependency Injection Container.
</p>

<p>
Let's here introduce a lambda function which is known as anonymous function that does not have a predefined name unlike a general function. An anonymous function may be stored in a variable, further can be executed by calling that variable like some other functions. But it is not a requirement to store it in a variable, the following snippets of code clear the fact:
</p>

```php
$items = array(
                function($i) { var_dump('Item No-'. $i); },
                function($i) { var_dump('Item No-'. $i); },
                function($i) { var_dump('Item No-'. $i); },
         );
for ($i = 0; $i < count($items); $i++)
{
     $items[$i]($i+1);
}
```

<p>
As you can see from the code above, three anonymous functions is stored in an array and then the array itself is invoked for each of the functions. But when you using anonymous function as callback, you don't need to store it in a variable anymore as it can be seen by the code below:
</p>

```php
$items = range(1,3);
array_map(function($i) { var_dump( 'Item No-'. $i);}, $items);
```
<p> 
Nevertheless, both of the code segments produce the same results at the end.
</p>

<p>
Now, basically a closure is an anonymous function that encapsulates its scope, meaning that it has no access to the scope in which it is defined or executed. It is, however, possible to inherit variables from the parent scope (where the closure is defined) into the closure with the <strong>use</strong> keyword, <a href="https://www.php.net/manual/en/class.closure.php"><i>Ref</i></a>:
</p>

```php
$items = array(
             function($s) 
             { 
               return function($i) use ($s) { var_dump($s . $i); }; 
             },
             function($s) 
             { 
               return function($i) use ($s) { var_dump($s . $i); }; 
             },
             function($s) 
             { 
               return function($i) use ($s) { var_dump($s . $i); }; 
             },
         );
$string = 'Item No-';
for ($i = 0; $i < count($items); $i++)
{
    $callback = $items[$i]($string);
    $callback($i + 1);
}
```
<p>
As it can easily be seen from the above code, each anonymous function in the array elements is now wrapped by a closure giving each of them a parameter from its(the closure's) parent scope by the aid of <strong>use</strong> keyword. This is the beauty of using an anonymous function having a closure in many PHP applications.
</p>

<p>
The example in PHP below is mainly focused on to the operation of Lambda/anonymous functions, closures and shared instances by introducing them into a container.
</p>

<p>
<ul>
<li><strong>container.php</strong></li>
</ul>
</p>

<p>
<i>
A detail illustration on this topic and some of my other Object-Oriented-Programming articles can be found in 
<a href="https://medium.com/@annuhuss/">the medium blog site</a>.
</i>
</p>
