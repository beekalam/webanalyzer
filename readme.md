intro
==============
This is a web frontend and also a REST API service for [syslogger](https://github.com/beekalam/syslogger).

# login credentials
username: admin
password: admin

Installation
===============

# installation in ubuntu 16.04
```
$ sudo apt-get install php5-intl 
$ sudo apt-get install php-intl 
```

# database server settings
copy `.env.sample` to `.env` and add your database credentials


# start a test webserver

```
$ php -S localhost:8000 -t /public
```


routes:

| method | URL                                           | desc                         |
| ------ | --------------------------------------------- | ---------------------------- |
| GET    | /logs/{page}                                  | all pagevisits               |
| GET    | /logs/{username}/{page}                       | page visits by username      |
| GET    | /logs/{username}/{startdate}/{enddate}/{page} | page visits by username,date |
| GET    | /nases                                        | list of nases                |
| POST   | /nases/add                                    | add a nas                    |
| POST   | /nases/delete/{id}                            | delete nas by id             |
| GET    | /rules                                        | list of rules                |
| POST   | /rules                                        | add a rule                   |
| POST   | /rules/delete/{id}                            | delete a rule by id                             |


# JSON return format for 
```
{
    "status" : "success",
    "data" : [],
    "total" : 1,                    // total number of pages
    "hasNext" : "true",             // has next page
    "hasPrev" : "true"              // has previous page
}
```

# JSON return format in case of error
```
{
    "status" : "error",
    "msg" : "error value"
}
```

# sample output for `/logs/{page}`
```
/logs/{page}

{
    "data": [
        {
            "weblog_id": 1781705,
            "username": "e-sharifii",
            "url": "http://download.cdn.mozilla.net/pub/firefox/releases/44.0b1/update/win32/en-US/firefox-44.0b1.complete.mar ",
            "visited_at": "1395-5-26 16:21:25",
            "ip": "192.168.192.160",
            "action": "allow",
            "domain": "download.cdn.mozilla.net",
            "serverside_file_type": null,
            "file_ext": "mar",
            "query": null,
            "path": "/pub/firefox/releases/44.0b1/update/win32/en-US/firefox-44.0b1.complete.mar ",
            "method": "GET",
            "nas_ip": "172.16.25.132",
            "params": null
        },
    ],
    "total": 72185,
    "hasNext": "true",
    "hasPrev": "false"
}
```


# sample output of `/nases`
```
{
    "data": [
        {
            "nas_id": 1,
            "nasip": "172.16.25.132",
            "username": "username",
            "password": "pass",
            "description": ""
        }
    ]
}
```


# sample output of `/rules`
```
{
"data": [
          {
            "exclusion_rules_id": 1,
            "exclusion_name": "by_ext",
            "exclusion_value": "js"
          },
            {
            "exclusion_rules_id": 9,
            "exclusion_name": "by_domain",
            "exclusion_value": "ocsp.digicert.com"
            }
    ]
}
```

