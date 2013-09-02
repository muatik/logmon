logmon
======

yet another web based text log monitor tool.

more detailed documentation will be here soon.


____


LogMon API
=========

LogMon API provides necessary resources and functionalities for a monitoring client. The web interface of LogMon works through this API.

####Version####

1.0

API Functions
=========

####1) Registering and updating a project####

```PUT /v1/projects```
Registers a new project or updates a current one. This command neeeds a json object placed in http request body. Each json object requires the following members:

- name : project's name.
- codeName : project's code name
- logPath :  file system path of log, like /var/wwww/projectX/log.txt

An example json can be seen here: https://gist.github.com/muatik/6412938#file-project-json

####2) Getting list of projects####

```GET /v1/projects/list```
Returns a list of registered projects. Each entry consists of the following variables:

- name : project's name.
- codeName : project's code name
- logPath :  file system path of log, like /var/wwww/projectX/log.txt
- updateDate: the last update time as a timestamp 

An example json can be seen here: https://gist.github.com/muatik/6412938#file-projectslist-json

####3) Getting Log Entries####

An entry in a list is one log entry, in other words, a line in a log file. Each entry consists of the following variables. 

- project : the project's code name which the log entry belogns to.
- date : creation date of the entry
- type : type of the entry. this value can be ```debug```, ```error``` or ```info```
- text : text of the entry. the actual log message.

An example of a list can be seen here: https://gist.github.com/muatik/6412938#file-logmonentries-json

Below you can see the list of the possible queries:

#####GET /v1/log/entries#####
Returns a list of all log entries regardless of their types.

#####GET /v1/log/entries?project=projectCodeName#####
Returns a list of all log entries belonging to the given project.

#####GET /v1/log/entries?type=debug#####
Returns a list of all debug log. 

#####GET /v1/log/entries?type=error#####
Returns a list of all error log. 

#####GET /v1/log/entries?type=info#####
Returns a list of all info log. 

#####GET /v1/log/entries?'''after'''=timestamp#####
Returns a list of all log which are occured after the given time.

#####GET /v1/log/entries?before=timestamp#####
Returns a list of all log which are occured before the given time.

#####GET /v1/log/entries?contains=keyword#####
Returns a list of all log entries which contain the given keyword.

#####GET /v1/log/entries?limit=100#####
Returns a list of all log entries. This list can contain the given number entries at most. The default value of this parameter is 100.

You can combine the filtering options or can use only one of them.
A few examples:

- Returns error log entries which are occured after *Mon, 02 Sep 2013 12:35:12*, and contain "*fatal*" word:
```GET /v1/log/entries?type=error&after=1378125312&contain=fatal```

- Returns log entries which are occured between *Mon, 02 Sep 2013 12:30:12 GMT*, *Mon, 02 Sep 2013 12:35:12*
```GET /v1/log/entries?before=1378125312&after=1378125012```

- Returns error log entries containing "*too long*" word:
```GET /v1/log/entries?contain=too%20long```


___

License
-

Apache License Version 2.0
