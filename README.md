# Documentation:
This is generated by [Grip](https://github.com/joeyespo/grip) using
````
grip README.md --export README.html
````
## Overview:
This is the Documentation of the Framework we are using in our self-developed KIS.

## Related Projects
We maintaince a list of other repositories to make this Framework work in the way we want it to:
(https://github.com/bennetgallein/VirtualizorPHP)[https://github.com/bennetgallein/VirtualizorPHP]
(https://github.com/bennetgallein/Trello-API-PHP-Wrapper)[https://github.com/bennetgallein/Trello-API-PHP-Wrapper]

## Module System:
The Heart of our System is the Module System which enables you to install and develop modules that perfectly fit your needs.
We offer basic documentation for ourselves, and for potential developers. Installing modules is pretty easy. Drop the Folder in the 
Modules Folder and you are good to go. The Structure of a module is pretty simple. In the following we'll break up one of our modules and explain the 
structure and the way the System handles the data.

This is our File: "supportmanager.json", which is the config-file for our support manager (who would have guessed that..):
````json
{
  "name": "Support Manager",
  "version": "0.1",
  "priority": "3",
  "baseperm": "2",
  "basepath": "support/",
  "authors": [
    {
      "name": "Bennet Gallein",
      "email": "bennet@intranetproject.net"
    },
    {
      "name": "Tobias Kalmbach",
      "email": "tobias@intranetproject.net"
    }
  ],
  "navs": [
    {
      "icon": "help_outline",
      "name": "Support Ticket",
      "link": "createticket.php",
      "permission": "1",
      "type": "nav"
    },
    {
      "icon": "help",
      "name": "Support Ticket Overview",
      "link": "ticketoverview.php",
      "permission": "2",
      "type": "nav"
    },
    {
      "link": "ticket.php",
      "permission": "2",
      "type": "page"
    }
  ],
  "dashboards": [
    {
      "link": "dashboard/include.php",
      "permission": "2"
    }
  ],
  "includeables": [
    {
      "name": "profile_overview",
      "link": "incl/ticketoverview.php",
      "permission": "2"
    }
  ]
}
````
Let's break this down a little bit:
````json
{
  "name": "Support Manager",
  "version": "0.1",
  "priority": "3",
  "baseperm": "2",
  "basepath": "support",
````
This is the part where the general information about the module is stored. 

| variable      | explanation |
| ------------- | ------------- | 
| name | The Name of the Module (access it over that |
| version | The Version of the module |
| priority | priority in the sidebar. lower means further up |
| baseperm | the baseperm to see anything of that module on the dashboard.|
| basepath | the basepath for the files from the module. |
  

Furthermore:
````json
"authors": [
    {
      "name": "Bennet Gallein",
      "email": "bennet@intranetproject.net"
    },
    {
      "name": "Tobias Kalmbach",
      "email": "tobias@intranetproject.net"
    }
  ],
````
This is where all the authors of the module should be listed.

| variable | explanation |
| --- | --- |
| name | the authors name |
| email | the email of the author |

This feature will come in handy if we are planning to realease a module managment system (MMS)

Let's continue with the important part:
````json
  "navs": [
    {
      "icon": "help_outline",
      "name": "Support Ticket",
      "link": "createticket.php",
      "permission": "1",
      "type": "nav"
    },
    {
      "icon": "help",
      "name": "Support Ticket Overview",
      "link": "ticketoverview.php",
      "permission": "2",
      "type": "nav"
    },
    {
      "link": "ticket.php",
      "permission": "2",
      "type": "page"
    }
  ],
````
This is pretty much the most important information in order to access modules.
We load the Modules dynamicly and those are the information we need in order to display them in the sidebar.

| variable | explanation |
| --- | --- |
| icon | the google icons icon |
| name | the display name |
| link | link to the file (relative from the config file) |
| permission | the min. permission to access that page |
| type | the type (atm. only "nav" and "page" are available) |

__note__: The type is important. Only navs with the type "nav" will be displayed in the navbar.

Next part:
````json
"dashboards": [
    {
      "link": "dashboard/include.php",
      "permission": "2"
    }
  ],
````
The Dashboard section are the collection of files that can be displayed on the dashboard (dashboard/index.php).

| variable | explanation | 
| --- | --- |
| link | link to the file (relative from the config file) |
| permission | the min. permission to view the file on the dashboard. |

The last part we have are 'includeables':
````json
"includeables": [
    {
      "name": "profile_overview",
      "link": "incl/ticketoverview.php",
      "permission": "2"
    }
  ]
````
These are files that can be included into other modules. 

| variable | explanation |
| --- | --- |
| name | name of the includeable. |
| link | link to the file (relative from the config file) |

Here is an example on how this can be used:
````php
$module = $db->getModuleByName("Support Manager");
if (isset($module)) {
    if ($module->getIncludeable("profile_overview")['permission'] <= $user->getPermissions()) {
        $re = include($module->getPath() . "/" . $module->getBasepath() . $module->getIncludeable("moneymethods")['link']);
    }
}
````

## Developing a module:
if you want to develop your own module, be sure to have the right config setup, as explained above. If so, you are ready to go.
There are some variables you don't want to overwrite! 
Here is a list of them:

| variable | usage |
| --- | --- |
| $db | this is the database variable |
| $user | this is the current logged in user object |
| $requestpath | The full request path |
| $params | the get params, if there are some |

Let's explain the $db and $user.

$user is the user object. Here are the functions you'll need to develop something:
````php
public function getId() {
    return $this->id;
}
public function getEmail() {
    return $this->email;
}
public function getFirstname() {
    return $this->firstname;
}
public function getLastname() {
    return $this->lastname;
}
public function getPassword() {
    return $this->password;
}
public function getRealid() {
    return $this->realid;
}
public function getName() {
    return $this->firstname . " " . $this->lastname;
}
public function getPermissions() {
    return $this->permissions;
}
````
Pretty self-explaining. The Database is a little more complicated:
If you want to make Database calls, just use this:
````php
$result = $db->simpleQuery("INSERT INTO lorem (name, helper) VALUES (" . $db->getConnection()->escape_string($name) . ", " . $db->getConnection()->escape_string("true") . ")");
````
If you want to use prepared Statements, use this function:
````php
$result = $db->prepareQuery("INSERT INTO lorem (name, helper) VALUES (?, ?)", array(
    $name,
    "true"
));
````
Both these functions return a mysqli result object.

This function returns the mysqli result set.
