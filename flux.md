```
📂
╰─📄main.php 
  ├─✨define HOST
  ├─🌟const DS
  ├─🌟const HOME
  ╰─📃require system\main.php 
    ├─🌟const SYSTEM\HOME
    ├─🌟const SYSTEM\HELPERS
    ├─🌟const APPLICATIONS\HOME
    ├─📃require APPLICATIONS\HOME\"configuration.php";
    │ ╰─🌟const GATES 
    ├─🌟const APPLICATION\HOME
    ├─📃require APPLICATION\HOME\"configuration.php"
    │ ├─🌟const APPLICATION\NAME
    │ ├─🌟const APPLICATION\PRODUCTION
    │ ├─🌟const APPLICATION\LANGUAGE
    │ ├─🌟const APPLICATION\TIMEZONE
    │ ├─🌟const APPLICATION\HELPERS
    │ ├─🌟const APPLICATION\LOGS
    │ ╰─🌟const APPLICATION\ROUTES
    ├─📃require "Core\Autoload.php"
    ├─⭐getInstance Core\Autoload
    ├─⭐getInstance Core\ErrorHandler
    ╰─⭐getInstance Core\System
      ├─⭐getInstance ErrorHandler
      ├─🏃method configurePHP
      ├─🤞try
      │ ╰─🏃method route
      │   ╰─🏃require \APPLICATION\HOME\"*route*.php"
      ├─💀catch Exception 
      │ ╰─🏃method ErrorHandler::exception
      ╰─🏁finally
        ╰─🏃method response
  

⭕🔧💡🔨⭐⚡🔥💊🚨

🏃Function/Method
📄Access File
📃Required File
⭐Object
🌟Constant
✨Define
🤞try
💀catch
🏁finally
```